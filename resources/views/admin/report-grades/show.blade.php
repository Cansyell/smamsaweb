@extends('layouts.app')

@section('title', 'Detail Nilai Rapor – ' . $student->full_name)

@section('content')
{{-- Breadcrumb --}}
<nav class="mb-4 text-sm text-gray-500 flex items-center gap-1">
    <a href="{{ route('admin.report-grades.index') }}" class="hover:text-indigo-600 transition">Nilai Rapor</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-gray-700 font-medium">{{ $student->full_name }}</span>
</nav>

{{-- Flash --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700">✕</button>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ===== KARTU INFO SISWA ===== --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Profil Siswa --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white leading-tight">{{ $student->full_name }}</h2>
                        <p class="text-indigo-200 text-sm">{{ $student->student_id }}</p>
                    </div>
                </div>
            </div>
            <div class="p-5 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">NISN</span>
                    <span class="font-medium text-gray-800">{{ $student->nisn }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jenis Kelamin</span>
                    <span class="font-medium text-gray-800">{{ $student->gender_label }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Spesialisasi</span>
                    <span class="font-medium">
                        @if($student->specialization)
                            @php
                                $specColor = match($student->specialization) {
                                    'tahfiz'   => 'bg-purple-100 text-purple-800',
                                    'language' => 'bg-teal-100 text-teal-800',
                                    default    => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $specColor }}">
                                {{ $student->specialization_label }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Sekolah Asal</span>
                    <span class="font-medium text-gray-800 text-right max-w-[150px]">{{ $student->previous_school }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status Berkas</span>
                    <span>{!! $student->status_badge !!}</span>
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="bg-white rounded-lg shadow-md p-5 space-y-2">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Tindakan</h3>
            {{-- <a href="{{ route('admin.report-grades.edit', $student->id) }}"
               class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ $student->reportGrade ? 'Edit Nilai Rapor' : 'Input Nilai Rapor' }}
            </a> --}}
            {{-- <a href="{{ route('admin.students.show', $student->id) }}"
               class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil Lengkap Siswa
            </a> --}}
            <a href="{{ route('admin.report-grades.index') }}"
               class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    {{-- ===== DETAIL NILAI ===== --}}
    <div class="lg:col-span-2 space-y-4">

        @php $grade = $student->reportGrade; @endphp

        @if($grade)
            {{-- Ringkasan Nilai --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Ringkasan Nilai Rapor</h3>
                    {!! $grade->grade_badge !!}
                </div>

                <div class="flex items-center gap-6">
                    {{-- Donut nilai rata-rata --}}
                    <div class="relative w-24 h-24 flex-shrink-0">
                        @php
                            $avg      = (float) $grade->average_grade;
                            $pct      = min($avg, 100);
                            $dash     = 2 * 3.14159 * 36;
                            $offset   = $dash - ($pct / 100) * $dash;
                            $ringColor = $avg >= 85 ? '#16a34a' : ($avg >= 75 ? '#2563eb' : ($avg >= 65 ? '#ca8a04' : '#dc2626'));
                        @endphp
                        <svg class="w-24 h-24 -rotate-90" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="36" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                            <circle cx="40" cy="40" r="36" fill="none"
                                    stroke="{{ $ringColor }}" stroke-width="8"
                                    stroke-dasharray="{{ $dash }}"
                                    stroke-dashoffset="{{ $offset }}"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-xl font-bold text-gray-800">{{ number_format($avg, 0) }}</span>
                            <span class="text-xs text-gray-500">avg</span>
                        </div>
                    </div>
                    <div class="flex-1 grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Nilai Tertinggi</p>
                            @php
                                $vals = array_filter([
                                    (float)$grade->islamic_studies, (float)$grade->indonesian_language,
                                    (float)$grade->english_language, (float)$grade->ppkn,
                                    (float)$grade->mtk, (float)$grade->ipa,
                                    (float)$grade->seni_budaya, (float)$grade->penjas,
                                    (float)$grade->prakarya,
                                ], fn($v) => $v > 0);
                            @endphp
                            <p class="text-xl font-bold text-green-600">{{ $vals ? number_format(max($vals), 1) : '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Nilai Terendah</p>
                            <p class="text-xl font-bold text-red-500">{{ $vals ? number_format(min($vals), 1) : '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Mata Pelajaran</p>
                            <p class="text-xl font-bold text-indigo-600">{{ count($vals) }}/9</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Kelengkapan</p>
                            <p class="text-xl font-bold text-blue-600">{{ round((count($vals) / 9) * 100) }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Nilai Detail --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Per Mata Pelajaran</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grafik</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $subjects = [
                                    'islamic_studies'     => 'Pendidikan Agama Islam',
                                    'indonesian_language' => 'Bahasa Indonesia',
                                    'english_language'    => 'Bahasa Inggris',
                                    'ppkn'                => 'Pendidikan Kewarganegaraan',
                                    'mtk'                 => 'Matematika',
                                    'ipa'                 => 'Ilmu Pengetahuan Alam',
                                    'seni_budaya'         => 'Seni Budaya',
                                    'penjas'              => 'Pendidikan Jasmani',
                                    'prakarya'            => 'Prakarya & Kewirausahaan',
                                ];
                            @endphp
                            @foreach($subjects as $field => $label)
                                @php
                                    $val = $grade->$field !== null ? (float) $grade->$field : null;
                                    $color = $val === null ? 'text-gray-400'
                                           : ($val >= 85 ? 'text-green-700 font-semibold'
                                           : ($val >= 75 ? 'text-blue-700'
                                           : ($val >= 65 ? 'text-yellow-700'
                                           : 'text-red-700')));
                                    $barColor = $val === null ? 'bg-gray-200'
                                              : ($val >= 85 ? 'bg-green-500'
                                              : ($val >= 75 ? 'bg-blue-500'
                                              : ($val >= 65 ? 'bg-yellow-500'
                                              : 'bg-red-500')));
                                    $predicate = $val === null ? '-'
                                               : ($val >= 85 ? 'A' : ($val >= 75 ? 'B' : ($val >= 65 ? 'C' : 'D')));
                                    $predBadge = $val === null ? 'bg-gray-100 text-gray-400'
                                               : ($val >= 85 ? 'bg-green-100 text-green-800'
                                               : ($val >= 75 ? 'bg-blue-100 text-blue-800'
                                               : ($val >= 65 ? 'bg-yellow-100 text-yellow-800'
                                               : 'bg-red-100 text-red-800')));
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-700">{{ $label }}</td>
                                    <td class="px-6 py-3 text-center {{ $color }}">
                                        {{ $val !== null ? number_format($val, 1) : '—' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="{{ $barColor }} h-2 rounded-full transition-all"
                                                 style="width: {{ $val ? min($val, 100) : 0 }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $predBadge }}">
                                            {{ $predicate }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Row rata-rata --}}
                            <tr class="bg-indigo-50 font-semibold">
                                <td class="px-6 py-3 text-indigo-800">Rata-rata Keseluruhan</td>
                                <td class="px-6 py-3 text-center text-indigo-700 font-bold text-base">
                                    {{ number_format((float)$grade->average_grade, 2) }}
                                </td>
                                <td class="px-6 py-3">
                                    <div class="w-full bg-indigo-100 rounded-full h-2">
                                        <div class="bg-indigo-500 h-2 rounded-full"
                                             style="width: {{ min((float)$grade->average_grade, 100) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    {!! $grade->grade_badge !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            {{-- Belum ada nilai --}}
            <div class="bg-white rounded-lg shadow-md flex flex-col items-center justify-center py-16 px-6 text-center">
                <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Data Nilai Belum Tersedia</h3>
                <p class="text-gray-400 mb-6">Siswa ini belum memiliki data nilai rapor yang tersimpan.</p>
                {{-- <a href="{{ route('admin.report-grades.edit', $student->id) }}"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Input Nilai Rapor Sekarang
                </a> --}}
            </div>
        @endif
    </div>
</div>
@endsection