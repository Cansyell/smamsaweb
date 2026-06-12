@extends('layouts.app')

@section('title', 'Hasil Ranking - Semua Peminatan')

@section('content')
<div class="space-y-6">
    <!-- Progress Bar Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Progress Pendaftaran</h3>
            <span class="text-2xl font-bold text-indigo-600">{{ number_format($progress['percentage'], 0) }}%</span>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500" 
                 style="width: {{ $progress['percentage'] }}%"></div>
        </div>
        
        <p class="text-sm text-gray-600">
            {{ $progress['completed'] }} dari {{ $progress['total'] }} langkah telah diselesaikan
        </p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-yellow-700">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-blue-700">{{ session('info') }}</p>
        </div>
    </div>
    @endif

    <!-- My Ranking/Position Card -->
    @if($myRanking)
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-xl font-semibold mb-3">
                    {{ $student->specialization === 'regular' ? 'Posisi Anda (FCFS)' : 'Peringkat Anda' }}
                </h2>
                <div class="flex items-baseline space-x-3 mb-4">
                    <span class="text-5xl font-bold">{{ $myRanking['rank'] }}</span>
                    <span class="text-xl opacity-90">dari {{ $myRanking['total_students'] }} siswa</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                        Pilihan: {{ ucfirst($student->specialization) }}
                    </span>
                    @if($student->specialization !== 'regular')
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                            Skor: {{ number_format($myRanking['final_score'], 4) }}
                        </span>
                    @else
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                            Sistem: FCFS
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-center">
                @if($myRanking['is_accepted'])
                    <div class="bg-green-500 w-24 h-24 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold">DITERIMA</span>
                @else
                    <div class="bg-red-500 w-24 h-24 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold">{{ $student->final_status === 'waiting_list' ? 'DAFTAR TUNGGU' : 'TIDAK DITERIMA' }}</span>
                @endif
            </div>
        </div>
        
        <div class="mt-6 flex gap-3">
            @if($student->specialization !== 'regular')
                <a href="{{ route('student.result.show') }}" 
                   class="px-6 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
                    Lihat Detail Perhitungan
                </a>
            @endif
            <a href="{{ route('student.result.card') }}" 
               target="_blank" 
               class="px-6 py-2 bg-white {{ $student->specialization !== 'regular' ? 'bg-opacity-20 text-white hover:bg-opacity-30' : 'text-blue-600 hover:bg-blue-50' }} rounded-lg transition font-medium">
                Cetak Kartu Hasil
            </a>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="font-semibold text-yellow-700 mb-1">
                    {{ $student->specialization === 'regular' ? 'Status belum tersedia' : 'Hasil ranking belum tersedia' }}
                </p>
                <p class="text-sm text-yellow-600">
                    @if($student->specialization === 'regular')
                        Admin belum menetapkan status penerimaan untuk siswa regular. Mohon tunggu pengumuman lebih lanjut.
                    @else
                        Admin belum melakukan perhitungan ranking. Mohon tunggu pengumuman lebih lanjut.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('student.result.index', ['specialization' => 'tahfiz']) }}" 
                   class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition {{ $filterSpecialization === 'tahfiz' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Tahfiz</span>
                        @if($student->specialization === 'tahfiz')
                            <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Pilihan Anda</span>
                        @endif
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        {{ $quotaInfo['tahfiz']['registered'] }} pendaftar • Kuota: {{ $quotaInfo['tahfiz']['quota'] }}
                    </div>
                </a>

                <a href="{{ route('student.result.index', ['specialization' => 'language']) }}" 
                   class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition {{ $filterSpecialization === 'language' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                        <span>Language</span>
                        @if($student->specialization === 'language')
                            <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Pilihan Anda</span>
                        @endif
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        {{ $quotaInfo['language']['registered'] }} pendaftar • Kuota: {{ $quotaInfo['language']['quota'] }}
                    </div>
                </a>

                <a href="{{ route('student.result.index', ['specialization' => 'regular']) }}" 
                   class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition {{ $filterSpecialization === 'regular' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Regular</span>
                        @if($student->specialization === 'regular')
                            <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Pilihan Anda</span>
                        @endif
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        {{ $quotaInfo['regular']['registered'] }} pendaftar • Kuota: {{ $quotaInfo['regular']['quota'] }}
                    </div>
                </a>
            </nav>
        </div>

        <!-- Statistics Cards -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Pendaftar</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $statistics['total_students'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kuota Tersedia</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $quotaInfo[$filterSpecialization]['quota'] }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                @if($filterSpecialization !== 'regular')
                <div class="bg-white border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Rata-rata Skor</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($statistics['average_score'], 2) }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Skor Tertinggi</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($statistics['highest_score'], 2) }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Sistem Seleksi</p>
                            <p class="text-lg font-bold text-gray-800">FCFS</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Validasi Pertama</p>
                            <p class="text-sm font-bold text-gray-800">
                                @php
                                    $firstValidated = \App\Models\Student::where('academic_year_id', $student->academic_year_id)
                                        ->where('specialization', 'regular')
                                        ->where('validation_status', 'valid')
                                        ->orderBy('validated_at', 'asc')
                                        ->first();
                                @endphp
                                {{ $firstValidated ? $firstValidated->validated_at->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Info Banner -->
            @if($filterSpecialization === 'regular')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold mb-1">Sistem First Come First Serve (FCFS)</p>
                        <p>Urutan ditentukan berdasarkan waktu validasi berkas oleh panitia. Siswa yang berkasnya divalidasi lebih awal akan mendapat prioritas lebih tinggi.</p>
                    </div>
                </div>
            </div>
            @else
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold mb-1">Sistem Simple Additive Weighting (SAW)</p>
                        <p>Ranking ditentukan berdasarkan perhitungan kriteria dan bobot nilai menggunakan metode SAW. Semakin tinggi skor, semakin tinggi ranking.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ranking List -->
           @if($rankings && $rankings->count() > 0)
                <div class="space-y-3">
                    @if($filterSpecialization === 'regular')
                        {{-- Regular: FCFS List --}}
                        @foreach($rankings as $index => $student_item)
                            @php $pos = ($rankings->currentPage() - 1) * $rankings->perPage() + $index + 1; @endphp
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition
                                {{ $student_item->id == $student->id ? 'bg-blue-50 border-blue-400 ring-2 ring-blue-200' : '' }}">

                                <div class="flex items-start gap-3">

                                    {{-- Rank Badge --}}
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full font-bold text-base sm:text-lg
                                            {{ $pos == 1 ? 'bg-yellow-400 text-white'
                                            : ($pos == 2 ? 'bg-gray-300 text-white'
                                            : ($pos == 3 ? 'bg-orange-400 text-white'
                                            : 'bg-gray-100 text-gray-700')) }}">
                                            {{ $pos }}
                                        </div>
                                    </div>

                                    {{-- Student Info --}}
                                    <div class="flex-1 min-w-0">

                                        {{-- Nama + badge "Posisi Anda" --}}
                                        <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                            <h3 class="text-sm sm:text-base font-semibold text-gray-900 leading-snug">
                                                {{ $student_item->full_name }}
                                            </h3>
                                            @if($student_item->id == $student->id)
                                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-500 text-white rounded-full animate-pulse shrink-0">
                                                    ← Posisi Anda
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Meta: stack di mobile, row di sm+ --}}
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 text-xs sm:text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                                </svg>
                                                {{ $student_item->nisn }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $student_item->validated_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @else
                        {{-- Tahfiz/Language: SAW Ranking --}}
                        @foreach($rankings as $ranking)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition
                                {{ $ranking->student_id == $student->id ? 'bg-blue-50 border-blue-400 ring-2 ring-blue-200' : '' }}">

                                <div class="flex items-start gap-3">

                                    {{-- Rank Badge --}}
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full font-bold text-base sm:text-lg
                                            {{ $ranking->primary_rank == 1 ? 'bg-yellow-400 text-white'
                                            : ($ranking->primary_rank == 2 ? 'bg-gray-300 text-white'
                                            : ($ranking->primary_rank == 3 ? 'bg-orange-400 text-white'
                                            : 'bg-gray-100 text-gray-700')) }}">
                                            {{ $ranking->primary_rank }}
                                        </div>
                                    </div>

                                    {{-- Student Info + Score --}}
                                    <div class="flex-1 min-w-0">

                                        {{-- Row atas: nama & badge kiri, skor kanan --}}
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <div class="flex flex-wrap items-center gap-1.5 min-w-0">
                                                <h3 class="text-sm sm:text-base font-semibold text-gray-900 leading-snug">
                                                    {{ $ranking->student->full_name }}
                                                </h3>
                                                @if($ranking->student_id == $student->id)
                                                    <span class="px-2 py-0.5 text-xs font-medium bg-blue-500 text-white rounded-full animate-pulse shrink-0">
                                                        ← Peringkat Anda
                                                    </span>
                                                @endif
                                            </div>
                                            {{-- Skor: selalu di pojok kanan atas --}}
                                            <div class="shrink-0 text-right">
                                                <span class="text-sm sm:text-base font-bold text-gray-900 font-mono">
                                                    {{ number_format($ranking->final_score, 4) }}
                                                </span>
                                                <p class="text-xs text-gray-400 mt-0.5">skor SAW</p>
                                            </div>
                                        </div>

                                        {{-- Meta: stack di mobile, row di sm+ --}}
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 text-xs sm:text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                                </svg>
                                                {{ substr($ranking->student->student_id, 0, 4) . '****' . substr($ranking->student->student_id, 8) }}
                                            </span>
                                            <span class="flex items-center gap-1 min-w-0">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                <span class="truncate">{{ $ranking->student->previous_school }}</span>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $rankings->appends(['specialization' => $filterSpecialization])->links() }}
                </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data</h3>
                <p class="text-gray-600">
                    {{ $filterSpecialization === 'regular' ? 'Belum ada siswa regular yang divalidasi' : 'Belum ada data ranking untuk peminatan ini' }}
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endsection