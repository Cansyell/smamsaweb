@extends('layouts.app')

@section('title', 'Nilai Raport')

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

    <!-- Report Grade Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Nilai Raport</h2>
            @if(!$reportGrade)
            <a href="{{ route('student.report-grades.create') }}" 
               class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Nilai
            </a>
            @endif
        </div>

        @if($reportGrade)
        <!-- Nilai Sudah Ada -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Status Nilai</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-3xl font-bold text-indigo-600">{{ number_format($reportGrade->average_grade, 2) }}</span>
                            {!! $reportGrade->grade_badge !!}
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Diperbarui</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $reportGrade->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Nilai -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Pendidikan Agama Islam</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($reportGrade->islamic_studies, 2) }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $reportGrade->islamic_studies }}%"></div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bahasa Indonesia</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($reportGrade->indonesian_language, 2) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $reportGrade->indonesian_language }}%"></div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Bahasa Inggris</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($reportGrade->english_language, 2) }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $reportGrade->english_language }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                <a href="{{ route('student.dashboard') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('student.report-grades.show', $reportGrade) }}" 
                       class="px-6 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail
                    </a>
                    <a href="{{ route('student.report-grades.edit', $reportGrade) }}" 
                       class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Nilai
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Nilai Belum Ada -->
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data Nilai</h3>
            <p class="text-gray-600 mb-6">Silakan input nilai raport semester 1-5 dari SMP/MTs Anda</p>
            <a href="{{ route('student.report-grades.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Nilai Sekarang
            </a>
        </div>
        @endif
    </div>
</div>
@endsection