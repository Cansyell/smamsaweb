<?php

namespace App\Export;

use App\Models\Student;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StudentExport
{
    private const LAST_COL = 'R';

    public function collection()
    {
        return Student::all();
    }

    public function headings()
    {
        return [
            'No', 'ID Siswa', 'NISN', 'Nama Lengkap', 'Nama Ayah', 'Nama Ibu',
            'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Umur', 'Alamat',
            'No. Telepon', 'Sekolah Asal', 'Tahun Lulus', 'No. KIP',
            'Spesialisasi', 'Status', 'Tanggal Daftar',
        ];
    }

    public function download(string $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        $lastCol = self::LAST_COL;

        // ── Baris 1: Nama sekolah ──────────────────────────────────
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'SMA MUHAMMADIYAH 1 PURWOKERTO');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // ── Baris 2: Alamat ────────────────────────────────────────
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Jl. Dr. Angka No.1, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(16);

        // ── Baris 3: Telp & Email ──────────────────────────────────
        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', 'Telp: (0281) 633373');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 10, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(16);

        // ── Baris 4: Garis pemisah ─────────────────────────────────
        $sheet->mergeCells("A4:{$lastCol}4");
        $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF1E1B4B']],
            ],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(4);

        // ── Baris 5: Judul dokumen ─────────────────────────────────
        $sheet->mergeCells("A5:{$lastCol}5");
        $sheet->setCellValue('A5', 'DAFTAR PESERTA PENERIMAAN MURID BARU');
        $sheet->getStyle('A5')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // ── Baris 6: Tanggal cetak ─────────────────────────────────
        $sheet->mergeCells("A6:{$lastCol}6");
        $sheet->setCellValue('A6', 'Dicetak pada: ' . now()->format('d F Y, H:i') . ' WIB');
        $sheet->getStyle('A6')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF6B7280'], 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(14);

        // ── Style background kop ───────────────────────────────────
        $sheet->getStyle("A1:{$lastCol}6")->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8F7FF']],
        ]);

        // ── Baris 7: Header kolom ──────────────────────────────────
        foreach ($this->headings() as $i => $heading) {
            $sheet->setCellValueByColumnAndRow($i + 1, 7, $heading);
        }
        $sheet->getStyle("A7:{$lastCol}7")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'name' => 'Arial', 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFFFFFF']]],
        ]);
        $sheet->getRowDimension(7)->setRowHeight(20);

        // ── Baris 8+: Data siswa ───────────────────────────────────
        foreach ($this->collection() as $i => $s) {
            $row       = $i + 8;
            $fillColor = ($i % 2 === 0) ? 'FFF8F7FF' : 'FFFFFFFF';

            $sheet->setCellValueByColumnAndRow(1,  $row, $i + 1);
            $sheet->setCellValueByColumnAndRow(2,  $row, $s->student_id);
            $sheet->setCellValueByColumnAndRow(3,  $row, $s->nisn);
            $sheet->setCellValueByColumnAndRow(4,  $row, $s->full_name);
            $sheet->setCellValueByColumnAndRow(5,  $row, $s->father_name);
            $sheet->setCellValueByColumnAndRow(6,  $row, $s->mother_name);
            $sheet->setCellValueByColumnAndRow(7,  $row, $s->gender === 'M' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValueByColumnAndRow(8,  $row, $s->place_of_birth);
            $sheet->setCellValueByColumnAndRow(9,  $row, $s->date_of_birth->format('d/m/Y'));
            $sheet->setCellValueByColumnAndRow(10, $row, $s->age . ' tahun');
            $sheet->setCellValueByColumnAndRow(11, $row, $s->address);
            $sheet->setCellValueByColumnAndRow(12, $row, $s->phone_number);
            $sheet->setCellValueByColumnAndRow(13, $row, $s->previous_school);
            $sheet->setCellValueByColumnAndRow(14, $row, $s->graduation_year);
            $sheet->setCellValueByColumnAndRow(15, $row, $s->kip_number ?? '-');
            $sheet->setCellValueByColumnAndRow(16, $row, $s->specialization_label ?? '-');
            $sheet->setCellValueByColumnAndRow(17, $row, ucfirst($s->validation_status));
            $sheet->setCellValueByColumnAndRow(18, $row, $s->created_at->format('d/m/Y'));

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $fillColor]],
                'font'      => ['name' => 'Arial', 'size' => 10],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFDDDDDD']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        $lastDataRow = $this->collection()->count() + 7;

        // ── Border luar tabel data ─────────────────────────────────
        $sheet->getStyle("A7:{$lastCol}{$lastDataRow}")->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF4F46E5']],
            ],
        ]);

        // ── Freeze di baris 8 (header tetap, kop tidak ikut freeze) ──
        // $sheet->freezePane('A8');

        // ── Auto width ────────────────────────────────────────────
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ── Print setup: tidak ada repeat rows ────────────────────
        $sheet->getPageSetup()
              ->setRowsToRepeatAtTopByStartAndEnd(0, 0)
              ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
              ->setFitToPage(true);

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}