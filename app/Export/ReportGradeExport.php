<?php

namespace App\Export;

use App\Models\Student;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportGradeExport
{
    // Kolom A–P  (16 kolom)
    private const LAST_COL = 'P';

    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    // Ambil data siswa beserta nilai rapor sesuai filter

    public function collection()
    {
        $query = Student::with(['reportGrade', 'academicYear'])
            ->whereNotNull('full_name');

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%{$s}%")
                  ->orWhere('nisn', 'like', "%{$s}%")
                  ->orWhere('student_id', 'like', "%{$s}%");
            });
        }

        if (!empty($this->filters['academic_year_id'])) {
            $query->where('academic_year_id', $this->filters['academic_year_id']);
        }

        if (!empty($this->filters['specialization'])) {
            $query->where('specialization', $this->filters['specialization']);
        }

        if (!empty($this->filters['gender'])) {
            $query->where('gender', $this->filters['gender']);
        }

        if (!empty($this->filters['grade_status'])) {
            if ($this->filters['grade_status'] === 'has_grade') {
                $query->whereHas('reportGrade');
            } elseif ($this->filters['grade_status'] === 'no_grade') {
                $query->whereDoesntHave('reportGrade');
            }
        }

        return $query->orderBy('full_name')->get();
    }

    // Heading kolom
    public function headings(): array
    {
        return [
            'No',
            'ID Siswa',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Spesialisasi',
            'PAI',
            'B. Indonesia',
            'B. Inggris',
            'PKn',
            'Matematika',
            'IPA',
            'Seni Budaya',
            'Penjas',
            'Prakarya',
            'Rata-rata',
        ];
    }

    // Build spreadsheet & stream download
    public function download(string $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Nilai Rapor Siswa');

        $lastCol = self::LAST_COL;

        // Baris 1: Nama sekolah 
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'SMA MUHAMMADIYAH 1 PURWOKERTO');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Baris 2: Alamat
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Jl. Dr. Angka No.1, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(16);

        // Baris 3: Telp
        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Telp: (0281) 633373');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 10, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(16);

        // Baris 4: Garis pemisah
        $sheet->mergeCells("A4:{$lastCol}4");
        $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF1E1B4B']],
            ],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(4);

        // Baris 5: Judul dokumen 
        $sheet->mergeCells("A5:{$lastCol}5");
        $sheet->setCellValue('A5', 'REKAP NILAI RAPOR PESERTA PENERIMAAN MURID BARU');
        $sheet->getStyle('A5')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Baris 6: Filter info + tanggal cetak 
        $filterInfo = $this->buildFilterInfo();
        $sheet->mergeCells("A6:{$lastCol}6");
        $sheet->setCellValue('A6', $filterInfo . '  |  Dicetak pada: ' . now()->format('d F Y, H:i') . ' WIB');
        $sheet->getStyle('A6')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF6B7280'], 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(14);

        // Background kop 
        $sheet->getStyle("A1:{$lastCol}6")->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8F7FF']],
        ]);

        // Baris 7: Header kolom 
        foreach ($this->headings() as $i => $heading) {
            $sheet->setCellValueByColumnAndRow($i + 1, 7, $heading);
        }
        $sheet->getStyle("A7:{$lastCol}7")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'name' => 'Arial', 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFFFFFF']]],
        ]);
        $sheet->getRowDimension(7)->setRowHeight(24);

        // Baris 8+: Data siswa 
        $students    = $this->collection();
        $totalRows   = $students->count();
        $subjectCols = ['G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O']; // kolom nilai mapel

        foreach ($students as $i => $s) {
            $row       = $i + 8;
            $fillColor = ($i % 2 === 0) ? 'FFF8F7FF' : 'FFFFFFFF';
            $g         = $s->reportGrade;

            // Data identitas
            $sheet->setCellValueByColumnAndRow(1, $row, $i + 1);
            $sheet->setCellValueByColumnAndRow(2, $row, $s->student_id);
            $sheet->setCellValueByColumnAndRow(3, $row, $s->nisn);
            $sheet->setCellValueByColumnAndRow(4, $row, $s->full_name);
            $sheet->setCellValueByColumnAndRow(5, $row, $s->gender === 'M' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValueByColumnAndRow(6, $row, $s->specialization_label ?? '-');

            // Nilai per mapel
            $sheet->setCellValueByColumnAndRow(7,  $row, $g ? (float) $g->islamic_studies     : null);
            $sheet->setCellValueByColumnAndRow(8,  $row, $g ? (float) $g->indonesian_language  : null);
            $sheet->setCellValueByColumnAndRow(9,  $row, $g ? (float) $g->english_language     : null);
            $sheet->setCellValueByColumnAndRow(10, $row, $g ? (float) $g->ppkn                 : null);
            $sheet->setCellValueByColumnAndRow(11, $row, $g ? (float) $g->mtk                  : null);
            $sheet->setCellValueByColumnAndRow(12, $row, $g ? (float) $g->ipa                  : null);
            $sheet->setCellValueByColumnAndRow(13, $row, $g ? (float) $g->seni_budaya          : null);
            $sheet->setCellValueByColumnAndRow(14, $row, $g ? (float) $g->penjas               : null);
            $sheet->setCellValueByColumnAndRow(15, $row, $g ? (float) $g->prakarya             : null);

            // Rata-rata (formula Excel)
            if ($g) {
                $sheet->setCellValue("P{$row}", "=IFERROR(AVERAGE(G{$row}:O{$row}),\"-\")");
            } else {
                $sheet->setCellValue("P{$row}", '-');
            }

            // Style baris data
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $fillColor]],
                'font'      => ['name' => 'Arial', 'size' => 10],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFDDDDDD']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);

            // Format angka untuk kolom nilai (1 desimal)
            $sheet->getStyle("G{$row}:P{$row}")->getNumberFormat()->setFormatCode('0.00');

            // Warna rata-rata berdasarkan nilai
            if ($g && $g->average_grade) {
                $avg        = (float) $g->average_grade;
                $fontColor  = $avg >= 85 ? 'FF16A34A'
                            : ($avg >= 75 ? 'FF2563EB'
                            : ($avg >= 65 ? 'FFCA8A04'
                            : 'FFDC2626'));
                $sheet->getStyle("P{$row}")->getFont()
                      ->setBold(true)
                      ->getColor()->setARGB($fontColor);
            }
        }

        $lastDataRow = $totalRows + 7;

        // Baris ringkasan (rata-rata keseluruhan)
        if ($totalRows > 0) {
            $summaryRow = $lastDataRow + 1;
            $sheet->mergeCells("A{$summaryRow}:F{$summaryRow}");
            $sheet->setCellValue("A{$summaryRow}", 'Rata-rata Keseluruhan');
            $sheet->getStyle("A{$summaryRow}")->applyFromArray([
                'font'      => ['bold' => true, 'name' => 'Arial', 'size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ]);

            // Formula rata-rata tiap kolom mapel
            $mapelCols = range(7, 16); // kolom G–P
            $colLetters = ['G','H','I','J','K','L','M','N','O','P'];
            foreach ($colLetters as $col) {
                $formula = "=IFERROR(AVERAGE({$col}8:{$col}{$lastDataRow}),\"-\")";
                $sheet->setCellValue("{$col}{$summaryRow}", $formula);
                $sheet->getStyle("{$col}{$summaryRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'name' => 'Arial', 'size' => 10, 'color' => ['argb' => 'FF4F46E5']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle("{$col}{$summaryRow}")->getNumberFormat()->setFormatCode('0.00');
            }

            $sheet->getStyle("A{$summaryRow}:{$lastCol}{$summaryRow}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE0E7FF']],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF4F46E5']]],
            ]);
            $sheet->getRowDimension($summaryRow)->setRowHeight(20);
        }

        // Border luar tabel data
        $sheet->getStyle("A7:{$lastCol}{$lastDataRow}")->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF4F46E5']],
            ],
        ]);

        // Lebar kolom
        $colWidths = [
            'A' => 5,   // No
            'B' => 14,  // ID Siswa
            'C' => 14,  // NISN
            'D' => 28,  // Nama
            'E' => 12,  // JK
            'F' => 12,  // Spesialisasi
            'G' => 8,   // PAI
            'H' => 10,  // B. Indo
            'I' => 10,  // B. Ing
            'J' => 8,   // PKn
            'K' => 10,  // MTK
            'L' => 8,   // IPA
            'M' => 10,  // Seni
            'N' => 9,   // Penjas
            'O' => 10,  // Prakarya
            'P' => 12,  // Rata-rata
        ];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Alignment tengah untuk kolom nilai
        if ($totalRows > 0) {
            $sheet->getStyle("G8:P{$lastDataRow}")->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Print setup
        $sheet->getPageSetup()
              ->setRowsToRepeatAtTopByStartAndEnd(0, 0)
              ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
              ->setFitToPage(true);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // Helper: bangun keterangan filter untuk baris 6
    private function buildFilterInfo(): string
    {
        $parts = [];

        if (!empty($this->filters['specialization'])) {
            $map   = ['tahfiz' => 'Tahfiz', 'language' => 'Bahasa', 'regular' => 'Reguler'];
            $parts[] = 'Spesialisasi: ' . ($map[$this->filters['specialization']] ?? $this->filters['specialization']);
        }

        if (!empty($this->filters['gender'])) {
            $parts[] = 'Gender: ' . ($this->filters['gender'] === 'M' ? 'Laki-laki' : 'Perempuan');
        }

        if (!empty($this->filters['grade_status'])) {
            $parts[] = 'Status: ' . ($this->filters['grade_status'] === 'has_grade' ? 'Sudah Ada Nilai' : 'Belum Ada Nilai');
        }

        if (!empty($this->filters['search'])) {
            $parts[] = 'Kata kunci: "' . $this->filters['search'] . '"';
        }

        return $parts ? implode('  ·  ', $parts) : 'Semua data';
    }
}