<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Seleksi - {{ $student->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .card {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #333;
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 30px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }

        .status-accepted {
            background: #10b981;
            color: white;
        }

        .status-rejected {
            background: #ef4444;
            color: white;
        }

        .info-section {
            margin: 20px 0;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-label {
            flex: 0 0 200px;
            font-weight: bold;
            color: #374151;
        }

        .info-value {
            flex: 1;
            color: #1f2937;
        }

        .ranking-box {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .ranking-number {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }

        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            flex: 0 0 200px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .card {
                border: none;
                border-radius: 0;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }
        }

        .print-button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin: 20px auto;
            display: block;
        }

        .print-button:hover {
            background: #5568d3;
        }

        .qr-code {
            margin: 20px 0;
            text-align: center;
        }

        .qr-code img {
            width: 120px;
            height: 120px;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">Cetak Kartu Hasil</button>

    <div class="card">
        <!-- Header -->
        <div class="header">
            <h1>KARTU HASIL SELEKSI PEMINATAN</h1>
            <p>SMK Muhammadiyah 1 Semarang</p>
            <p>Tahun Ajaran {{ $student->academicYear->year ?? '2024/2025' }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Status Badge -->
            <div style="text-align: center;">
                <span class="status-badge {{ $myRanking['is_accepted'] ? 'status-accepted' : 'status-rejected' }}">
                    {{ $myRanking['is_accepted'] ? 'DITERIMA' : 'TIDAK DITERIMA' }}
                </span>
            </div>

            <!-- Student Info -->
            <div class="info-section">
                <h2 style="color: #374151; margin-bottom: 15px; font-size: 20px;">Data Calon Siswa</h2>
                
                <div class="info-row">
                    <div class="info-label">Nomor Pendaftaran</div>
                    <div class="info-value">{{ $student->student_id }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">NISN</div>
                    <div class="info-value">{{ $student->nisn }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ strtoupper($student->full_name) }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Asal Sekolah</div>
                    <div class="info-value">{{ $student->previous_school }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Peminatan yang Dipilih</div>
                    <div class="info-value">{{ strtoupper($student->specialization_label) }}</div>
                </div>
            </div>

            <!-- Ranking Info -->
            <div class="ranking-box">
                <div style="font-size: 16px; color: #6b7280; margin-bottom: 10px;">PERINGKAT ANDA</div>
                <div class="ranking-number">{{ $myRanking['rank'] }}</div>
                <div style="font-size: 14px; color: #6b7280;">dari {{ $myRanking['total_students'] }} siswa</div>
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">Skor Akhir</div>
                    <div style="font-size: 24px; font-weight: bold; color: #10b981;">{{ number_format($myRanking['final_score'], 4) }}</div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="info-section">
                <h2 style="color: #374151; margin-bottom: 15px; font-size: 20px;">Informasi Tambahan</h2>
                
                <div class="info-row">
                    <div class="info-label">Kuota Peminatan</div>
                    <div class="info-value">{{ $quotaInfo[$student->specialization]['quota'] }} siswa</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Total Pendaftar</div>
                    <div class="info-value">{{ $myRanking['total_students'] }} siswa</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Tanggal Perhitungan</div>
                    <div class="info-value">{{ $myRanking['calculated_at']->format('d F Y') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Waktu Cetak</div>
                    <div class="info-value">{{ now()->format('d F Y H:i:s') }}</div>
                </div>
            </div>

            <!-- Note -->
            @if($myRanking['is_accepted'])
            <div style="background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <p style="color: #1e40af; font-size: 14px; margin: 0;">
                    <strong>Catatan:</strong> Selamat! Anda diterima di peminatan {{ strtoupper($student->specialization_label) }}. 
                    Silakan menunggu informasi lebih lanjut mengenai daftar ulang dan jadwal orientasi.
                </p>
            </div>
            @else
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <p style="color: #991b1b; font-size: 14px; margin: 0;">
                    <strong>Catatan:</strong> Maaf, Anda belum dapat diterima di peminatan {{ strtoupper($student->specialization_label) }} 
                    karena peringkat Anda di luar kuota yang tersedia. Silakan hubungi panitia untuk informasi lebih lanjut.
                </p>
            </div>
            @endif

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div style="margin-bottom: 10px;">Calon Siswa,</div>
                    <div class="signature-line">{{ $student->full_name }}</div>
                </div>
                
                <div class="signature-box">
                    <div style="margin-bottom: 10px;">Panitia Seleksi,</div>
                    <div class="signature-line">( ........................... )</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Penerimaan Siswa Baru</p>
            <p>SMK Muhammadiyah 1 Semarang</p>
            <p style="margin-top: 10px;">Jl. Tentara Pelajar No. 1, Semarang - Telp: (024) 8414168</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280;" class="no-print">
        <p>Kartu ini merupakan bukti resmi hasil seleksi peminatan</p>
        <p>Simpan kartu ini dengan baik untuk keperluan administrasi selanjutnya</p>
    </div>
</body>
</html>