<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Seleksi - {{ $student->full_name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --accepted:     #059669;
            --accepted-bg:  #d1fae5;
            --accepted-bdr: #6ee7b7;
            --rejected:     #dc2626;
            --rejected-bg:  #fee2e2;
            --rejected-bdr: #fca5a5;
            --cross:        #7c3aed;
            --cross-bg:     #ede9fe;
            --cross-bdr:    #c4b5fd;
            --dual:         #d97706;
            --dual-bg:      #fef3c7;
            --dual-bdr:     #fcd34d;
            --info:         #1d4ed8;
            --info-bg:      #dbeafe;
            --info-bdr:     #93c5fd;
            --warn:         #b45309;
            --warn-bg:      #fefce8;
            --warn-bdr:     #fde68a;
            --border:       #e2e8f0;
            --text:         #1e293b;
            --muted:        #64748b;
            --score:        #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            padding: 24px 16px;
            color: var(--text);
        }

        /* ── PRINT BUTTON ────────────────────────────────── */
        .print-button {
            display: block;
            margin: 0 auto 20px;
            background: #334155;
            color: #fff;
            border: none;
            padding: 11px 28px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: .4px;
            transition: background .15s;
        }
        .print-button:hover { background: #1e293b; }

        /* ── CARD SHELL ──────────────────────────────────── */
        .card {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
        }

        /* ── HEADER ──────────────────────────────────────── */
        .header {
            background: #0f172a;
            color: #fff;
            padding: 32px 36px;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 12px,
                rgba(255,255,255,.03) 12px,
                rgba(255,255,255,.03) 13px
            );
        }
        .header-inner { position: relative; }
        .header-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.3px;
            margin-bottom: 6px;
        }
        .header-sub {
            font-size: 13px;
            color: #94a3b8;
            display: flex;
            gap: 16px;
        }

        /* ── CONTENT WRAPPER ─────────────────────────────── */
        .content { padding: 32px 36px; }

        /* ── STATUS BANNER ───────────────────────────────── */
        .status-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 22px;
            border-radius: 12px;
            border: 1.5px solid;
            margin-bottom: 28px;
        }
        .status-banner.accepted {
            background: var(--accepted-bg);
            border-color: var(--accepted-bdr);
        }
        .status-banner.rejected {
            background: var(--rejected-bg);
            border-color: var(--rejected-bdr);
        }
        .status-icon {
            width: 44px; height: 44px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .accepted .status-icon { background: var(--accepted); }
        .rejected .status-icon { background: var(--rejected); }
        .status-text-main {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -.2px;
        }
        .accepted .status-text-main { color: var(--accepted); }
        .rejected .status-text-main { color: var(--rejected); }
        .status-text-sub { font-size: 13px; color: var(--muted); margin-top: 2px; }

        /* ── SECTION TITLE ───────────────────────────────── */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
        }

        /* ── INFO ROWS ───────────────────────────────────── */
        .info-section { margin-bottom: 28px; }
        .info-row {
            display: flex;
            padding: 11px 0;
            border-bottom: 1px solid #f1f5f9;
            gap: 16px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            flex: 0 0 190px;
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
        }
        .info-value {
            flex: 1;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        /* ── RANKING BOX ─────────────────────────────────── */
        .ranking-box {
            background: #f8fafc;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            gap: 0;
            margin-bottom: 28px;
        }
        .ranking-col {
            flex: 1;
            text-align: center;
            padding: 0 20px;
        }
        .ranking-col + .ranking-col {
            border-left: 1.5px solid var(--border);
        }
        .ranking-col-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }
        .ranking-number {
            font-family: 'DM Mono', monospace;
            font-size: 44px;
            font-weight: 500;
            color: var(--score);
            line-height: 1;
        }
        .ranking-of {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }
        .ranking-score {
            font-family: 'DM Mono', monospace;
            font-size: 28px;
            font-weight: 500;
            color: var(--accepted);
            line-height: 1;
        }
        .ranking-score-null {
            font-size: 20px;
            color: var(--muted);
        }

        /* ── NOTICE BOXES ─────────────────────────────────── */
        .notice {
            display: flex;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 10px;
            border-left: 4px solid;
            margin-bottom: 16px;
        }
        .notice-icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
        .notice-body { flex: 1; }
        .notice-title { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
        .notice-text  { font-size: 13px; line-height: 1.6; }

        /* Colour variants */
        .notice-success {
            background: var(--accepted-bg);
            border-color: var(--accepted);
        }
        .notice-success .notice-title { color: var(--accepted); }
        .notice-success .notice-text  { color: #065f46; }

        .notice-error {
            background: var(--rejected-bg);
            border-color: var(--rejected);
        }
        .notice-error .notice-title { color: var(--rejected); }
        .notice-error .notice-text  { color: #7f1d1d; }

        .notice-purple {
            background: var(--cross-bg);
            border-color: var(--cross);
        }
        .notice-purple .notice-title { color: var(--cross); }
        .notice-purple .notice-text  { color: #4c1d95; }

        .notice-amber {
            background: var(--dual-bg);
            border-color: var(--dual);
        }
        .notice-amber .notice-title { color: var(--dual); }
        .notice-amber .notice-text  { color: #78350f; }

        .notice-blue {
            background: var(--info-bg);
            border-color: var(--info);
        }
        .notice-blue .notice-title { color: var(--info); }
        .notice-blue .notice-text  { color: #1e3a8a; }

        .notice-yellow {
            background: var(--warn-bg);
            border-color: var(--warn);
        }
        .notice-yellow .notice-title { color: var(--warn); }
        .notice-yellow .notice-text  { color: #78350f; }

        /* ── DUAL PASS SCORE TABLE ───────────────────────── */
        .score-compare {
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--dual-bdr);
        }
        .score-compare table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .score-compare th {
            background: var(--dual-bg);
            padding: 7px 12px;
            text-align: left;
            font-weight: 700;
            color: var(--dual);
        }
        .score-compare td {
            padding: 7px 12px;
            color: #78350f;
            border-top: 1px solid var(--dual-bdr);
        }
        .score-compare .highlight td {
            background: #fffbeb;
            font-weight: 700;
        }
        .badge-rec {
            display: inline-block;
            background: var(--dual);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            margin-left: 6px;
            vertical-align: middle;
            letter-spacing: .5px;
        }

        /* ── SIGNATURE ───────────────────────────────────── */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box { text-align: center; flex: 0 0 200px; }
        .signature-label { font-size: 13px; color: var(--muted); margin-bottom: 56px; }
        .signature-line {
            border-top: 1px solid #334155;
            padding-top: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        /* ── FOOTER ──────────────────────────────────────── */
        .footer {
            background: #f8fafc;
            padding: 20px 36px;
            border-top: 1.5px solid var(--border);
            text-align: center;
            font-size: 11.5px;
            color: var(--muted);
            line-height: 1.8;
        }

        /* ── PRINT ───────────────────────────────────────── */
        @media print {
            body { background: #fff; padding: 0; }
            .card { border: none; border-radius: 0; box-shadow: none; }
            .no-print { display: none !important; }
        }

        .disclaimer { text-align: center; margin-top: 20px; font-size: 11.5px; color: #94a3b8; }
    </style>
</head>
<body>

<button onclick="window.print()" class="print-button no-print">🖨️ Cetak Kartu Hasil Seleksi</button>

<div class="card">

    {{-- ── HEADER ──────────────────────────────────────── --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-label">Sistem Penerimaan Peserta Didik Baru</div>
            <h1>Kartu Hasil Seleksi</h1>
            <div class="header-sub">
                <span>SMA Muhammadiyah 1 Purwokerto</span>
                <span>·</span>
                <span>Tahun Ajaran {{ $student->academicYear->year ?? '2024/2025' }}</span>
            </div>
        </div>
    </div>

    {{-- ── CONTENT ──────────────────────────────────────── --}}
    <div class="content">

        {{-- STATUS BANNER --}}
        <div class="status-banner {{ $myRanking['is_accepted'] ? 'accepted' : 'rejected' }}">
            <div class="status-icon">
                {{ $myRanking['is_accepted'] ? '✓' : '✗' }}
            </div>
            <div>
                <div class="status-text-main">
                    {{ $myRanking['is_accepted'] ? 'DITERIMA' : 'TIDAK DITERIMA' }}
                </div>
                <div class="status-text-sub">
                    @if($myRanking['is_accepted'])
                        Selamat — Anda berhasil lolos seleksi penerimaan siswa baru.
                    @else
                        Maaf, Anda belum berhasil lolos dalam seleksi ini.
                    @endif
                </div>
            </div>
        </div>

        {{-- DATA SISWA --}}
        <div class="info-section">
            <div class="section-title">Data Calon Siswa</div>
            <div class="info-row">
                <div class="info-label">Nomor Pendaftaran</div>
                <div class="info-value" style="font-family:'DM Mono',monospace;">{{ $student->student_id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NISN</div>
                <div class="info-value" style="font-family:'DM Mono',monospace;">{{ $student->nisn }}</div>
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
                <div class="info-label">Peminatan Pilihan</div>
                <div class="info-value">{{ strtoupper($labels[$student->specialization]) }}</div>
            </div>
            @if($student->cross_accepted)
            <div class="info-row">
                <div class="info-label">Diterima di Peminatan</div>
                <div class="info-value" style="color:var(--cross);font-weight:700;">
                    {{ strtoupper($labels[$student->accepted_specialization ?? 'regular']) }}
                    <span style="font-size:11px;font-weight:500;color:var(--muted);margin-left:6px;">(lintas jalur)</span>
                </div>
            </div>
            @endif
        </div>

        {{-- RANKING --}}
        <div class="section-title">Peringkat &amp; Skor</div>
        <div class="ranking-box">
            <div class="ranking-col">
                <div class="ranking-col-label">Peringkat Anda</div>
                <div class="ranking-number">{{ $myRanking['rank'] }}</div>
                <div class="ranking-of">dari {{ $myRanking['total_students'] }} siswa</div>
            </div>
            <div class="ranking-col">
                <div class="ranking-col-label">Skor Akhir SAW</div>
                @if(!is_null($myRanking['final_score']))
                    <div class="ranking-score">{{ number_format($myRanking['final_score'], 4) }}</div>
                @else
                    <div class="ranking-score-null">— FCFS —</div>
                @endif
                <div class="ranking-of">{{ $student->specialization === 'regular' ? 'First Come First Served' : 'Simple Additive Weighting' }}</div>
            </div>
        </div>

        {{-- ── INFORMASI TAMBAHAN ─────────────────────────── --}}
        <div class="info-section" style="margin-bottom:24px;">
            <div class="section-title">Informasi Tambahan</div>
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
                <div class="info-value">{{ now()->format('d F Y, H:i:s') }}</div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════
             NOTICE SECTION — logika bertingkat sesuai SOP
             ═══════════════════════════════════════════════════ --}}

        @php
            // Ambil skor SAW kedua jalur untuk dual-pass
            $tahfizScore   = null;
            $languageScore = null;
            if ($student->dual_pass) {
                $tahfizResult   = $student->sawResults
                    ->firstWhere('specialization', 'tahfiz');
                $languageResult = $student->sawResults
                    ->firstWhere('specialization', 'language');
                $tahfizScore    = $tahfizResult?->final_score;
                $languageScore  = $languageResult?->final_score;
            }
        @endphp

        {{-- ①  DITERIMA — normal, di spesialisasi pilihan sendiri --}}
        @if($myRanking['is_accepted'] && !$student->cross_accepted && !$student->dual_pass)
            <div class="notice notice-success">
                <div class="notice-icon">🎉</div>
                <div class="notice-body">
                    <div class="notice-title">Selamat! Anda Diterima</div>
                    <div class="notice-text">
                        Anda diterima di peminatan <strong>{{ strtoupper($labels[$student->specialization]) }}</strong>
                        sesuai pilihan Anda. Silakan menunggu informasi lebih lanjut mengenai
                        daftar ulang dan jadwal orientasi siswa baru.
                    </div>
                </div>
            </div>

        {{-- ②  DITERIMA — cross-accepted (belum dual-pass) --}}
        @elseif($myRanking['is_accepted'] && $student->cross_accepted && !$student->dual_pass)
            <div class="notice notice-purple">
                <div class="notice-icon">🔀</div>
                <div class="notice-body">
                    <div class="notice-title">Diterima Melalui Lintas Jalur</div>
                    <div class="notice-text">
                        Anda tidak lolos kuota peminatan
                        <strong>{{ strtoupper($labels[$student->specialization]) }}</strong>
                        (pilihan utama Anda), namun berhasil masuk melalui
                        <em>sisa kuota</em> peminatan
                        <strong>{{ strtoupper($labels[$student->accepted_specialization ?? 'regular']) }}</strong>.
                        Status ini resmi dan diakui oleh panitia seleksi.
                        Silakan hubungi panitia untuk konfirmasi daftar ulang.
                    </div>
                </div>
            </div>

        {{-- ③  DITERIMA — dual-pass --}}
        @elseif($myRanking['is_accepted'] && $student->dual_pass)

            {{-- 3a. Saran sudah sesuai (sudah di spesialisasi terbaik) --}}
            @if($dualPassInfo && $dualPassInfo['already_at_recommended'])
                <div class="notice notice-success">
                    <div class="notice-icon">✅</div>
                    <div class="notice-body">
                        <div class="notice-title">Diterima &amp; Pilihan Anda Sudah Optimal</div>
                        <div class="notice-text">
                            Anda lolos di kedua peminatan (Dual Pass). Pilihan Anda saat ini,
                            <strong>{{ strtoupper($labels[$student->specialization]) }}</strong>,
                            sudah merupakan peminatan dengan skor SAW tertinggi Anda.
                            Tidak diperlukan perubahan.
                        </div>
                    </div>
                </div>

            {{-- 3b. Saran pindah — skor di sebelah lebih tinggi --}}
            @elseif($dualPassInfo)
                <div class="notice notice-amber">
                    <div class="notice-icon">💡</div>
                    <div class="notice-body">
                        <div class="notice-title">Anda Lulus di Dua Peminatan — Disarankan Pindah</div>
                        <div class="notice-text">
                            Selamat, Anda lolos seleksi di <strong>kedua peminatan</strong> (Dual Pass).
                            Skor SAW Anda di peminatan
                            <strong>{{ strtoupper($labels[$dualPassInfo['recommended']]) }}</strong>
                            lebih tinggi dibandingkan pilihan utama Anda
                            (<strong>{{ strtoupper($labels[$dualPassInfo['chosen']]) }}</strong>).
                            Sesuai SOP sekolah, panitia menyarankan Anda untuk
                            <strong>berpindah ke peminatan {{ strtoupper($labels[$dualPassInfo['recommended']]) }}</strong>
                            agar potensi akademis Anda lebih optimal.
                        </div>

                        {{-- Tabel perbandingan skor --}}
                        @if(!is_null($tahfizScore) || !is_null($languageScore))
                        <div class="score-compare" style="margin-top:12px;">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Peminatan</th>
                                        <th>Skor SAW</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="{{ $dualPassInfo['recommended'] === 'tahfiz' ? 'highlight' : '' }}">
                                        <td>
                                            {{ $labels['tahfiz'] }}
                                            @if($dualPassInfo['recommended'] === 'tahfiz')
                                                <span class="badge-rec">DISARANKAN</span>
                                            @endif
                                        </td>
                                        <td style="font-family:'DM Mono',monospace;">
                                            {{ !is_null($tahfizScore) ? number_format($tahfizScore, 4) : '—' }}
                                        </td>
                                        <td>Lolos</td>
                                    </tr>
                                    <tr class="{{ $dualPassInfo['recommended'] === 'language' ? 'highlight' : '' }}">
                                        <td>
                                            {{ $labels['language'] }}
                                            @if($dualPassInfo['recommended'] === 'language')
                                                <span class="badge-rec">DISARANKAN</span>
                                            @endif
                                        </td>
                                        <td style="font-family:'DM Mono',monospace;">
                                            {{ !is_null($languageScore) ? number_format($languageScore, 4) : '—' }}
                                        </td>
                                        <td>Lolos</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <div class="notice-text" style="margin-top:10px;font-style:italic;color:#92400e;">
                            ⚠ Perubahan peminatan hanya dapat dilakukan melalui panitia seleksi
                            pada masa daftar ulang. Saran ini tidak mengikat — keputusan akhir
                            ada di tangan Anda dan orang tua/wali.
                        </div>
                    </div>
                </div>
            @endif

        {{-- ④  DITOLAK — potensi masuk regular (Pass 3) --}}
        @elseif(!$myRanking['is_accepted'] && in_array($student->specialization, ['tahfiz', 'language']))
            <div class="notice notice-error">
                <div class="notice-icon">❌</div>
                <div class="notice-body">
                    <div class="notice-title">Belum Diterima di Peminatan {{ strtoupper($labels[$student->specialization]) }}</div>
                    <div class="notice-text">
                        Peringkat Anda berada di luar kuota yang tersedia untuk peminatan
                        <strong>{{ strtoupper($labels[$student->specialization]) }}</strong>.
                    </div>
                </div>
            </div>

            @php
                // Cek apakah masih ada kuota regular
                $regularQuotaRemaining = ($quotaInfo['regular']['quota'] ?? 0)
                    - ($quotaInfo['regular']['accepted'] ?? 0);
            @endphp

            @if($regularQuotaRemaining > 0)
            <div class="notice notice-yellow">
                <div class="notice-icon">🔔</div>
                <div class="notice-body">
                    <div class="notice-title">Peluang: Masih Ada Kuota Kelas Reguler</div>
                    <div class="notice-text">
                        Sesuai SOP sekolah, siswa yang tidak lolos seleksi peminatan
                        masih dapat dipertimbangkan masuk jalur
                        <strong>Kelas Reguler</strong> (sistem FCFS — urutan pendaftaran)
                        selama kuota masih tersedia.
                        Saat ini masih tersedia <strong>{{ $regularQuotaRemaining }} tempat</strong>
                        di kelas Reguler.
                        <br><br>
                        Silakan hubungi panitia seleksi untuk mendaftarkan minat Anda ke
                        kelas Reguler sebelum masa pendaftaran ulang berakhir.
                    </div>
                </div>
            </div>
            @else
            <div class="notice notice-blue">
                <div class="notice-icon">ℹ️</div>
                <div class="notice-body">
                    <div class="notice-title">Informasi Selanjutnya</div>
                    <div class="notice-text">
                        Kuota kelas Reguler juga telah penuh. Silakan hubungi panitia seleksi
                        untuk informasi lebih lanjut mengenai opsi yang tersedia.
                    </div>
                </div>
            </div>
            @endif

        {{-- ⑤  DITOLAK — regular, kuota penuh --}}
        @elseif(!$myRanking['is_accepted'] && $student->specialization === 'regular')
            <div class="notice notice-error">
                <div class="notice-icon">❌</div>
                <div class="notice-body">
                    <div class="notice-title">Belum Diterima di Kelas Reguler</div>
                    <div class="notice-text">
                        Kuota kelas Reguler telah penuh dan nomor urut pendaftaran Anda
                        berada di luar kuota yang tersedia. Silakan hubungi panitia seleksi
                        untuk informasi lebih lanjut.
                    </div>
                </div>
            </div>
        @endif

        {{-- ── TANDA TANGAN ─────────────────────────────── --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Calon Siswa,</div>
                <div class="signature-line">{{ $student->full_name }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Panitia Seleksi,</div>
                <div class="signature-line">( ........................... )</div>
            </div>
        </div>

    </div>{{-- /content --}}

    {{-- ── FOOTER ───────────────────────────────────────── --}}
    <div class="footer">
        <div>Dokumen ini dicetak secara otomatis dari Sistem Informasi Penerimaan Siswa Baru</div>
        <div><strong>SMA Muhammadiyah 1 Purwokerto</strong></div>
        <div>Jl. Dr. Angka No.1, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115</div>
    </div>

</div>{{-- /card --}}

<div class="disclaimer no-print">
    <p>Kartu ini merupakan bukti resmi hasil seleksi peminatan.</p>
    <p>Simpan kartu ini untuk keperluan administrasi selanjutnya.</p>
</div>

</body>
</html>