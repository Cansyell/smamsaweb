@extends('layouts.app')

@section('title', 'Detail Hasil SAW - ' . $student->full_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Hasil SAW</h2>
                <p class="text-gray-600 mt-1">Rincian perhitungan untuk {{ $student->full_name }}</p>
            </div>
            <a href="{{ route('committee.saw-results.index') }}" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Student Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center gap-6">
            <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center">
                <span class="text-3xl font-bold text-indigo-600">{{ substr($student->full_name, 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-800">{{ $student->full_name }}</h3>
                <p class="text-sm text-gray-500">NISN: {{ $student->nisn }}</p>
                <p class="text-sm text-gray-500">Email: {{ $student->user->email ?? '-' }}</p>
                <p class="text-sm text-gray-600 mt-2">
                    Pilihan Spesialisasi: 
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $student->specialization === 'tahfiz' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $student->specialization === 'tahfiz' ? 'Tahfiz' : 'Bahasa' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Ranking Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tahfiz Ranking -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🕌</span>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Ranking Tahfiz</h3>
                        <p class="text-sm text-gray-600">Hasil penilaian spesialisasi Tahfiz</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if($tahfizResult)
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Peringkat</span>
                            <span class="text-3xl font-bold text-green-600">#{{ $tahfizResult->rank }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Final Score</span>
                            <span class="text-2xl font-bold text-green-600">{{ number_format($tahfizResult->final_score, 4) }}</span>
                        </div>
                        <div class="pt-4 border-t">
                            <p class="text-xs text-gray-500 mb-2">Dihitung pada:</p>
                            <p class="text-sm font-medium text-gray-700">{{ $tahfizResult->calculated_at?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada data ranking Tahfiz</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Language Ranking -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🌍</span>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Ranking Bahasa</h3>
                        <p class="text-sm text-gray-600">Hasil penilaian spesialisasi Bahasa</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if($languageResult)
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Peringkat</span>
                            <span class="text-3xl font-bold text-blue-600">#{{ $languageResult->rank }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Final Score</span>
                            <span class="text-2xl font-bold text-blue-600">{{ number_format($languageResult->final_score, 4) }}</span>
                        </div>
                        <div class="pt-4 border-t">
                            <p class="text-xs text-gray-500 mb-2">Dihitung pada:</p>
                            <p class="text-sm font-medium text-gray-700">{{ $languageResult->calculated_at?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada data ranking Bahasa</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Calculation Tahfiz -->
    @if($tahfizResult)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <h3 class="text-lg font-semibold text-gray-800">Rincian Perhitungan Tahfiz</h3>
            <p class="text-sm text-gray-600 mt-1">Detail perhitungan per kriteria untuk spesialisasi Tahfiz</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Bobot</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai Ternormalisasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kontribusi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tahfizResult->detail_calculation as $code => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $detail['criteria_name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ number_format($detail['weight'], 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ number_format($detail['normalized_value'], 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-bold text-green-600">{{ number_format($detail['score'], 4) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-green-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-800 text-right">Total Score</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold text-green-700">{{ number_format($tahfizResult->final_score, 4) }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <!-- Detail Calculation Language -->
    @if($languageResult)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
            <h3 class="text-lg font-semibold text-gray-800">Rincian Perhitungan Bahasa</h3>
            <p class="text-sm text-gray-600 mt-1">Detail perhitungan per kriteria untuk spesialisasi Bahasa</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Bobot</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai Ternormalisasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kontribusi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($languageResult->detail_calculation as $code => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $detail['criteria_name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ number_format($detail['weight'], 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ number_format($detail['normalized_value'], 4) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-bold text-blue-600">{{ number_format($detail['score'], 4) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-blue-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-800 text-right">Total Score</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold text-blue-700">{{ number_format($languageResult->final_score, 4) }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection