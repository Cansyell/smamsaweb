<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hasil Seleksi PPDB</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #1a1a2e;
            background: #fff;
        }

        /* ── KOP ── */
        .kop {
            text-align: center;
            padding-bottom: 6px;
            border-bottom: 2.5px solid #1e1b4b;
            margin-bottom: 8px;
        }
        .kop-nama {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #1e1b4b;
        }
        .kop-alamat {
            font-size: 8pt;
            color: #555;
            margin-top: 2px;
        }
        .kop-telp {
            font-size: 8pt;
            color: #555;
        }

        /* ── JUDUL DOKUMEN ── */
        .doc-title {
            text-align: center;
            margin: 6px 0 2px;
        }
        .doc-title h2 {
            font-size: 11pt;
            font-weight: bold;
            color: #1e1b4b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 8pt;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 8px;
        }

        /* ── STATISTIK RINGKAS ── */
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border-collapse: separate;
            border-spacing: 4px;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 5px 8px;
            border-radius: 4px;
            width: 16.6%;
        }
        .stat-box .stat-label {
            font-size: 7.5pt;
            color: #555;
        }
        .stat-box .stat-value {
            font-size: 13pt;
            font-weight: bold;
        }
        .stat-total    { background: #ede9fe; }
        .stat-total    .stat-value { color: #4f46e5; }
        .stat-accepted { background: #dcfce7; }
        .stat-accepted .stat-value { color: #16a34a; }
        .stat-rejected { background: #fee2e2; }
        .stat-rejected .stat-value { color: #dc2626; }
        .stat-pending  { background: #fef9c3; }
        .stat-pending  .stat-value { color: #ca8a04; }
        .stat-tahfiz   { background: #ede9fe; }
        .stat-tahfiz   .stat-value { color: #7c3aed; }
        .stat-language { background: #e0f2fe; }
        .stat-language .stat-value { color: #0284c7; }
        .stat-regular  { background: #f0fdf4; }
        .stat-regular  .stat-value { color: #15803d; }

        /* ── SECTION HEADING ── */
        .section-title {
            font-size: 9pt;
            font-weight: bold;
            color: #fff;
            background: #4f46e5;
            padding: 4px 8px;
            margin: 10px 0 0 0;
            border-radius: 3px 3px 0 0;
        }

        /* ── TABEL DATA ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        thead tr th {
            background: #4f46e5;
            color: #fff;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            padding: 4px 5px;
            border: 1px solid #fff;
        }
        tbody tr td {
            font-size: 8pt;
            padding: 3px 5px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        tbody tr:nth-child(even) td {
            background: #f5f3ff;
        }
        tbody tr:nth-child(odd) td {
            background: #fff;
        }
        .td-no     { text-align: center; width: 24px; }
        .td-nisn   { text-align: center; width: 88px; }
        .td-nama   { width: 160px; }
        .td-jk     { text-align: center; width: 60px; }
        .td-spec   { text-align: center; width: 65px; }
        .td-status { text-align: center; width: 65px; }
        .td-reg    { text-align: center; width: 90px; }

        /* Status badge */
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-accepted { background: #dcfce7; color: #15803d; }
        .badge-rejected { background: #fee2e2; color: #b91c1c; }
        .badge-pending  { background: #fef9c3; color: #92400e; }
        .badge-tahfiz   { background: #ede9fe; color: #6d28d9; }
        .badge-language { background: #e0f2fe; color: #0369a1; }
        .badge-regular  { background: #f0fdf4; color: #15803d; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 14px;
            font-size: 7.5pt;
            color: #6b7280;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }

        /* ── PAGE BREAK ── */
        .page-break { page-break-before: always; }

        /* ── EMPTY STATE ── */
        .empty-row td {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 10px;
        }

        /* Tanda tangan */
        .ttd-area {
            margin-top: 20px;
            display: table;
            width: 100%;
        }
        .ttd-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            font-size: 8.5pt;
        }
        .ttd-line {
            margin: 40px auto 2px;
            width: 120px;
            border-bottom: 1px solid #1a1a2e;
        }
        .ttd-label { font-size: 7.5pt; color: #555; }
    </style>
</head>
<body>

    {{-- ════════════════════════════════════════════════════
         KOP SURAT
    ════════════════════════════════════════════════════ --}}
    <div class="kop">
        <div class="kop-nama">SMA MUHAMMADIYAH 1 PURWOKERTO</div>
        <div class="kop-alamat">Jl. Dr. Angka No.1, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115</div>
        <div class="kop-telp">Telp: (0281) 633373</div>
    </div>

    {{-- ════════════════════════════════════════════════════
         JUDUL
    ════════════════════════════════════════════════════ --}}
    <div class="doc-title">
        <h2>Hasil Seleksi Penerimaan Murid Baru (PPDB)</h2>
    </div>
    <div class="doc-subtitle">
        Tahun Ajaran: {{ $activeYear->year }} &nbsp;|&nbsp; Dicetak pada: {{ $printedAt }}
    </div>

    {{-- ════════════════════════════════════════════════════
         STATISTIK RINGKAS
    ════════════════════════════════════════════════════ --}}
    <div class="stats-row">
        <div class="stat-box stat-total">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Peserta</div>
        </div>
        <div class="stat-box stat-accepted">
            <div class="stat-value">{{ $stats['accepted'] }}</div>
            <div class="stat-label">Diterima</div>
        </div>
        <div class="stat-box stat-rejected">
            <div class="stat-value">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Tidak Diterima</div>
        </div>
        <div class="stat-box stat-pending">
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Belum Diproses</div>
        </div>
        <div class="stat-box stat-tahfiz">
            <div class="stat-value">{{ $stats['tahfiz'] }}</div>
            <div class="stat-label">Lulus Tahfiz</div>
        </div>
        <div class="stat-box stat-language">
            <div class="stat-value">{{ $stats['language'] }}</div>
            <div class="stat-label">Lulus Bahasa</div>
        </div>
        <div class="stat-box stat-regular" style="width:16.8%">
            <div class="stat-value">{{ $stats['regular'] }}</div>
            <div class="stat-label">Lulus Regular</div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════
         TAB ALL — Semua Peserta
    ════════════════════════════════════════════════════ --}}
    @if($tab === 'all' || $tab === 'all_specializations')
        <div class="section-title">Data Semua Peserta</div>
        <table>
            <thead>
                <tr>
                    <th class="td-no">No</th>
                    <th class="td-nisn">NISN / ID</th>
                    <th class="td-nama">Nama Lengkap</th>
                    <th class="td-jk">Jenis Kelamin</th>
                    <th class="td-spec">Spesialisasi</th>
                    <th class="td-status">Status</th>
                    <th class="td-reg">Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allStudents as $i => $s)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td class="td-nisn" style="font-size:7.5pt;">
                            {{ $s->nisn ?? '-' }}<br>
                            <span style="color:#9ca3af">{{ $s->student_id }}</span>
                        </td>
                        <td class="td-nama">{{ $s->full_name }}</td>
                        <td class="td-jk">{{ $s->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td class="td-spec">
                            @php
                                $specMap = ['tahfiz' => 'Tahfiz', 'language' => 'Bahasa', 'regular' => 'Regular'];
                            @endphp
                            <span class="badge badge-{{ $s->specialization }}">
                                {{ $specMap[$s->specialization] ?? $s->specialization }}
                            </span>
                        </td>
                        <td class="td-status">
                            @php
                                $statusMap = ['accepted' => 'Diterima', 'rejected' => 'Tidak Diterima', 'pending' => 'Pending'];
                            @endphp
                            <span class="badge badge-{{ $s->final_status }}">
                                {{ $statusMap[$s->final_status] ?? $s->final_status }}
                            </span>
                        </td>
                        <td class="td-reg" style="font-size:7.5pt;">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="7">Tidak ada data peserta.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- ════════════════════════════════════════════════════
         TAB TAHFIZ
    ════════════════════════════════════════════════════ --}}
    @if($tab === 'tahfiz' || $tab === 'all_specializations')
        @if($tab === 'all_specializations')
            <div class="page-break"></div>
        @endif
        <div class="section-title">Peserta Diterima — Spesialisasi Tahfiz</div>
        <table>
            <thead>
                <tr>
                    <th class="td-no">No</th>
                    <th class="td-nisn">NISN / ID</th>
                    <th class="td-nama">Nama Lengkap</th>
                    <th class="td-jk">Jenis Kelamin</th>
                    <th class="td-reg">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahfizStudents as $i => $s)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td class="td-nisn" style="font-size:7.5pt;">
                            {{ $s->nisn ?? '-' }}<br>
                            <span style="color:#9ca3af">{{ $s->student_id }}</span>
                        </td>
                        <td class="td-nama">{{ $s->full_name }}</td>
                        <td class="td-jk">{{ $s->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td class="td-reg" style="font-size:7.5pt;">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="5">Belum ada peserta yang diterima di spesialisasi Tahfiz.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- ════════════════════════════════════════════════════
         TAB BAHASA
    ════════════════════════════════════════════════════ --}}
    @if($tab === 'language' || $tab === 'all_specializations')
        @if($tab === 'all_specializations')
            <div style="margin-top:12px;"></div>
        @endif
        <div class="section-title">Peserta Diterima — Spesialisasi Bahasa</div>
        <table>
            <thead>
                <tr>
                    <th class="td-no">No</th>
                    <th class="td-nisn">NISN / ID</th>
                    <th class="td-nama">Nama Lengkap</th>
                    <th class="td-jk">Jenis Kelamin</th>
                    <th class="td-reg">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($languageStudents as $i => $s)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td class="td-nisn" style="font-size:7.5pt;">
                            {{ $s->nisn ?? '-' }}<br>
                            <span style="color:#9ca3af">{{ $s->student_id }}</span>
                        </td>
                        <td class="td-nama">{{ $s->full_name }}</td>
                        <td class="td-jk">{{ $s->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td class="td-reg" style="font-size:7.5pt;">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="5">Belum ada peserta yang diterima di spesialisasi Bahasa.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- ════════════════════════════════════════════════════
         TAB REGULAR
    ════════════════════════════════════════════════════ --}}
    @if($tab === 'regular' || $tab === 'all_specializations')
        @if($tab === 'all_specializations')
            <div style="margin-top:12px;"></div>
        @endif
        <div class="section-title">Peserta Diterima — Regular (Urut FCFS)</div>
        <table>
            <thead>
                <tr>
                    <th class="td-no">No</th>
                    <th class="td-nisn">NISN / ID</th>
                    <th class="td-nama">Nama Lengkap</th>
                    <th class="td-jk">Jenis Kelamin</th>
                    <th class="td-reg">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($regularStudents as $i => $s)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td class="td-nisn" style="font-size:7.5pt;">
                            {{ $s->nisn ?? '-' }}<br>
                            <span style="color:#9ca3af">{{ $s->student_id }}</span>
                        </td>
                        <td class="td-nama">{{ $s->full_name }}</td>
                        <td class="td-jk">{{ $s->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td class="td-reg" style="font-size:7.5pt;">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="5">Belum ada peserta yang diterima di Regular.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- ════════════════════════════════════════════════════
         TANDA TANGAN
    ════════════════════════════════════════════════════ --}}
    <div class="ttd-area">
        <div class="ttd-box"></div>
        <div class="ttd-box">
            <div style="font-size:8.5pt;">Purwokerto, {{ now()->format('d F Y') }}</div>
            <div style="font-size:8.5pt; font-weight:bold; margin-top:2px;">Panitia PPDB</div>
            <div class="ttd-line"></div>
            <div class="ttd-label">Nama &amp; Tanda Tangan</div>
        </div>
        <div class="ttd-box"></div>
    </div>

    {{-- ════════════════════════════════════════════════════
         FOOTER
    ════════════════════════════════════════════════════ --}}
    <div class="footer">
        <div class="footer-left">
            SMA Muhammadiyah 1 Purwokerto &nbsp;·&nbsp; Sistem PPDB
        </div>
        <div class="footer-right">
            Dicetak: {{ $printedAt }}
        </div>
    </div>

</body>
</html>