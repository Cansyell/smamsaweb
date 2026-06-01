<?php

namespace App\Http\Controllers\Committee;

use App\Export\SelectionResultPdfExport;
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

    /**
     * Export hasil seleksi ke PDF menggunakan DomPDF.
     *
     * Query param:
     *   ?tab=all               → semua peserta
     *   ?tab=tahfiz            → hanya Tahfiz
     *   ?tab=language          → hanya Bahasa
     *   ?tab=regular           → hanya Regular
     *   ?tab=all_specializations → semua tab terpisah per halaman (default)
     *   ?mode=stream           → preview di browser (opsional)
     */
    public function exportPdf(Request $request)
    {
        $activeYear = AcademicYear::getActiveYear();

        if (! $activeYear) {
            return redirect()
                ->route('committee.dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $tab  = $request->input('tab', 'all_specializations');
        // 'all_specializations' = semua section dalam 1 PDF (default kalau tab = 'all')
        $mode = $request->input('mode', 'download'); // 'download' | 'stream'

        $tabLabels = [
            'all'                => 'Semua-Peserta',
            'tahfiz'             => 'Tahfiz',
            'language'           => 'Bahasa',
            'regular'            => 'Regular',
            'all_specializations'=> 'Semua-Spesialisasi',
        ];

        $filename = 'Hasil-Seleksi-PPDB-'
          . ($tabLabels[$tab] ?? $tab)
          . '-'
          . now()->format('Ymd_His')
          . '.pdf';

        $export = new SelectionResultPdfExport($activeYear, ['tab' => $tab]);

        return $mode === 'stream'
            ? $export->stream($filename)
            : $export->download($filename);
    }
}