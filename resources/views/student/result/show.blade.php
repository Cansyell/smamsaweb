@extends('layouts.app')

@section('title', 'Detail Perhitungan Ranking')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Perhitungan Ranking</h1>
            <p class="text-gray-600 mt-2">Rincian perhitungan nilai SAW untuk {{ $student->full_name }}</p>
        </div>
        <a href="{{ route('student.result.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            Kembali ke Ranking
        </a>
    </div>

    <!-- Ranking Summary -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-5xl font-bold text-blue-600 mb-2">{{ $myRanking['rank'] }}</div>
                <div class="text-gray-600">Peringkat</div>
                <div class="text-sm text-gray-500 mt-1">dari {{ $myRanking['total_students'] }} siswa</div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-bold text-green-600 mb-2">{{ number_format($myRanking['final_score'], 4) }}</div>
                <div class="text-gray-600">Skor Akhir</div>
                <div class="text-sm text-gray-500 mt-1">Hasil Perhitungan SAW</div>
            </div>
            <div class="text-center">
                <div class="mb-2">
                    @if($myRanking['is_accepted'])
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @else
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-500 rounded-full">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="text-gray-600">Status Kelulusan</div>
                <div class="text-sm font-semibold mt-1 {{ $myRanking['is_accepted'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $myRanking['is_accepted'] ? 'DITERIMA' : 'TIDAK DITERIMA' }}
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <div>
                    <span class="font-semibold">Peminatan:</span> 
                    <span class="text-gray-800">{{ ucfirst($student->specialization) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Kuota:</span> 
                    <span class="text-gray-800">{{ $myRanking['quota'] }} siswa</span>
                </div>
                <div>
                    <span class="font-semibold">Dihitung pada:</span> 
                    <span class="text-gray-800">{{ $myRanking['calculated_at']->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Calculation -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Rincian Perhitungan Per Kriteria</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot (W)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Normalisasi (R)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor (W × R)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalScore = 0;
                    @endphp
                    
                    @foreach($sawResult->detail_calculation as $code => $detail)
                    @php
                        $totalScore += $detail['score'];
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $detail['criteria_name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($detail['weight'], 4) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($detail['normalized_value'], 4) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($detail['score'], 4) }}
                        </td>
                    </tr>
                    @endforeach
                    
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="px-6 py-4 text-right text-sm text-gray-900">
                            Total Skor Akhir (V):
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                            {{ number_format($sawResult->final_score, 4) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Formula Explanation -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">Penjelasan Perhitungan:</h3>
            <div class="text-sm text-blue-700 space-y-1">
                <p>1. Setiap kriteria memiliki <strong>bobot (W)</strong> yang dihitung menggunakan metode AHP</p>
                <p>2. Nilai mentah dinormalisasi menjadi <strong>nilai normalisasi (R)</strong>:</p>
                <ul class="ml-6 list-disc">
                    <li>Benefit: R = Xij / Max(Xij)</li>
                    <li>Cost: R = Min(Xij) / Xij</li>
                </ul>
                <p>3. <strong>Skor per kriteria</strong> = Bobot (W) × Nilai Normalisasi (R)</p>
                <p>4. <strong>Skor Akhir (V)</strong> = Σ (W × R) untuk semua kriteria</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('student.result.card') }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                Cetak Kartu Hasil
            </a>
            <a href="{{ route('student.result.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition">
                Lihat Ranking Lengkap
            </a>
        </div>
    </div>
</div>
@endsection