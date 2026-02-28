<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Http\Request;

class SelectionResultController extends Controller
{
    /**
     * Tampilkan halaman hasil seleksi PPDB yang dikelompokkan
     * berdasarkan spesialisasi (Tahfiz / Bahasa / Regular).
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (! $activeYear) {
            return redirect()
                ->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        // ---------- Base query ----------
        $base = Student::with('user')
            ->where('academic_year_id', $activeYear->id)
            ->where('validation_status', 'valid');

        // ---------- Semua peserta (semua status) ----------
        $allStudents = (clone $base)
            ->orderByRaw("FIELD(final_status, 'accepted', 'rejected', 'pending')")
            ->orderBy('specialization')
            ->orderBy('full_name')
            ->get();

        // ---------- Tahfiz – hanya yang lulus ----------
        $tahfizStudents = (clone $base)
            ->where('specialization', 'tahfiz')
            ->where('final_status', 'accepted')
            ->orderBy('full_name')
            ->get();

        // ---------- Bahasa – hanya yang lulus ----------
        $languageStudents = (clone $base)
            ->where('specialization', 'language')
            ->where('final_status', 'accepted')
            ->orderBy('full_name')
            ->get();

        // ---------- Regular – hanya yang lulus, urut FCFS ----------
        $regularStudents = (clone $base)
            ->where('specialization', 'regular')
            ->where('final_status', 'accepted')
            ->orderBy('created_at')
            ->get();

        // ---------- Statistik ----------
        $stats = [
            'total'    => $allStudents->count(),
            'tahfiz'   => $tahfizStudents->count(),
            'language' => $languageStudents->count(),
            'regular'  => $regularStudents->count(),
        ];

        return view('committee.selection-results.index', compact(
            'activeYear',
            'allStudents',
            'tahfizStudents',
            'languageStudents',
            'regularStudents',
            'stats'
        ));
    }
}