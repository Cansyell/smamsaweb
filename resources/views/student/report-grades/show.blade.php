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
        <div class="space-y-4">
            @php
                $subjects = [
                    ['key' => 'islamic_studies',    'label' => 'Pendidikan Agama Islam', 'from' => 'green-50',  'to' => 'emerald-50',  'border' => 'green-200',  'text' => 'green-600',  'bar_from' => 'green-500',  'bar_to' => 'emerald-600'],
                    ['key' => 'indonesian_language', 'label' => 'Bahasa Indonesia',       'from' => 'blue-50',   'to' => 'indigo-50',   'border' => 'blue-200',   'text' => 'blue-600',   'bar_from' => 'blue-500',   'bar_to' => 'indigo-600'],
                    ['key' => 'english_language',   'label' => 'Bahasa Inggris',         'from' => 'purple-50', 'to' => 'pink-50',     'border' => 'purple-200', 'text' => 'purple-600', 'bar_from' => 'purple-500', 'bar_to' => 'pink-600'],
                    ['key' => 'ppkn',               'label' => 'PPKn',                   'from' => 'yellow-50', 'to' => 'amber-50',    'border' => 'yellow-200', 'text' => 'yellow-600', 'bar_from' => 'yellow-500', 'bar_to' => 'amber-600'],
                    ['key' => 'mtk',                'label' => 'Matematika',             'from' => 'red-50',    'to' => 'rose-50',     'border' => 'red-200',    'text' => 'red-600',    'bar_from' => 'red-500',    'bar_to' => 'rose-600'],
                    ['key' => 'ipa',                'label' => 'IPA',                    'from' => 'teal-50',   'to' => 'cyan-50',     'border' => 'teal-200',   'text' => 'teal-600',   'bar_from' => 'teal-500',   'bar_to' => 'cyan-600'],
                    ['key' => 'seni_budaya',        'label' => 'Seni Budaya',            'from' => 'pink-50',   'to' => 'fuchsia-50',  'border' => 'pink-200',   'text' => 'pink-600',   'bar_from' => 'pink-500',   'bar_to' => 'fuchsia-600'],
                    ['key' => 'penjas',             'label' => 'Pendidikan Jasmani',     'from' => 'orange-50', 'to' => 'amber-50',    'border' => 'orange-200', 'text' => 'orange-600', 'bar_from' => 'orange-500', 'bar_to' => 'amber-600'],
                    ['key' => 'prakarya',           'label' => 'Prakarya',               'from' => 'indigo-50', 'to' => 'violet-50',   'border' => 'indigo-200', 'text' => 'indigo-600', 'bar_from' => 'indigo-500', 'bar_to' => 'violet-600'],
                ];
            @endphp

            @foreach($subjects as $s)
                @if($reportGrade->{$s['key']} !== null)
                <div class="bg-gradient-to-r from-{{ $s['from'] }} to-{{ $s['to'] }} rounded-lg p-6 border border-{{ $s['border'] }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/60 rounded-full">
                                <svg class="w-8 h-8 text-{{ $s['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">{{ $s['label'] }}</h4>
                                <p class="text-sm text-gray-600">Rata-rata Semester 1-5</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-bold text-{{ $s['text'] }}">{{ number_format($reportGrade->{$s['key']}, 2) }}</p>
                            <p class="text-sm text-gray-600">/100</p>
                        </div>
                    </div>
                    <div class="w-full bg-white/50 rounded-full h-3">
                        <div class="bg-gradient-to-r from-{{ $s['bar_from'] }} to-{{ $s['bar_to'] }} h-3 rounded-full transition-all duration-500"
                            style="width: {{ $reportGrade->{$s['key']} }}%"></div>
                    </div>
                </div>
                @endif
            @endforeach
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