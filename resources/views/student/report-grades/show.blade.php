@extends('layouts.app')

@section('title', 'Detail Nilai Raport')

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

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Detail Nilai Raport</h2>
                    <p class="text-blue-100">Informasi lengkap nilai raport siswa</p>
                </div>
                {!! $reportGrade->grade_badge !!}
            </div>
            
            <!-- Average Score Display -->
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 mt-6">
                <div class="text-center">
                    <p class="text-blue-100 text-sm uppercase tracking-wide mb-2">Nilai Rata-rata</p>
                    <p class="text-6xl font-bold">{{ number_format($reportGrade->average_grade, 2) }}</p>
                    <p class="text-blue-100 mt-2">dari 100</p>
                </div>
            </div>
        </div>

        <!-- Student Info -->
        <div class="px-6 py-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Siswa</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                    <p class="font-semibold text-gray-800">{{ $student->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">NISN</p>
                    <p class="font-semibold text-gray-800">{{ $student->nisn }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">ID Siswa</p>
                    <p class="font-semibold text-gray-800">{{ $student->student_id }}</p>
                </div>
            </div>
        </div>

        <!-- Grades Detail -->
        <div class="px-6 py-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Detail Nilai Mata Pelajaran</h3>
            
            <div class="space-y-6">
                <!-- Pendidikan Agama Islam -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">Pendidikan Agama Islam</h4>
                                <p class="text-sm text-gray-600">Rata-rata Semester 1-5</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-bold text-green-600">{{ number_format($reportGrade->islamic_studies, 2) }}</p>
                            <p class="text-sm text-gray-600">/100</p>
                        </div>
                    </div>
                    <div class="w-full bg-green-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $reportGrade->islamic_studies }}%"></div>
                    </div>
                </div>

                <!-- Bahasa Indonesia -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">Bahasa Indonesia</h4>
                                <p class="text-sm text-gray-600">Rata-rata Semester 1-5</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-bold text-blue-600">{{ number_format($reportGrade->indonesian_language, 2) }}</p>
                            <p class="text-sm text-gray-600">/100</p>
                        </div>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $reportGrade->indonesian_language }}%"></div>
                    </div>
                </div>

                <!-- Bahasa Inggris -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-6 border border-purple-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">Bahasa Inggris</h4>
                                <p class="text-sm text-gray-600">Rata-rata Semester 1-5</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-bold text-purple-600">{{ number_format($reportGrade->english_language, 2) }}</p>
                            <p class="text-sm text-gray-600">/100</p>
                        </div>
                    </div>
                    <div class="w-full bg-purple-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $reportGrade->english_language }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <div>
                    <span class="font-semibold">Dibuat:</span> 
                    {{ $reportGrade->created_at->format('d F Y, H:i') }} WIB
                </div>
                <div>
                    <span class="font-semibold">Terakhir Diperbarui:</span> 
                    {{ $reportGrade->updated_at->format('d F Y, H:i') }} WIB
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            <div class="flex items-center justify-between">
                <a href="{{ route('student.report-grades.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('student.report-grades.edit', $reportGrade) }}" 
                       class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Nilai
                    </a>
                    <button onclick="window.print()" 
                            class="px-6 py-2 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white;
        }
        
        .shadow-md {
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection