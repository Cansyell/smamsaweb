@extends('layouts.app')

@section('title', 'Hasil Ranking - ' . ucfirst($filterSpecialization))

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

    <!-- My Ranking Card -->
    @if($myRanking)
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-xl font-semibold mb-3">Peringkat Anda</h2>
                <div class="flex items-baseline space-x-3 mb-4">
                    <span class="text-5xl font-bold">{{ $myRanking['rank'] }}</span>
                    <span class="text-xl opacity-90">dari {{ $myRanking['total_students'] }} siswa</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                        Peminatan: {{ ucfirst($student->specialization) }}
                    </span>
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                        Skor: {{ number_format($myRanking['final_score'], 4) }}
                    </span>
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
                    <span class="text-sm font-semibold">TIDAK DITERIMA</span>
                @endif
            </div>
        </div>
        
        <div class="mt-6 flex gap-3">
            <a href="{{ route('student.result.show') }}" 
               class="px-6 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
                Lihat Detail Perhitungan
            </a>
            <a href="{{ route('student.result.card') }}" 
               target="_blank" 
               class="px-6 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition font-medium">
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
                <p class="font-semibold text-yellow-700 mb-1">Hasil ranking belum tersedia</p>
                <p class="text-sm text-yellow-600">Admin belum melakukan perhitungan ranking. Mohon tunggu pengumuman lebih lanjut.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
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

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
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

        <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
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

        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4">
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
    </div>

    <!-- Ranking Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Ranking</h2>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('student.result.index', ['specialization' => 'tahfiz']) }}" 
                   class="px-4 py-2 rounded-lg {{ $filterSpecialization == 'tahfiz' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Tahfiz
                </a>
                <a href="{{ route('student.result.index', ['specialization' => 'language']) }}" 
                   class="px-4 py-2 rounded-lg {{ $filterSpecialization == 'language' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Bahasa
                </a>
                <a href="{{ route('student.result.index', ['specialization' => 'regular']) }}" 
                   class="px-4 py-2 rounded-lg {{ $filterSpecialization == 'regular' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Reguler
                </a>
            </div>
        </div>

        <!-- Ranking List -->
        @if($rankings->count() > 0)
        <div class="space-y-4">
            @foreach($rankings as $ranking)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition {{ $ranking->student_id == $student->id ? 'bg-blue-50 border-blue-300' : '' }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 flex-1">
                        <!-- Rank Badge -->
                        <div class="flex-shrink-0">
                            @if($ranking->rank <= 3)
                                <div class="flex items-center justify-center w-12 h-12 rounded-full {{ $ranking->rank == 1 ? 'bg-yellow-400' : ($ranking->rank == 2 ? 'bg-gray-300' : 'bg-orange-400') }} text-white font-bold text-lg">
                                    {{ $ranking->rank }}
                                </div>
                            @else
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-700 font-bold text-lg">
                                    {{ $ranking->rank }}
                                </div>
                            @endif
                        </div>

                        <!-- Student Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-base font-semibold text-gray-900">
                                    {{ $ranking->student->full_name }}
                                </h3>
                                @if($ranking->student_id == $student->id)
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        Anda
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    {{ $ranking->student->nisn }}
                                </span>
                                <span class="flex items-center truncate">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $ranking->student->previous_school }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Score and Status -->
                    <div class="text-right ml-4">
                        <div class="text-lg font-bold text-gray-900 mb-2">
                            {{ number_format($ranking->final_score, 4) }}
                        </div>
                        @if($ranking->rank <= $quotaInfo[$filterSpecialization]['quota'])
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                Diterima
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                Tidak Diterima
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $rankings->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data Ranking</h3>
            <p class="text-gray-600">Belum ada data ranking untuk peminatan ini</p>
        </div>
        @endif
    </div>
</div>
@endsection