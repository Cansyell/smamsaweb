@extends('layouts.app')

@section('title', 'Detail Nilai Kriteria - ' . $student->full_name)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('committee.criterion-values.index', ['specialization' => $student->specialization]) }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Siswa
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Detail Nilai Kriteria</h2>
                <p class="text-gray-600">Lihat rincian nilai kriteria siswa</p>
            </div>
            <a href="{{ route('committee.criterion-values.create', $student) }}" 
               class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Nilai
            </a>
        </div>
    </div>

    <!-- Student Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Siswa</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                <p class="font-semibold text-gray-800">{{ $student->full_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">NISN</p>
                <p class="font-semibold text-gray-800">{{ $student->nisn }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Spesialisasi</p>
                <p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $student->specialization === 'tahfiz' ? 'bg-green-100 text-green-800' : ($student->specialization === 'language' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $student->specialization_label }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Status Kelengkapan</p>
                <p>
                    @if($values->count() === $criterias->count())
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Lengkap
                    </span>
                    @else
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Belum Lengkap ({{ $values->count() }}/{{ $criterias->count() }})
                    </span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Criteria Values -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Nilai Kriteria</h3>
        </div>

        <div class="divide-y divide-gray-200">
            @foreach($criterias as $index => $criteria)
            @php
                $value = $values->get($criteria->id);
            @endphp
            <div class="p-6 hover:bg-gray-50 transition {{ $value ? '' : 'bg-yellow-50' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-semibold">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $criteria->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $criteria->code }}</p>
                            </div>
                            <div class="text-right">
                                @if($value)
                                <div class="text-3xl font-bold text-indigo-600">
                                    {{ number_format($value->raw_value, 2) }}
                                </div>
                                <div class="text-sm text-gray-500">/ 100</div>
                                @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Belum Diisi
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="ml-13 space-y-2">
                            @if($criteria->description)
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-600">{{ $criteria->description }}</p>
                            </div>
                            @endif

                            <div class="flex items-center gap-4 flex-wrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $criteria->attribute_type === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($criteria->attribute_type === 'benefit')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        @endif
                                    </svg>
                                    {{ $criteria->attribute_type === 'benefit' ? 'Benefit' : 'Cost' }}
                                </span>

                                @if($criteria->data_source)
                                <span class="text-xs text-gray-500">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Sumber: {{ $criteria->data_source }}
                                </span>
                                @endif

                                @if($value && $value->normalized_value)
                                <span class="text-xs text-gray-500">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Nilai Ternormalisasi: {{ number_format($value->normalized_value, 4) }}
                                </span>
                                @endif
                            </div>

                            @if($value && $value->notes)
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-semibold text-blue-800 mb-1">Catatan:</p>
                                        <p class="text-sm text-blue-700">{{ $value->notes }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Statistics -->
    @if($values->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Nilai</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Rata-rata Nilai</p>
                <p class="text-3xl font-bold text-blue-600">
                    {{ number_format($values->avg('raw_value'), 2) }}
                </p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Nilai Tertinggi</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ number_format($values->max('raw_value'), 2) }}
                </p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Nilai Terendah</p>
                <p class="text-3xl font-bold text-yellow-600">
                    {{ number_format($values->min('raw_value'), 2) }}
                </p>
            </div>
            <div class="text-center p-4 bg-indigo-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Total Kriteria Terisi</p>
                <p class="text-3xl font-bold text-indigo-600">
                    {{ $values->count() }}/{{ $criterias->count() }}
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection