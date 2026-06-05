<?php

namespace App\Export;

use App\Models\AcademicYear;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class SelectionResultPdfExport
{
    private AcademicYear $activeYear;
    private array $filters;

    public function __construct(AcademicYear $activeYear, array $filters = [])
    {
        $this->activeYear = $activeYear;
        $this->filters    = $filters;
    }

    // Base query: peserta valid di tahun ajaran aktif
    private function baseQuery()
    {
        return Student::with('user')
            ->where('academic_year_id', $this->activeYear->id)
            ->where('validation_status', 'valid');
    }

    // Ambil data sesuai tab/filter
    private function getData(): array
    {
        $tab = $this->filters['tab'] ?? 'all';

        $allStudents = (clone $this->baseQuery())
            ->orderByRaw("FIELD(final_status, 'accepted', 'rejected', 'pending')")
            ->orderBy('specialization')
            ->orderBy('full_name')
            ->get();

        $tahfizStudents = (clone $this->baseQuery())
            ->where('specialization', 'tahfiz')
            ->where('final_status', 'accepted')
            ->orderBy('full_name')
            ->get();

        $languageStudents = (clone $this->baseQuery())
            ->where('specialization', 'language')
            ->where('final_status', 'accepted')
            ->orderBy('full_name')
            ->get();

        $regularStudents = (clone $this->baseQuery())
            ->where('specialization', 'regular')
            ->where('final_status', 'accepted')
            ->orderBy('created_at')
            ->get();

        $stats = [
            'total'    => $allStudents->count(),
            'accepted' => $allStudents->where('final_status', 'accepted')->count(),
            'rejected' => $allStudents->where('final_status', 'rejected')->count(),
            'pending'  => $allStudents->where('final_status', 'pending')->count(),
            'tahfiz'   => $tahfizStudents->count(),
            'language' => $languageStudents->count(),
            'regular'  => $regularStudents->count(),
        ];

        return compact(
            'tab',
            'allStudents',
            'tahfizStudents',
            'languageStudents',
            'regularStudents',
            'stats'
        );
    }

    // Stream download PDF
    public function download(string $filename)
    {
        $data = $this->getData();
        $data['activeYear']  = $this->activeYear;
        $data['printedAt']   = now()->format('d F Y, H:i') . ' WIB';

        $pdf = Pdf::loadView('exports.selection-result-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'     => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'dpi'             => 110,
            ]);

        return $pdf->download($filename);
    }

    // Stream inline (preview di browser)
    public function stream(string $filename)
    {
        $data = $this->getData();
        $data['activeYear']  = $this->activeYear;
        $data['printedAt']   = now()->format('d F Y, H:i') . ' WIB';

        $pdf = Pdf::loadView('exports.selection-result-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'     => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'dpi'             => 110,
            ]);

        return $pdf->stream($filename);
    }
}