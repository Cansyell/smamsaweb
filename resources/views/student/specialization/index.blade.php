@extends('layouts.app')

@section('title', 'Pilihan Peminatan')

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

    <!-- Specialization Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pilihan Peminatan</h2>
            @if(!$hasSpecialization)
            <a href="{{ route('student.specialization.create') }}" 
               class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Pilih Peminatan
            </a>
            @endif
        </div>

        @if($hasSpecialization)
        <!-- Sudah Pilih Peminatan -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Peminatan Anda</h3>
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
                            @if($student->testScore)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    ✓ Sudah Tes Interview
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                    ⏳ Menunggu Tes
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($ranking)
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Peringkat</p>
                        <p class="text-4xl font-bold text-indigo-600">#{{ $ranking->rank }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ranking Card (if available) -->
            @if($ranking)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Peringkat</p>
                            <p class="text-3xl font-bold text-gray-800">#{{ $ranking->rank }}</p>
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
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($ranking->final_score, 2) }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($ranking->final_score * 10, 100) }}%"></div>
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
            @else
            <!-- Waiting for Test -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="p-3 bg-yellow-100 rounded-full mr-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-yellow-800 mb-1">Menunggu Tes Interview</h3>
                        <p class="text-sm text-yellow-700">
                            Peringkat akan dihitung setelah Anda menyelesaikan tes interview. 
                            Pantau terus informasi jadwal tes dari panitia.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recommendation Info -->
            @if($recommendation && $recommendation['recommended'])
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="p-3 bg-blue-100 rounded-full mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-800 mb-1">Rekomendasi Sistem</h3>
                        <p class="text-sm text-blue-700">
                            Berdasarkan nilai rapor Anda, sistem merekomendasikan kelas 
                            <strong>{{ ucfirst($recommendation['recommended']) }}</strong>.
                            {{ $recommendation['reason'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quota Info -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kuota Peminatan</h3>
                
                <div class="space-y-4">
                    @foreach($quotaInfo as $type => $info)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">
                                @if($type === 'tahfiz') 🕌 Tahfiz
                                @elseif($type === 'language') 🌍 Bahasa
                                @else 📚 Reguler
                                @endif
                            </span>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-800">{{ $info['accepted'] }} / {{ $info['quota'] }}</span>
                                <span class="text-xs text-gray-500 block">diterima</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300
                                @if($info['percentage'] >= 90) bg-red-500
                                @elseif($info['percentage'] >= 70) bg-yellow-500
                                @else bg-green-500
                                @endif"
                                style="width: {{ $info['percentage'] }}%">
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-gray-500">
                                Tersisa {{ $info['available'] }} tempat
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $info['registered'] }} pendaftar
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <svg class="inline w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <strong>Catatan:</strong> Penerimaan berdasarkan peringkat hasil SAW. 
                        Siswa dengan ranking 1-{{ min(60, 90) }} akan diterima sesuai kuota masing-masing kelas.
                    </p>
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
                    @if($ranking)
                    <a href="{{ route('student.specialization.show') }}" 
                       class="px-6 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail
                    </a>
                    @endif
                    @if(!$student->testScore)
                    <a href="{{ route('student.specialization.edit') }}" 
                       class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Ubah Pilihan
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @else
        <!-- Belum Pilih Peminatan -->
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Memilih Peminatan</h3>
            <p class="text-gray-600 mb-6">Silakan pilih kelas peminatan sesuai minat dan kemampuan Anda</p>
            
            @if($student->isPersonalDataCompleted() && $student->isReportGradeCompleted() && $student->isDocumentsCompleted())
            <a href="{{ route('student.specialization.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Pilih Peminatan Sekarang
            </a>
            @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 max-w-md mx-auto">
                <p class="text-sm text-red-800 font-semibold mb-2">
                    Lengkapi data berikut terlebih dahulu:
                </p>
                <ul class="text-sm text-red-700 space-y-1 text-left">
                    @if(!$student->isPersonalDataCompleted())
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Data Pribadi
                        </li>
                    @endif
                    @if(!$student->isReportGradeCompleted())
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Nilai Rapor
                        </li>
                    @endif
                    @if(!$student->isDocumentsCompleted())
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Upload Dokumen (minimal 2)
                        </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection