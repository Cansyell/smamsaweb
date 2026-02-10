@extends('layouts.app')

@section('title', 'Hasil Perhitungan SAW')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hasil Perhitungan SAW & Status Kelulusan</h2>
                <p class="text-gray-600 mt-1">Data kelulusan dan perankingan siswa berdasarkan spesialisasi</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('committee.criterion-values.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                
                @if($allStudents->isNotEmpty())
                <form action="{{ route('committee.criterion-values.determine-acceptance') }}" method="POST" id="acceptanceForm">
                    @csrf
                    <button type="button" 
                            onclick="confirmDetermineAcceptance()"
                            class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tentukan Status Penerimaan
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
                <p class="mt-1 text-sm text-green-700 whitespace-pre-line">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Info -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-2">Informasi Perankingan:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Tab Global:</strong> Menampilkan semua siswa dengan status kelulusan (tidak ada ranking global)</li>
                    <li><strong>Tab Tahfiz & Bahasa:</strong> Ranking berdasarkan SAW (nilai rapor & test)</li>
                    <li><strong>Tab Regular:</strong> Ranking berdasarkan waktu pendaftaran (FCFS)</li>
                    <li><strong>Status Lulus:</strong> Ditampilkan dengan ranking di program yang diikuti</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Siswa</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Semua jalur</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Lulus</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['total_passed'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">siswa</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Jalur Tahfiz</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['tahfiz_choice'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">siswa</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Jalur Bahasa</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['language_choice'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">siswa</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Jalur Regular</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['regular_choice'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">siswa</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button onclick="showTab('global')" 
                        id="tab-global"
                        class="tab-button w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        data-tab="global">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Data Global
                </button>
                <button onclick="showTab('tahfiz')" 
                        id="tab-tahfiz"
                        class="tab-button w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        data-tab="tahfiz">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Ranking Tahfiz
                </button>
                <button onclick="showTab('language')" 
                        id="tab-language"
                        class="tab-button w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        data-tab="language">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    Ranking Bahasa
                </button>
                <button onclick="showTab('regular')" 
                        id="tab-regular"
                        class="tab-button w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        data-tab="regular">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ranking Regular
                </button>
            </nav>
        </div>

        <!-- Tab Content: Global (All Students) -->
        <div id="content-global" class="tab-content">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Data Global - Semua Siswa</h3>
                        <p class="text-sm text-gray-600 mt-1">Menampilkan semua siswa dengan status kelulusan dan ranking per jalur</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Filter Status -->
                        <select id="filterStatus" onchange="filterTable()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="all">Semua Status</option>
                            <option value="passed">Lulus</option>
                            <option value="failed">Tidak Lulus</option>
                        </select>
                        
                        <!-- Filter Program -->
                        <select id="filterProgram" onchange="filterTable()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="all">Semua Program</option>
                            <option value="tahfiz">Tahfiz</option>
                            <option value="language">Bahasa</option>
                            <option value="regular">Regular</option>
                        </select>

                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
                            {{ $allStudents->count() }} Siswa
                        </span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="globalTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                                NISN ↕
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                Nama Siswa ↕
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Program
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Kelulusan
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ranking Tahfiz
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ranking Bahasa
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(6)">
                                Tanggal Lulus ↕
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allStudents as $data)
                        <tr class="hover:bg-gray-50 transition-colors" 
                            data-status="{{ $data['final_status'] }}" 
                            data-program="{{ $data['student']->specialization }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 font-mono">{{ $data['student']->nisn }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full {{ $data['avatar_color'] }} flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($data['student']->full_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $data['student']->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $data['student']->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $data['program_badge_color'] }}">
                                    {!! $data['program_icon'] !!}
                                    {{ $data['program_label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($data['final_status'] === 'accepted')
                                    <span class="px-4 py-2 inline-flex items-center text-sm font-bold rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        LULUS
                                    </span>
                                @elseif($data['final_status'] === 'rejected')
                                    <span class="px-4 py-2 inline-flex items-center text-sm font-bold rounded-full bg-gradient-to-r from-red-500 to-rose-500 text-white shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        TIDAK LULUS
                                    </span>
                                @else
                                    <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-full bg-gray-100 text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($data['tahfiz_rank'] && $data['final_status'] === 'accepted')
                                    <div class="flex flex-col items-center">
                                        @if($data['tahfiz_rank'] == 1)
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-lg">
                                                🥇 #1
                                            </span>
                                        @elseif($data['tahfiz_rank'] == 2)
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-gray-300 to-gray-400 text-white shadow-lg">
                                                🥈 #2
                                            </span>
                                        @elseif($data['tahfiz_rank'] == 3)
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-orange-400 to-orange-500 text-white shadow-lg">
                                                🥉 #3
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                                #{{ $data['tahfiz_rank'] }}
                                            </span>
                                        @endif
                                        @if($data['tahfiz_score'])
                                            <span class="text-xs text-gray-500 mt-1">{{ number_format($data['tahfiz_score'], 4) }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($data['language_rank'] && $data['final_status'] === 'accepted')
                                    <div class="flex flex-col items-center">
                                        @if($data['language_rank'] == 1)
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-lg">
                                                🥇 #1
                                            </span>
                                        @elseif($data['language_rank'] == 2)
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-gray-300 to-gray-400 text-white shadow-lg">
                                                🥈 #2
                                            </span>
                                        @elseif($data['language_rank'] == 3)
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-orange-400 to-orange-500 text-white shadow-lg">
                                                🥉 #3
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                                #{{ $data['language_rank'] }}
                                            </span>
                                        @endif
                                        @if($data['language_score'])
                                            <span class="text-xs text-gray-500 mt-1">{{ number_format($data['language_score'], 4) }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center" data-date="{{ $data['validated_at'] ?? '' }}">
                                @if($data['validated_at'])
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($data['validated_at'])->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($data['validated_at'])->format('H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($data['student']->specialization === 'regular')
                                    <a href="{{ route('committee.saw-results.show', $data['student']) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                @else
                                    <a href="{{ route('committee.saw-results.show', $data['student']) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 font-semibold">Belum ada data siswa</p>
                                <p class="text-sm text-gray-400 mt-1">Tidak ada siswa yang terdaftar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content: Tahfiz -->
        <div id="content-tahfiz" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Ranking Jalur Tahfiz</h3>
                        <p class="text-sm text-gray-600 mt-1">Berdasarkan perhitungan SAW (Nilai Rapor & Test)</p>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                        {{ $tahfizRanking->count() }} Siswa
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SAW Score</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tahfizRanking as $data)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($data['rank'] == 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-lg">
                                        🥇 #1
                                    </span>
                                @elseif($data['rank'] == 2)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-gray-300 to-gray-400 text-white shadow-lg">
                                        🥈 #2
                                    </span>
                                @elseif($data['rank'] == 3)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-orange-400 to-orange-500 text-white shadow-lg">
                                        🥉 #3
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                        #{{ $data['rank'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 font-mono">{{ $data['student']->nisn }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($data['student']->full_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $data['student']->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $data['student']->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold text-green-600">{{ number_format($data['score'], 4) }}</span>
                                    <span class="text-xs text-gray-500">Final Score</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('committee.saw-results.show', $data['student']) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 font-semibold">Belum ada siswa jalur Tahfiz</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content: Language -->
        <div id="content-language" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-cyan-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Ranking Jalur Bahasa</h3>
                        <p class="text-sm text-gray-600 mt-1">Berdasarkan perhitungan SAW (Nilai Rapor & Test)</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                        {{ $languageRanking->count() }} Siswa
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SAW Score</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($languageRanking as $data)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($data['rank'] == 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-lg">
                                        🥇 #1
                                    </span>
                                @elseif($data['rank'] == 2)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-gray-300 to-gray-400 text-white shadow-lg">
                                        🥈 #2
                                    </span>
                                @elseif($data['rank'] == 3)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-orange-400 to-orange-500 text-white shadow-lg">
                                        🥉 #3
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                        #{{ $data['rank'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 font-mono">{{ $data['student']->nisn }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($data['student']->full_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $data['student']->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $data['student']->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold text-blue-600">{{ number_format($data['score'], 4) }}</span>
                                    <span class="text-xs text-gray-500">Final Score</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('committee.saw-results.show', $data['student']) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 font-semibold">Belum ada siswa jalur Bahasa</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content: Regular -->
        <div id="content-regular" class="tab-content hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Ranking Jalur Regular</h3>
                        <p class="text-sm text-gray-600 mt-1">Berdasarkan waktu pendaftaran (FCFS - First Come First Serve)</p>
                    </div>
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
                        {{ $regularRanking->count() }} Siswa
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pendaftaran</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($regularRanking as $index => $student)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($index + 1 == 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-lg">
                                        🥇 #1
                                    </span>
                                @elseif($index + 1 == 2)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-gray-300 to-gray-400 text-white shadow-lg">
                                        🥈 #2
                                    </span>
                                @elseif($index + 1 == 3)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-orange-400 to-orange-500 text-white shadow-lg">
                                        🥉 #3
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm font-bold bg-purple-100 text-purple-800">
                                        #{{ $index + 1 }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 font-mono">{{ $student->nisn }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($student->full_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-semibold text-purple-600">
                                        {{ $student->created_at->format('d M Y') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $student->created_at->format('H:i:s') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('committee.saw-results.show', $student) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 font-semibold">Belum ada siswa jalur Regular</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Keterangan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="font-semibold text-gray-700 mb-3">Status Kelulusan:</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            LULUS
                        </span>
                        <span class="text-gray-600">Siswa diterima</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-full bg-gradient-to-r from-red-500 to-rose-500 text-white">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            TIDAK LULUS
                        </span>
                        <span class="text-gray-600">Siswa tidak diterima</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Menunggu
                        </span>
                        <span class="text-gray-600">Belum ditentukan</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="font-semibold text-gray-700 mb-3">Ranking per Jalur:</p>
                <ul class="space-y-1 text-gray-600">
                    <li>• <strong>Ranking Tahfiz:</strong> Hanya ditampilkan jika siswa LULUS dan mengikuti Tahfiz</li>
                    <li>• <strong>Ranking Bahasa:</strong> Hanya ditampilkan jika siswa LULUS dan mengikuti Bahasa</li>
                    <li>• <strong>Tanda "-":</strong> Siswa tidak lulus atau tidak mengikuti jalur tersebut</li>
                    <li>• <strong>Tab Global:</strong> Tidak ada ranking global, hanya menampilkan data lengkap semua siswa</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab switching functionality
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-indigo-500', 'text-indigo-600', 'border-green-500', 'text-green-600', 
                                  'border-blue-500', 'text-blue-600', 'border-purple-500', 'text-purple-600');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    
    switch(tabName) {
        case 'global':
            activeButton.classList.add('border-indigo-500', 'text-indigo-600');
            break;
        case 'tahfiz':
            activeButton.classList.add('border-green-500', 'text-green-600');
            break;
        case 'language':
            activeButton.classList.add('border-blue-500', 'text-blue-600');
            break;
        case 'regular':
            activeButton.classList.add('border-purple-500', 'text-purple-600');
            break;
    }
}

// Filter table functionality
function filterTable() {
    const statusFilter = document.getElementById('filterStatus').value;
    const programFilter = document.getElementById('filterProgram').value;
    const table = document.getElementById('globalTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        if (row.cells.length < 2) continue; // Skip empty state row
        
        const status = row.getAttribute('data-status');
        const program = row.getAttribute('data-program');
        
        let showRow = true;
        
        // Filter by status
        if (statusFilter !== 'all') {
            if (statusFilter === 'passed' && status !== 'accepted') showRow = false;
            if (statusFilter === 'failed' && status !== 'rejected') showRow = false;
        }
        
        // Filter by program
        if (programFilter !== 'all' && program !== programFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    }
}

// Sort table functionality
let sortDirection = {};
function sortTable(columnIndex) {
    const table = document.getElementById('globalTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr')).filter(row => row.cells.length > 1);
    
    if (!sortDirection[columnIndex]) sortDirection[columnIndex] = 'asc';
    else sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
    
    rows.sort((a, b) => {
        let aValue = a.cells[columnIndex].textContent.trim();
        let bValue = b.cells[columnIndex].textContent.trim();
        
        // Handle date sorting
        if (columnIndex === 6) {
            aValue = a.cells[columnIndex].getAttribute('data-date') || '';
            bValue = b.cells[columnIndex].getAttribute('data-date') || '';
        }
        
        if (sortDirection[columnIndex] === 'asc') {
            return aValue.localeCompare(bValue);
        } else {
            return bValue.localeCompare(aValue);
        }
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

// Initialize first tab on page load
document.addEventListener('DOMContentLoaded', function() {
    showTab('global');
});

function confirmDetermineAcceptance() {
    const totalStudents = {{ $stats['total_students'] }};
    const tahfizChoice = {{ $stats['tahfiz_choice'] }};
    const languageChoice = {{ $stats['language_choice'] }};
    const regularChoice = {{ $stats['regular_choice'] }};
    
    const message = `Apakah Anda yakin ingin menentukan status penerimaan?

Total siswa yang akan diproses: ${totalStudents}
• Jalur Tahfiz: ${tahfizChoice} siswa (SAW)
• Jalur Bahasa: ${languageChoice} siswa (SAW)
• Jalur Regular: ${regularChoice} siswa (FCFS)

Proses ini akan menentukan status LULUS/TIDAK LULUS untuk semua siswa.

Lanjutkan?`;
    
    if (confirm(message)) {
        const form = document.getElementById('acceptanceForm');
        const button = form.querySelector('button');
        
        button.disabled = true;
        button.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        
        form.submit();
    }
}
</script>
@endpush
@endsection