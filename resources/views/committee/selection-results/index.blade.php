@extends('layouts.app')

@section('title', 'Hasil Seleksi PPDB')

@section('content')
<div class="space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">Hasil Seleksi PPDB</h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Rekapitulasi hasil seleksi
                        @if($activeYear)
                            &mdash; Tahun Ajaran <span class="font-medium text-indigo-600">{{ $activeYear->name }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">

                {{-- Export Dropdown --}}
                <div class="relative">
                    <a href="{{ route('committee.selection-results.export-pdf') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 active:scale-95 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export PDF
                        </a>

                    <div id="export-dropdown"
                         class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl border border-gray-100 shadow-lg z-30 py-1 overflow-hidden">

                        <div class="px-3 pt-2 pb-1">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Tab aktif</p>
                        </div>
                        <a href="#" id="export-active-tab-link" target="_blank"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                            <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download tab ini
                        </a>

                        <div class="border-t border-gray-50 my-1"></div>
                        <div class="px-3 pt-1 pb-1">
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Per spesialisasi</p>
                        </div>
                        <a href="{{ route('committee.selection-results.export-pdf', ['tab' => 'all_specializations']) }}" target="_blank"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                            Semua (3 spesialisasi)
                        </a>
                        <a href="{{ route('committee.selection-results.export-pdf', ['tab' => 'tahfiz']) }}" target="_blank"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                            Tahfiz saja
                        </a>
                        <a href="{{ route('committee.selection-results.export-pdf', ['tab' => 'language']) }}" target="_blank"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                            Bahasa saja
                        </a>
                        <a href="{{ route('committee.selection-results.export-pdf', ['tab' => 'regular']) }}" target="_blank"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-violet-50 hover:text-violet-700 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-violet-500 flex-shrink-0"></span>
                            Regular saja
                        </a>
                    </div>
                </div>

                <a href="{{ route('committee.saw-results.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 active:scale-95 rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php
        $statCards = [
            ['label' => 'Total peserta', 'value' => $stats['total'],    'color' => 'indigo',  'icon' => 'M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m4-4a4 4 0 100-8 4 4 0 000 8z'],
            ['label' => 'Lulus Tahfiz',  'value' => $stats['tahfiz'],   'color' => 'emerald', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'Lulus Bahasa',  'value' => $stats['language'], 'color' => 'blue',    'icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
            ['label' => 'Lulus Regular', 'value' => $stats['regular'],  'color' => 'violet',  'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        $colorMap = [
            'indigo'  => ['icon' => 'bg-indigo-100 text-indigo-600',  'val' => 'text-indigo-700'],
            'emerald' => ['icon' => 'bg-emerald-100 text-emerald-600','val' => 'text-emerald-700'],
            'blue'    => ['icon' => 'bg-blue-100 text-blue-600',      'val' => 'text-blue-700'],
            'violet'  => ['icon' => 'bg-violet-100 text-violet-600',  'val' => 'text-violet-700'],
        ];
        @endphp

        @foreach($statCards as $card)
        @php $c = $colorMap[$card['color']]; @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl {{ $c['icon'] }} flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 leading-none mb-1">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold {{ $c['val'] }} leading-none">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABS + TABLE --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-100 px-4 pt-2">
            <nav class="flex gap-1">
                @foreach([
                    ['key' => 'all',      'label' => 'Semua',   'count' => $allStudents->count()],
                    ['key' => 'tahfiz',   'label' => 'Tahfiz',  'count' => $tahfizStudents->count()],
                    ['key' => 'language', 'label' => 'Bahasa',  'count' => $languageStudents->count()],
                    ['key' => 'regular',  'label' => 'Regular', 'count' => $regularStudents->count()],
                ] as $tab)
                <button onclick="showTab('{{ $tab['key'] }}')"
                        id="tab-{{ $tab['key'] }}"
                        class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition-all focus:outline-none whitespace-nowrap">
                    {{ $tab['label'] }}
                    <span id="badge-{{ $tab['key'] }}"
                          class="ml-1.5 px-1.5 py-0.5 text-xs rounded-md bg-gray-100 text-gray-500 font-medium transition-colors">
                        {{ $tab['count'] }}
                    </span>
                </button>
                @endforeach
            </nav>
        </div>

        {{-- Search --}}
        <div class="px-5 py-3 border-b border-gray-50 flex items-center gap-3">
            <div class="relative flex-1 max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="search-input" placeholder="Cari nama / NISN..."
                       oninput="filterTable()"
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-400 transition">
            </div>
            <p id="result-count" class="text-xs text-gray-400"></p>
        </div>

        {{-- TAB: SEMUA --}}
        <div id="content-all" class="tab-content">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Semua Peserta</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Seluruh peserta yang telah divalidasi</p>
                    </div>
                    <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-full text-xs font-semibold shadow-sm">
                        {{ count($allStudents) }} Peserta
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Registrasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Spesialisasi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil Seleksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($allStudents as $i => $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 text-center text-sm text-gray-400 font-mono">{{ $i + 1 }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700">{{ $student->student_id ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700 search-nisn">{{ $student->nisn ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-xs text-white flex-shrink-0
                                        @if($student->specialization === 'tahfiz') bg-gradient-to-br from-emerald-400 to-emerald-600
                                        @elseif($student->specialization === 'language') bg-gradient-to-br from-blue-400 to-blue-600
                                        @else bg-gradient-to-br from-violet-400 to-violet-600
                                        @endif">
                                        {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 search-name">{{ $student->full_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                @if($student->specialization === 'tahfiz')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                        Tahfiz
                                    </span>
                                @elseif($student->specialization === 'language')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        Bahasa
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-violet-100 text-violet-700">
                                        Regular
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                @if($student->final_status === 'accepted')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        LULUS
                                    </span>
                                @elseif($student->final_status === 'rejected')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-red-100 text-red-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        TIDAK LULUS
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Belum ada data peserta.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: TAHFIZ --}}
        <div id="content-tahfiz" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Spesialisasi Tahfiz</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Peserta yang diterima melalui seleksi SAW</p>
                    </div>
                    <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-full text-xs font-semibold shadow-sm">
                        {{ count($tahfizStudents) }} Peserta
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Registrasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil Seleksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($tahfizStudents as $i => $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 text-center text-sm text-gray-400 font-mono">{{ $i + 1 }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700">{{ $student->student_id ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700 search-nisn">{{ $student->nisn ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-xs text-white flex-shrink-0 bg-gradient-to-br from-emerald-400 to-emerald-600">
                                        {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 search-name">{{ $student->full_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                @if($student->final_status === 'accepted')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        LULUS
                                    </span>
                                @elseif($student->final_status === 'rejected')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-red-100 text-red-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        TIDAK LULUS
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Belum ada peserta yang diterima di Tahfiz.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: BAHASA --}}
        <div id="content-language" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Spesialisasi Bahasa</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Peserta yang diterima melalui seleksi SAW</p>
                    </div>
                    <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-full text-xs font-semibold shadow-sm">
                        {{ count($languageStudents) }} Peserta
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Registrasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil Seleksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($languageStudents as $i => $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 text-center text-sm text-gray-400 font-mono">{{ $i + 1 }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700">{{ $student->student_id ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700 search-nisn">{{ $student->nisn ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-xs text-white flex-shrink-0 bg-gradient-to-br from-blue-400 to-blue-600">
                                        {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 search-name">{{ $student->full_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                @if($student->final_status === 'accepted')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        LULUS
                                    </span>
                                @elseif($student->final_status === 'rejected')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-red-100 text-red-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        TIDAK LULUS
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Belum ada peserta yang diterima di Bahasa.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: REGULAR --}}
        <div id="content-regular" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">Spesialisasi Regular</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Peserta yang diterima melalui FCFS (urutan daftar)</p>
                    </div>
                    <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-full text-xs font-semibold shadow-sm">
                        {{ count($regularStudents) }} Peserta
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Registrasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil Seleksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($regularStudents as $i => $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 text-center text-sm text-gray-400 font-mono">{{ $i + 1 }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700">{{ $student->student_id ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-700 search-nisn">{{ $student->nisn ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-xs text-white flex-shrink-0 bg-gradient-to-br from-violet-400 to-violet-600">
                                        {{ strtoupper(substr($student->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 search-name">{{ $student->full_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                @if($student->final_status === 'accepted')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        LULUS
                                    </span>
                                @elseif($student->final_status === 'rejected')
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-red-100 text-red-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        TIDAK LULUS
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Belum ada peserta yang diterima di Regular.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- KETERANGAN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Keterangan</p>
        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-2">
                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">Tahfiz</span>
                <span class="text-gray-500">Diterima via seleksi SAW</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">Bahasa</span>
                <span class="text-gray-500">Diterima via seleksi SAW</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-medium bg-violet-100 text-violet-800">Regular</span>
                <span class="text-gray-500">Diterima via FCFS (urutan daftar)</span>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const pdfRoutes = {
    all:      '{{ route('committee.selection-results.export-pdf', ['tab' => 'all']) }}',
    tahfiz:   '{{ route('committee.selection-results.export-pdf', ['tab' => 'tahfiz']) }}',
    language: '{{ route('committee.selection-results.export-pdf', ['tab' => 'language']) }}',
    regular:  '{{ route('committee.selection-results.export-pdf', ['tab' => 'regular']) }}',
};

const tabColors = {
    all:      { border: 'border-indigo-500',  text: 'text-indigo-600',  badge: 'bg-indigo-100 text-indigo-700'  },
    tahfiz:   { border: 'border-emerald-500', text: 'text-emerald-600', badge: 'bg-emerald-100 text-emerald-700'},
    language: { border: 'border-blue-500',    text: 'text-blue-600',    badge: 'bg-blue-100 text-blue-700'      },
    regular:  { border: 'border-violet-500',  text: 'text-violet-600',  badge: 'bg-violet-100 text-violet-700'  },
};

let activeTab = 'all';

function showTab(name) {
    activeTab = name;

    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

    document.querySelectorAll('.tab-btn').forEach(btn => {
        const key   = btn.id.replace('tab-', '');
        const c     = tabColors[key];
        btn.classList.remove(c.border, c.text);
        btn.classList.add('border-transparent', 'text-gray-500');

        const badge = document.getElementById('badge-' + key);
        if (badge) {
            badge.classList.remove(...c.badge.split(' '));
            badge.classList.add('bg-gray-100', 'text-gray-500');
        }
    });

    document.getElementById('content-' + name).classList.remove('hidden');

    const activeBtn   = document.getElementById('tab-' + name);
    const activeColor = tabColors[name];
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add(activeColor.border, activeColor.text);

    const activeBadge = document.getElementById('badge-' + name);
    if (activeBadge) {
        activeBadge.classList.remove('bg-gray-100', 'text-gray-500');
        activeColor.badge.split(' ').forEach(cls => activeBadge.classList.add(cls));
    }

    document.getElementById('export-active-tab-link').href = pdfRoutes[name] ?? pdfRoutes['all'];
    document.getElementById('search-input').value = '';
    document.getElementById('result-count').textContent = '';
}

function filterTable() {
    const q       = document.getElementById('search-input').value.toLowerCase().trim();
    const content = document.getElementById('content-' + activeTab);
    const rows    = content.querySelectorAll('tbody tr');
    let   visible = 0;

    rows.forEach(row => {
        const name  = row.querySelector('.search-name')?.textContent.toLowerCase() ?? '';
        const nisn  = row.querySelector('.search-nisn')?.textContent.toLowerCase() ?? '';
        const match = !q || name.includes(q) || nisn.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('result-count').textContent = q ? `${visible} hasil ditemukan` : '';
}

document.addEventListener('DOMContentLoaded', function () {
    showTab('all');

    const btn      = document.getElementById('export-dropdown-btn');
    const dropdown = document.getElementById('export-dropdown');
    const chevron  = document.getElementById('export-chevron');

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-180', isHidden);
    });

    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
            dropdown.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    });
});
</script>
@endpush