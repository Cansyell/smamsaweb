<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Export\ReportGradeExport;
use App\Models\ReportGrade;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ReportGradeController extends Controller
{
    /**
     * Daftar nilai rapor semua siswa
     */
    public function index(Request $request)
    {
        $query = Student::with(['reportGrade', 'academicYear'])
            ->whereNotNull('full_name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('grade_status')) {
            if ($request->grade_status === 'has_grade') {
                $query->whereHas('reportGrade');
            } elseif ($request->grade_status === 'no_grade') {
                $query->whereDoesntHave('reportGrade');
            }
        }

        $sortBy    = $request->get('sort_by', 'full_name');
        $sortOrder = $request->get('sort_order', 'asc');

        if ($sortBy === 'average_grade') {
            $query->leftJoin('report_grades', 'students.id', '=', 'report_grades.student_id')
                  ->orderBy('report_grades.average_grade', $sortOrder)
                  ->select('students.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $students      = $query->paginate(15)->withQueryString();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $stats         = $this->getSummaryStats($request);

        return view('admin.report-grades.index', compact('students', 'academicYears', 'stats'));
    }

    /**
     * Detail nilai rapor satu siswa
     */
    public function show(Student $student)
    {
        $student->load(['reportGrade', 'academicYear', 'user']);

        return view('admin.report-grades.show', compact('student'));
    }

    /**
     * Form edit nilai rapor siswa
     */
    public function edit(Student $student)
    {
        $student->load(['reportGrade', 'academicYear']);

        return view('admin.report-grades.edit', compact('student'));
    }

    /**
     * Update / tambah nilai rapor siswa
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'islamic_studies'     => 'nullable|numeric|min:0|max:100',
            'indonesian_language' => 'nullable|numeric|min:0|max:100',
            'english_language'    => 'nullable|numeric|min:0|max:100',
            'ppkn'                => 'nullable|numeric|min:0|max:100',
            'mtk'                 => 'nullable|numeric|min:0|max:100',
            'ipa'                 => 'nullable|numeric|min:0|max:100',
            'seni_budaya'         => 'nullable|numeric|min:0|max:100',
            'penjas'              => 'nullable|numeric|min:0|max:100',
            'prakarya'            => 'nullable|numeric|min:0|max:100',
        ]);

        $reportGrade = $student->reportGrade;

        if ($reportGrade) {
            $reportGrade->updateGrade($validated);
            $message = 'Nilai rapor berhasil diperbarui.';
        } else {
            $validated['student_id'] = $student->id;
            ReportGrade::createGrade($validated);
            $message = 'Nilai rapor berhasil ditambahkan.';
        }

        return redirect()
            ->route('admin.report-grades.show', $student->id)
            ->with('success', $message);
    }

    /**
     * Hapus nilai rapor siswa
     */
    public function destroy(Student $student)
    {
        $reportGrade = $student->reportGrade;

        if (!$reportGrade) {
            return back()->with('error', 'Data nilai rapor tidak ditemukan.');
        }

        $reportGrade->delete();

        return redirect()
            ->route('admin.report-grades.index')
            ->with('success', "Nilai rapor {$student->full_name} berhasil dihapus.");
    }

    /**
     * Export nilai rapor ke Excel.
     * Filter yang aktif di halaman index ikut diterapkan pada hasil export.
     */
    public function export(Request $request)
    {
        $filters = $request->only([
            'search',
            'academic_year_id',
            'specialization',
            'gender',
            'grade_status',
        ]);

        $filename = 'nilai-rapor-siswa_' . now()->format('Ymd_His') . '.xlsx';

        return (new ReportGradeExport($filters))->download($filename);
    }

    // ─────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────

    private function getSummaryStats(Request $request): array
    {
        $baseQuery = Student::query();

        if ($request->filled('academic_year_id')) {
            $baseQuery->where('academic_year_id', $request->academic_year_id);
        }

        $totalStudents = $baseQuery->count();
        $withGrade     = (clone $baseQuery)->whereHas('reportGrade')->count();
        $withoutGrade  = $totalStudents - $withGrade;

        $avgGrade = ReportGrade::whereHas('student', function ($q) use ($request) {
            if ($request->filled('academic_year_id')) {
                $q->where('academic_year_id', $request->academic_year_id);
            }
        })->avg('average_grade');

        return [
            'total_students' => $totalStudents,
            'with_grade'     => $withGrade,
            'without_grade'  => $withoutGrade,
            'avg_grade'      => $avgGrade ? round($avgGrade, 2) : null,
            'completion_pct' => $totalStudents > 0
                ? round(($withGrade / $totalStudents) * 100, 1)
                : 0,
        ];
    }
}