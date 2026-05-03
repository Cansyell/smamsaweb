@extends('layouts.app')

@section('title', 'Hasil Perhitungan SAW')

@section('content')
<div class="space-y-6">

    {{-- ================================================================
         HEADER
    ================================================================ --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hasil Perhitungan SAW & Status Kelulusan</h2>
                <p class="text-gray-600 mt-1">Data kelulusan dan perankingan siswa berdasarkan spesialisasi</p>
            </div>
            <div class="flex gap-3 flex-wrap items-center">

                {{-- Status Publish Badge --}}
                @if($activeYear->result_status === 'published')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Hasil Dipublikasikan
                        <span class="text-xs font-normal opacity-75">{{ $activeYear->published_at?->format('d M Y H:i') }}</span>
                    </span>
                @elseif($activeYear->result_status === 'reviewing')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Sedang Direview
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-100 text-gray-600 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Draft
                    </span>
                @endif

                <a href="{{ route('committee.criterion-values.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm">
                    ← Kembali
                </a>

                {{-- Tombol Tentukan Penerimaan --}}
                @if($allStudents->isNotEmpty() && $activeYear->result_status !== 'published')
                <form action="{{ route('committee.criterion-values.determine-acceptance') }}" method="POST" id="acceptanceForm">
                    @csrf
                    <button type="button" onclick="confirmDetermineAcceptance()"
                            class="px-5 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tentukan Status Penerimaan
                    </button>
                </form>
                @endif

                {{-- Tombol Publish --}}
                @if($activeYear->result_status !== 'published')
                    <a href="{{ route('committee.publish-result.preview') }}"
                       class="px-5 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Publish Hasil
                    </a>
                @else
                    <a href="{{ route('committee.publish-result.preview') }}"
                       class="px-5 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                        Kelola Publikasi
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ================================================================
         ALERT MESSAGES
    ================================================================ --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-green-700 whitespace-pre-line">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- ================================================================
         DUAL PASS ALERT
    ================================================================ --}}
    @if($stats['dual_pass_count'] > 0)
    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <div class="text-sm text-amber-800">
                <p class="font-semibold mb-1">⭐ Terdapat {{ $stats['dual_pass_count'] }} siswa yang lulus di dua spesialisasi (Dual Pass)</p>
                <p>Siswa-siswa ini terlihat di tab <strong>Data Global</strong> dengan badge ★ Dual. Mereka mendapat notifikasi saran untuk mempertimbangkan pindah ke spesialisasi dengan skor SAW lebih tinggi.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         STATISTIK
    ================================================================ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        @php
        $statCards = [
            ['label'=>'Total Siswa',   'value'=>$stats['total_students'],  'color'=>'indigo', 'sub'=>'Semua jalur'],
            ['label'=>'Lulus',         'value'=>$stats['total_passed'],     'color'=>'green',  'sub'=>'siswa'],
            ['label'=>'Jalur Tahfiz',  'value'=>$stats['tahfiz_choice'],    'color'=>'emerald','sub'=>'mendaftar'],
            ['label'=>'Jalur Bahasa',  'value'=>$stats['language_choice'],  'color'=>'blue',   'sub'=>'mendaftar'],
            ['label'=>'Jalur Regular', 'value'=>$stats['regular_choice'],   'color'=>'purple', 'sub'=>'mendaftar'],
            ['label'=>'Dual Pass',     'value'=>$stats['dual_pass_count'],  'color'=>'amber',  'sub'=>'lulus 2 jalur'],
            ['label'=>'Cross',         'value'=>$stats['cross_accepted'],   'color'=>'orange', 'sub'=>'lintas jalur'],
        ];
        $colorMap = [
            'indigo'  => 'bg-indigo-50 text-indigo-700',
            'green'   => 'bg-green-50 text-green-700',
            'emerald' => 'bg-emerald-50 text-emerald-700',
            'blue'    => 'bg-blue-50 text-blue-700',
            'purple'  => 'bg-purple-50 text-purple-700',
            'amber'   => 'bg-amber-50 text-amber-700',
            'orange'  => 'bg-orange-50 text-orange-700',
        ];
        @endphp
        @foreach($statCards as $s)
        <div class="bg-white rounded-lg shadow p-4 {{ $colorMap[$s['color']] }}">
            <p class="text-xs font-medium opacity-70">{{ $s['label'] }}</p>
            <p class="text-2xl font-bold mt-1">{{ $s['value'] }}</p>
            <p class="text-xs opacity-60 mt-0.5">{{ $s['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         TABS
    ================================================================ --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-200 overflow-x-auto">
            <nav class="flex -mb-px min-w-max">
                @foreach([
                    ['global',   'Data Global',    'indigo'],
                    ['tahfiz',   'Ranking Tahfiz',  'green'],
                    ['language', 'Ranking Bahasa',  'blue'],
                    ['regular',  'Ranking Regular', 'purple'],
                ] as [$key, $label, $color])
                <button onclick="showTab('{{ $key }}')" id="tab-{{ $key }}"
                        class="tab-button px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap focus:outline-none transition-colors duration-200">
                    {{ $label }}
                    @if($key === 'global' && $stats['dual_pass_count'] > 0)
                        <span class="ml-1 px-1.5 py-0.5 text-xs bg-amber-100 text-amber-700 rounded-full">★ {{ $stats['dual_pass_count'] }}</span>
                    @endif
                </button>
                @endforeach
            </nav>
        </div>

        {{-- ──────────────── TAB: GLOBAL ──────────────── --}}
        <div id="content-global" class="tab-content">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-gray-800">Data Global — Semua Siswa</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Semua siswa dengan status kelulusan & ranking per jalur. Badge ★ Dual dan ↔ Cross hanya tampil di sini.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <select id="filterStatus" onchange="filterTable()" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Semua Status</option>
                        <option value="accepted">Lulus</option>
                        <option value="rejected">Tidak Lulus</option>
                        <option value="pending">Menunggu</option>
                    </select>
                    <select id="filterProgram" onchange="filterTable()" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Semua Program</option>
                        <option value="tahfiz">Tahfiz</option>
                        <option value="language">Bahasa</option>
                        <option value="regular">Regular</option>
                    </select>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">{{ $allStudents->count() }} Siswa</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="globalTable">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">NISN ↕</th>
                            <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">Nama ↕</th>
                            <th class="px-4 py-3 text-center">Program</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Rank Tahfiz</th>
                            <th class="px-4 py-3 text-center">Rank Bahasa</th>
                            <th class="px-4 py-3 text-center cursor-pointer hover:bg-gray-100" onclick="sortTable(6)">Tanggal ↕</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allStudents as $data)
                        <tr class="hover:bg-gray-50 transition-colors"
                            data-status="{{ $data['final_status'] }}"
                            data-program="{{ $data['student']->specialization }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700">{{ $data['student']->nisn }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full {{ $data['avatar_color'] }} flex items-center justify-center shrink-0">
                                        <span class="text-white font-bold text-xs">{{ substr($data['student']->full_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 flex items-center gap-1">
                                            {{ $data['student']->full_name }}
                                            @if($data['dual_pass'])
                                                <span class="px-1.5 py-0.5 text-xs bg-amber-100 text-amber-700 rounded-full font-semibold" title="Lulus 2 spesialisasi">★ Dual</span>
                                            @endif
                                            @if($data['cross_accepted'])
                                                <span class="px-1.5 py-0.5 text-xs bg-orange-100 text-orange-700 rounded-full font-semibold" title="Lulus lintas spesialisasi">↔ Cross</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-400">{{ $data['student']->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="px-2 py-1 inline-flex items-center text-xs font-semibold rounded-full {{ $data['program_badge_color'] }}">
                                    {!! $data['program_icon'] !!}{{ $data['program_label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @if($data['final_status'] === 'accepted')
                                    <span class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-full bg-green-100 text-green-700">✓ LULUS</span>
                                @elseif($data['final_status'] === 'rejected')
                                    <span class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-full bg-red-100 text-red-700">✗ TIDAK LULUS</span>
                                @else
                                    <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-gray-100 text-gray-500">⏳ Menunggu</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @include('committee.saw-results._rank-cell', ['rank'=>$data['tahfiz_rank'],'score'=>$data['tahfiz_score'],'color'=>'green'])
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @include('committee.saw-results._rank-cell', ['rank'=>$data['language_rank'],'score'=>$data['language_score'],'color'=>'blue'])
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm" data-date="{{ $data['validated_at'] ?? '' }}">
                                @if($data['validated_at'])
                                    <div class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($data['validated_at'])->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($data['validated_at'])->format('H:i') }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('committee.saw-results.show', $data['student']) }}"
                                   class="inline-flex items-center px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition text-xs font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada data siswa</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ──────────────── TAB: TAHFIZ ──────────────── --}}
        <div id="content-tahfiz" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-green-50 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="font-semibold text-gray-800">Ranking Jalur Tahfiz</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Hanya siswa yang memilih jalur Tahfiz, diurutkan berdasarkan SAW score.</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">{{ $tahfizRanking->count() }} Siswa</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-center w-20">Rank</th>
                            <th class="px-4 py-3 text-left">NISN</th>
                            <th class="px-4 py-3 text-left">Nama Siswa</th>
                            <th class="px-4 py-3 text-center">SAW Score</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tahfizRanking as $data)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-4 py-3 text-center">
                                @include('committee.saw-results._rank-badge', ['rank'=>$data['rank'],'color'=>'green'])
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700">{{ $data['student']->nisn }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shrink-0">
                                        <span class="text-white font-bold text-xs">{{ substr($data['student']->full_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $data['student']->full_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $data['student']->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-base font-bold text-green-700">{{ number_format($data['score'], 4) }}</div>
                                <div class="text-xs text-gray-400">Final Score</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($data['status_in_tab'] === 'accepted')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Lulus</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full font-semibold">Tidak Lulus</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('committee.saw-results.show', $data['student']) }}"
                                   class="inline-flex items-center px-3 py-1 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition text-xs font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada siswa jalur Tahfiz</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ──────────────── TAB: BAHASA ──────────────── --}}
        <div id="content-language" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="font-semibold text-gray-800">Ranking Jalur Bahasa</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Hanya siswa yang memilih jalur Bahasa, diurutkan berdasarkan SAW score.</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">{{ $languageRanking->count() }} Siswa</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-center w-20">Rank</th>
                            <th class="px-4 py-3 text-left">NISN</th>
                            <th class="px-4 py-3 text-left">Nama Siswa</th>
                            <th class="px-4 py-3 text-center">SAW Score</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($languageRanking as $data)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-4 py-3 text-center">
                                @include('committee.saw-results._rank-badge', ['rank'=>$data['rank'],'color'=>'blue'])
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700">{{ $data['student']->nisn }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shrink-0">
                                        <span class="text-white font-bold text-xs">{{ substr($data['student']->full_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $data['student']->full_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $data['student']->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-base font-bold text-blue-700">{{ number_format($data['score'], 4) }}</div>
                                <div class="text-xs text-gray-400">Final Score</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($data['status_in_tab'] === 'accepted')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Lulus</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full font-semibold">Tidak Lulus</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('committee.saw-results.show', $data['student']) }}"
                                   class="inline-flex items-center px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition text-xs font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada siswa jalur Bahasa</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ──────────────── TAB: REGULAR ──────────────── --}}
        <div id="content-regular" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-purple-50 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="font-semibold text-gray-800">Ranking Jalur Regular</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Berdasarkan waktu validasi (FCFS)</p>
                </div>
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">{{ $regularRanking->count() }} Siswa</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-center w-20">Rank</th>
                            <th class="px-4 py-3 text-left">NISN</th>
                            <th class="px-4 py-3 text-left">Nama Siswa</th>
                            <th class="px-4 py-3 text-center">Waktu Validasi</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($regularRanking as $index => $student)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-4 py-3 text-center">
                                @include('committee.saw-results._rank-badge', ['rank'=>$index+1,'color'=>'purple'])
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700">{{ $student->nisn }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shrink-0">
                                        <span class="text-white font-bold text-xs">{{ substr($student->full_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->full_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $student->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($student->validated_at)
                                    <div class="text-sm font-semibold text-purple-700">{{ \Carbon\Carbon::parse($student->validated_at)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($student->validated_at)->format('H:i:s') }}</div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($student->final_status === 'accepted')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Lulus</span>
                                @elseif($student->final_status === 'rejected')
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full font-semibold">Tidak Lulus</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded-full">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('committee.saw-results.show', $student) }}"
                                   class="inline-flex items-center px-3 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition text-xs font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada siswa jalur Regular</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Legenda badge — hanya relevan untuk tab Global --}}
    <div class="bg-white rounded-lg shadow-md p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Keterangan Badge <span class="font-normal text-gray-400">(berlaku di tab Data Global)</span></h3>
        <div class="flex flex-wrap gap-3 text-sm">
            <div class="flex items-center gap-2"><span class="px-2 py-0.5 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">↔ Cross</span><span class="text-gray-600">Lulus di spesialisasi bukan pilihannya (mengisi sisa slot)</span></div>
            <div class="flex items-center gap-2"><span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">★ Dual</span><span class="text-gray-600">Lulus di dua spesialisasi — mendapat saran pindah ke skor SAW lebih tinggi</span></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTab(name) {
    const colors = { global: 'indigo', tahfiz: 'green', language: 'blue', regular: 'purple' };
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(b => {
        b.classList.remove(
            'border-indigo-500','text-indigo-600',
            'border-green-500','text-green-600',
            'border-blue-500','text-blue-600',
            'border-purple-500','text-purple-600'
        );
        b.classList.add('border-transparent','text-gray-500');
    });
    document.getElementById('content-' + name).classList.remove('hidden');
    const btn = document.getElementById('tab-' + name);
    btn.classList.remove('border-transparent','text-gray-500');
    const c = colors[name];
    btn.classList.add(`border-${c}-500`, `text-${c}-600`);
}

function filterTable() {
    const status  = document.getElementById('filterStatus').value;
    const program = document.getElementById('filterProgram').value;
    document.querySelectorAll('#globalTable tbody tr').forEach(row => {
        if (row.cells.length < 2) return;
        const rowStatus  = row.dataset.status;
        const rowProgram = row.dataset.program;
        let show = true;
        if (status !== 'all' && rowStatus !== status) show = false;
        if (program !== 'all' && rowProgram !== program) show = false;
        row.style.display = show ? '' : 'none';
    });
}

let sortDir = {};
function sortTable(col) {
    const tbody = document.querySelector('#globalTable tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => r.cells.length > 1);
    sortDir[col] = sortDir[col] === 'asc' ? 'desc' : 'asc';
    rows.sort((a, b) => {
        let av = col === 6 ? (a.cells[col].dataset.date || '') : a.cells[col].textContent.trim();
        let bv = col === 6 ? (b.cells[col].dataset.date || '') : b.cells[col].textContent.trim();
        return sortDir[col] === 'asc' ? av.localeCompare(bv) : bv.localeCompare(av);
    });
    rows.forEach(r => tbody.appendChild(r));
}

function confirmDetermineAcceptance() {
    const total    = {{ $stats['total_students'] }};
    const tahfiz   = {{ $stats['tahfiz_choice'] }};
    const language = {{ $stats['language_choice'] }};
    const regular  = {{ $stats['regular_choice'] }};

    if (!confirm(
        `Tentukan status penerimaan untuk ${total} siswa?\n` +
        `• Tahfiz  : ${tahfiz} siswa (SAW)\n` +
        `• Bahasa  : ${language} siswa (SAW)\n` +
        `• Regular : ${regular} siswa (FCFS)\n\n` +
        `Proses ini juga mendeteksi siswa Dual Pass & Cross.\n\nLanjutkan?`
    )) return;

    const form = document.getElementById('acceptanceForm');
    const btn  = form.querySelector('button');
    btn.disabled = true;
    btn.innerHTML = `<svg class="w-4 h-4 inline mr-1 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memproses...`;
    form.submit();
}

document.addEventListener('DOMContentLoaded', () => showTab('global'));
</script>
@endpush
@endsection