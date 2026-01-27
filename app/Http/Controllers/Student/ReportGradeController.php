<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportGradeRequest;
use App\Http\Requests\UpdateReportGradeRequest;
use App\Models\ReportGrade;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Query student berdasarkan user_id
        $student = Student::where('user_id', auth()->id())->first();

        // Jika student belum ada, redirect ke profile
        if (!$student) {
            return redirect()->route('student.profile.create')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        // Ambil report grade dari student
        $reportGrade = $student->reportGrade;

        // Hitung progress
        $progress = $this->calculateProgress($student);

        return view('student.report-grades.index', compact('student', 'reportGrade', 'progress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Query student berdasarkan user_id
        $student = Student::where('user_id', auth()->id())->first();

        // Jika student belum ada, redirect ke profile
        if (!$student) {
            return redirect()->route('student.profile.create')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        // Jika sudah ada report grade, redirect ke edit
        if ($student->reportGrade) {
            return redirect()->route('student.report-grades.edit', $student->reportGrade)
                ->with('info', 'Anda sudah memiliki data nilai, silakan edit jika perlu perubahan');
        }

        // Hitung progress
        $progress = $this->calculateProgress($student);

        return view('student.report-grades.create', compact('student', 'progress'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportGradeRequest $request)
    {
        // Query student berdasarkan user_id
        $student = Student::where('user_id', auth()->id())->first();

        // Jika student belum ada, redirect ke profile
        if (!$student) {
            return redirect()->route('student.profile.create')
                ->with('error', 'Silakan lengkapi data pribadi terlebih dahulu');
        }

        // Jika sudah ada report grade, redirect ke edit
        if ($student->reportGrade) {
            return redirect()->route('student.report-grades.edit', $student->reportGrade)
                ->with('error', 'Anda sudah memiliki data nilai');
        }

        try {
            // Menggunakan static method dari model
            $data = $request->validated();
            $data['student_id'] = $student->id;
            
            ReportGrade::createGrade($data);

            return redirect()->route('student.report-grades.index')
                ->with('success', 'Data nilai raport berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportGrade $reportGrade)
    {
        // Pastikan report grade milik user yang login
        if ($reportGrade->student->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $student = $reportGrade->student;
        $progress = $this->calculateProgress($student);

        return view('student.report-grades.show', compact('reportGrade', 'student', 'progress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReportGrade $reportGrade)
    {
        // Pastikan report grade milik user yang login
        if ($reportGrade->student->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $student = $reportGrade->student;
        $progress = $this->calculateProgress($student);

        return view('student.report-grades.edit', compact('reportGrade', 'student', 'progress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportGradeRequest $request, ReportGrade $reportGrade)
    {
        // Pastikan report grade milik user yang login
        if ($reportGrade->student->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Menggunakan method dari model
            $reportGrade->updateGrade($request->validated());

            return redirect()->route('student.report-grades.index')
                ->with('success', 'Data nilai raport berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportGrade $reportGrade)
    {
        // Pastikan report grade milik user yang login
        if ($reportGrade->student->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $reportGrade->delete();

            return redirect()->route('student.report-grades.index')
                ->with('success', 'Data nilai raport berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Calculate progress for student registration
     */
    private function calculateProgress(Student $student): array
    {
        $steps = [
            'profile' => $student->exists,
            'grades' => $student->reportGrade !== null,
            'documents' => false, // Sesuaikan dengan kebutuhan
            'payment' => false, // Sesuaikan dengan kebutuhan
        ];

        $completed = count(array_filter($steps));
        $total = count($steps);
        $percentage = ($completed / $total) * 100;

        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => $percentage,
            'steps' => $steps,
        ];
    }
}