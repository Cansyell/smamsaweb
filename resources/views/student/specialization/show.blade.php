@extends('layouts.app')

@section('title', 'Detail Hasil Peminatan')

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

    <!-- Main Result Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detail Hasil Peminatan</h2>
            <a href="{{ route('student.specialization.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Kembali
            </a>
        </div>

        @if($sawResult)
        <!-- Score Cards -->
        <div class="space-y-6">
            <!-- Specialization & Rank -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Peminatan</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-3xl font-bold text-indigo-600">
                                @if($student->specialization === 'tahfiz')
                                    🕌 Tahfiz
                                @elseif($student->specialization === 'language')
                                    🌍 Bahasa
                                @else
                                    📚 Reguler
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 mb-1">Peringkat Anda</p>
                        <p class="text-5xl font-bold text-indigo-600">#{{ $sawResult->rank }}</p>
                    </div>
                </div>
            </div>

            <!-- Score Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Peringkat</p>
                            <p class="text-3xl font-bold text-gray-800">#{{ $sawResult->rank }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nilai Akhir SAW</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($sawResult->final_score, 2) }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($sawResult->final_score * 10, 100) }}%"></div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nilai Akademik</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($student->reportGrade->average_grade ?? 0, 1) }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $student->reportGrade->average_grade ?? 0 }}%"></div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nilai Tes</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($student->testScore->average_score ?? 0, 1) }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $student->testScore->average_score ?? 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- SAW Calculation Details -->
            @if($sawResult->detail_calculation)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Detail Perhitungan SAW</h3>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-700 mb-2">
                        <strong>Metode:</strong> Simple Additive Weighting (SAW)
                    </p>
                    <p class="text-xs text-gray-600">
                        Nilai akhir dihitung dengan rumus: <strong>Σ(wi × ri)</strong>, dimana wi adalah bobot kriteria dan ri adalah nilai normalisasi
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kriteria</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Bobot (w)</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Nilai Normalisasi (r)</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Skor (w×r)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($sawResult->detail_calculation as $code => $detail)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                    {{ $detail['criteria_name'] ?? $code }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                        {{ number_format($detail['weight'] * 100, 1) }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    {{ number_format($detail['normalized_value'], 4) }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">
                                    {{ number_format($detail['score'], 4) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-800">
                                    Total Nilai Akhir:
                                </td>
                                <td class="px-4 py-3 text-right text-lg font-bold text-green-600">
                                    {{ number_format($sawResult->final_score, 4) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($sawResult->calculated_at)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Dihitung pada: {{ $sawResult->calculated_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>
                @endif
            </div>
            @endif

            <!-- Grade Details -->
            @if($student->reportGrade)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Detail Nilai Rapor</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">PAI</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $student->reportGrade->pai_grade }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $student->reportGrade->pai_grade }}%"></div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">B. Indonesia</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $student->reportGrade->indonesian_grade }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $student->reportGrade->indonesian_grade }}%"></div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">B. Inggris</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $student->reportGrade->english_grade }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $student->reportGrade->english_grade }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Test Scores -->
            @if($student->testScore)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Detail Nilai Tes Interview</h3>
                
                <div class="space-y-3">
                    @if($student->testScore->quran_achievement)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Prestasi Tahfiz</span>
                        <span class="text-lg font-bold text-green-600">{{ $student->testScore->quran_achievement }}</span>
                    </div>
                    @endif
                    @if($student->testScore->quran_reading)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Baca Al-Quran</span>
                        <span class="text-lg font-bold text-blue-600">{{ $student->testScore->quran_reading }}</span>
                    </div>
                    @endif
                    @if($student->testScore->interview)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Interview</span>
                        <span class="text-lg font-bold text-purple-600">{{ $student->testScore->interview }}</span>
                    </div>
                    @endif
                    @if($student->testScore->public_speaking)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Public Speaking</span>
                        <span class="text-lg font-bold text-orange-600">{{ $student->testScore->public_speaking }}</span>
                    </div>
                    @endif
                    @if($student->testScore->dialogue)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Dialog Bahasa</span>
                        <span class="text-lg font-bold text-pink-600">{{ $student->testScore->dialogue }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Statistics Card -->
            @if($statistics && $statistics['total_students'] > 0)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Statistik Peminatan {{ ucfirst($student->specialization) }}</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Total Peserta</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $statistics['total_students'] }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Nilai Tertinggi</p>
                        <p class="text-3xl font-bold text-green-600">{{ $statistics['highest_score'] }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Nilai Rata-rata</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $statistics['average_score'] }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Nilai Terendah</p>
                        <p class="text-3xl font-bold text-red-600">{{ $statistics['lowest_score'] }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Info Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Tentang Sistem Peringkat</p>
                        <ul class="space-y-1 text-xs">
                            <li>• Menggunakan metode AHP untuk menentukan bobot kriteria</li>
                            <li>• Menggunakan metode SAW untuk perhitungan nilai akhir</li>
                            <li>• Peringkat diperbarui secara otomatis setelah tes</li>
                            <li>• Penempatan kelas berdasarkan kuota dan ranking</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="flex justify-between items-center border-t border-gray-200 pt-6">
                <a href="{{ route('student.dashboard') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Ke Dashboard
                </a>
                <a href="{{ route('student.specialization.index') }}" 
                   class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                    Lihat Ringkasan
                    <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        @else
        <!-- No Result Yet -->
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Perhitungan Sedang Diproses</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                Hasil peringkat akan ditampilkan setelah panitia melakukan perhitungan SAW. 
                Mohon tunggu pengumuman lebih lanjut.
            </p>
            <a href="{{ route('student.specialization.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Halaman Utama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection