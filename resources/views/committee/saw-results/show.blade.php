@extends('layouts.app')

@section('title', 'Detail Hasil SAW - ' . $sawResult->student->full_name)

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Hasil SAW</h2>
                <p class="text-gray-600 mt-1">Rincian perhitungan skor untuk siswa ini</p>
            </div>
            <a href="{{ route('committee.saw-results.index', ['specialization' => $sawResult->specialization]) }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Hasil SAW
            </a>
        </div>
    </div>

    <!-- Student Identity Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <!-- Avatar & Rank Badge -->
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-3xl font-bold text-indigo-600">
                            {{ substr($sawResult->student->full_name, 0, 1) }}
                        </span>
                    </div>
                    <!-- Rank badge -->
                    <div class="absolute -bottom-2 -right-2 flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold shadow
                        @if($sawResult->rank == 1) bg-yellow-100 text-yellow-800
                        @elseif($sawResult->rank == 2) bg-gray-200 text-gray-800
                        @elseif($sawResult->rank == 3) bg-orange-100 text-orange-800
                        @else bg-indigo-100 text-indigo-800
                        @endif">
                        @if($sawResult->rank == 1) 🥇
                        @elseif($sawResult->rank == 2) 🥈
                        @elseif($sawResult->rank == 3) 🥉
                        @else {{ $sawResult->rank }}
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $sawResult->student->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $sawResult->student->nisn }}</p>
                    <p class="text-sm text-gray-500">{{ $sawResult->student->user->email ?? '-' }}</p>
                </div>
            </div>

            <!-- Spacer -->
            <div class="sm:flex-1"></div>

            <!-- Key Metrics -->
            <div class="flex gap-6">
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Peringkat</p>
                    <p class="text-2xl font-bold text-indigo-600">#{{ $sawResult->rank }}</p>
                </div>
                <div class="w-px bg-gray-200"></div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Final Score</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($sawResult->final_score, 4) }}</p>
                </div>
                <div class="w-px bg-gray-200"></div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Spesialisasi</p>
                    <p class="text-lg font-bold text-gray-800">{{ ucfirst($sawResult->specialization) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Breakdown Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Rincian Perhitungan per Kriteria</h3>
            <span class="text-xs text-gray-500">
                SAW Score = Σ (Bobot × Nilai Ternormalisasi)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot (w)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Ternormalisasi (r)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kontribusi (w × r)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $details = $sawResult->detail_calculation;
                        $no = 1;
                        $totalScore = $sawResult->final_score;
                    @endphp

                    @foreach($details as $code => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $no++ }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $detail['criteria_name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $code }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ number_format($detail['weight'], 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ number_format($detail['normalized_value'], 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-bold text-indigo-600">{{ number_format($detail['score'], 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $percentage = $totalScore > 0 ? ($detail['score'] / $totalScore) * 100 : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2" style="min-width: 60px;">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-12 text-right">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <!-- Total Row -->
                <tfoot class="bg-indigo-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm font-bold text-gray-800 text-right">
                            Total SAW Score
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold text-indigo-700">{{ number_format($sawResult->final_score, 4) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold text-indigo-600">100%</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Metadata / Calculation Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Perhitungan</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Tahun Ajaran</p>
                <p class="text-sm font-semibold text-gray-800 mt-1">{{ $sawResult->academicYear->year ?? '-' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Spesialisasi</p>
                <p class="text-sm font-semibold text-gray-800 mt-1">{{ ucfirst($sawResult->specialization) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Perhitungan</p>
                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $sawResult->calculated_at?->format('d M Y H:i') ?? '-' }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Dihitung Oleh</p>
                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $sawResult->calculator?->name ?? 'Sistem' }}
                </p>
            </div>
        </div>
    </div>

</div>
@endsection