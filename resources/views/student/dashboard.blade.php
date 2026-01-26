@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ $student->full_name }}!</h2>
        <p class="text-blue-100">ID Siswa: {{ $student->student_id ?? 'Belum tersedia' }}</p>
        <p class="text-blue-100 text-sm mt-1">Tahun Ajaran: {{ $student->academicYear->year ?? '-' }}</p>
    </div>

    <!-- Overall Progress Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Progress Pendaftaran</h3>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $progress['completed'] }} dari {{ $progress['total'] }} langkah telah diselesaikan
                </p>
            </div>
            <div class="text-right">
                <span class="text-4xl font-bold text-indigo-600">{{ number_format($progress['percentage'], 0) }}%</span>
                <p class="text-xs text-gray-500 mt-1">Selesai</p>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500 relative" 
                 style="width: {{ $progress['percentage'] }}%">
                @if($progress['percentage'] > 0)
                <div class="absolute right-0 top-0 h-full w-1 bg-white opacity-50"></div>
                @endif
            </div>
        </div>
        
        @if($progress['percentage'] < 100)
        <p class="text-sm text-amber-600 mt-2">
            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            Mohon lengkapi semua langkah pendaftaran untuk dapat divalidasi oleh panitia
        </p>
        @else
        <p class="text-sm text-green-600 mt-2">
            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Selamat! Semua langkah pendaftaran telah selesai. Menunggu validasi dari panitia.
        </p>
        @endif
    </div>

    <!-- Registration Steps -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Langkah Pendaftaran</h3>
        
        <div class="space-y-4">
            @foreach($steps as $index => $step)
            <div class="border-2 {{ $step['completed'] ? 'border-green-500 bg-green-50' : 'border-gray-300 bg-white' }} rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start">
                    <!-- Step Number/Icon -->
                    <div class="flex-shrink-0 mr-4">
                        @if($step['completed'])
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-xl font-bold text-gray-600">{{ $index + 1 }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Step Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg {{ $step['completed'] ? 'text-green-700' : 'text-gray-800' }}">
                                    {{ $step['name'] }}
                                </h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $step['description'] }}</p>
                                
                                <!-- Step Details -->
                                <div class="mt-3">
                                    @if($step['name'] === 'Data Pribadi')
                                        <div class="flex items-center space-x-4 text-sm">
                                            <span class="text-gray-600">
                                                <span class="font-medium">{{ $step['details']['completed'] }}/{{ $step['details']['total'] }}</span> field terisi
                                            </span>
                                            <div class="flex-1 max-w-xs">
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $step['details']['percentage'] }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($step['name'] === 'Nilai Rapor')
                                        @if($step['completed'])
                                        <div class="flex items-center space-x-4 text-sm">
                                            <span class="text-gray-600">
                                                Rata-rata: <span class="font-semibold text-blue-600">{{ number_format($step['details']['average'], 2) }}</span>
                                            </span>
                                        </div>
                                        @else
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $step['details']['completed'] }}/{{ $step['details']['total'] }}</span> mata pelajaran diinput
                                        </div>
                                        @endif
                                    @elseif($step['name'] === 'Upload Berkas')
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $step['details']['completed'] }}</span> dokumen terupload
                                            @if(count($step['details']['files']) > 0)
                                                <span class="ml-2 text-xs text-gray-500">
                                                    ({{ implode(', ', $step['details']['files']) }})
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($step['name'] === 'Pilih Peminatan')
                                        @if($step['completed'])
                                        <div class="text-sm">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ ucfirst($step['details']['selected']) }}
                                            </span>
                                        </div>
                                        @else
                                        <div class="text-sm text-gray-600">
                                            Belum memilih peminatan
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <a href="{{ route($step['route']) }}" 
                               class="ml-4 inline-flex items-center px-4 py-2 {{ $step['completed'] ? 'bg-green-600 hover:bg-green-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-sm font-medium rounded-lg transition">
                                {{ $step['completed'] ? 'Lihat' : 'Lengkapi' }}
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Validation Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Validasi Berkas</h3>
        
        @if($validationStatus['status'] === 'pending')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-yellow-800">Menunggu Validasi</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Berkas Anda sedang dalam proses validasi oleh panitia. 
                            @if($progress['percentage'] < 100)
                                Pastikan semua langkah pendaftaran telah diselesaikan.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @elseif($validationStatus['status'] === 'valid')
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-800">Berkas Tervalidasi ✓</p>
                        <p class="text-sm text-green-700 mt-1">
                            Berkas Anda telah divalidasi pada {{ $validationStatus['validated_at']?->format('d M Y H:i') }}
                        </p>
                        <p class="text-sm text-green-600 mt-2">
                            Silakan menunggu jadwal tes dari panitia.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($validationStatus['status'] === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-red-800">Berkas Ditolak</p>
                        <p class="text-sm text-red-700 mt-1 font-medium">Catatan dari Panitia:</p>
                        <p class="text-sm text-red-700 mt-1 bg-red-100 p-3 rounded">
                            {{ $validationStatus['notes'] ?? 'Silakan perbaiki berkas Anda.' }}
                        </p>
                        <a href="{{ route('student.documents.index') }}" class="inline-block mt-3 text-sm font-medium text-red-700 hover:text-red-800">
                            Perbaiki Berkas →
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Test Scores Status -->
    @if($validationStatus['status'] === 'valid')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Tes & Nilai</h3>
        
        @if($testScoresStatus['completed'])
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-800">Nilai Tes Tersedia</p>
                        <p class="text-sm text-blue-700 mt-1">
                            Nilai tes telah diinput oleh panitia. Rata-rata: <span class="font-bold">{{ number_format($testScoresStatus['average'], 2) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                    <p class="text-xs text-purple-600 font-medium mb-1">Prestasi Quran</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $testScoresStatus['quran_achievement'] ?? '-' }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                    <p class="text-xs text-blue-600 font-medium mb-1">Baca Quran</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $testScoresStatus['quran_reading'] ?? '-' }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                    <p class="text-xs text-green-600 font-medium mb-1">Wawancara</p>
                    <p class="text-2xl font-bold text-green-700">{{ $testScoresStatus['interview'] ?? '-' }}</p>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-lg border border-amber-200">
                    <p class="text-xs text-amber-600 font-medium mb-1">Public Speaking</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $testScoresStatus['public_speaking'] ?? '-' }}</p>
                </div>
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 p-4 rounded-lg border border-pink-200">
                    <p class="text-xs text-pink-600 font-medium mb-1">Dialog</p>
                    <p class="text-2xl font-bold text-pink-700">{{ $testScoresStatus['dialogue'] ?? '-' }}</p>
                </div>
            </div>
        @else
            <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-gray-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-700">Menunggu Jadwal Tes</p>
                        <p class="text-sm text-gray-600 mt-1">
                            Nilai tes belum diinput. Harap menunggu penjadwalan tes dari panitia.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif

    <!-- Final Result -->
    @if($finalResult['calculated'])
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Hasil Seleksi Akhir</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border-2 border-indigo-300">
                <p class="text-sm text-indigo-600 font-medium mb-1">Nilai Akademik</p>
                <p class="text-3xl font-bold text-indigo-700">{{ number_format($finalResult['academic_score'], 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-2 border-purple-300">
                <p class="text-sm text-purple-600 font-medium mb-1">Nilai Tes</p>
                <p class="text-3xl font-bold text-purple-700">{{ number_format($finalResult['test_score'], 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-2 border-blue-300">
                <p class="text-sm text-blue-600 font-medium mb-1">Total Nilai</p>
                <p class="text-3xl font-bold text-blue-700">{{ number_format($finalResult['total_score'], 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-lg border-2 border-teal-300">
                <p class="text-sm text-teal-600 font-medium mb-1">Peringkat</p>
                <p class="text-3xl font-bold text-teal-700">{{ $finalResult['ranking'] ?? '-' }}</p>
            </div>
        </div>
        
        @if($finalResult['status'] === 'accepted')
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-bold text-green-800 text-2xl">🎉 Selamat! Anda Diterima</p>
                        <p class="text-green-700 mt-2">
                            Anda diterima di <span class="font-bold text-lg">Kelas {{ ucfirst($finalResult['class_type']) }}</span>
                        </p>
                        <p class="text-sm text-green-600 mt-2">
                            Silakan menunggu informasi lebih lanjut dari panitia terkait daftar ulang.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($finalResult['status'] === 'waiting_list')
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-400 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-bold text-yellow-800 text-2xl">Daftar Tunggu</p>
                        <p class="text-yellow-700 mt-2">
                            Anda berada dalam daftar tunggu untuk Kelas {{ ucfirst($finalResult['class_type']) }}
                        </p>
                        <p class="text-sm text-yellow-600 mt-2">
                            Harap menunggu informasi lebih lanjut dari panitia.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($finalResult['status'] === 'rejected')
            <div class="bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-bold text-red-800 text-2xl">Mohon Maaf</p>
                        <p class="text-red-700 mt-2">
                            Terima kasih atas partisipasi Anda dalam seleksi ini.
                        </p>
                        <p class="text-sm text-red-600 mt-2">
                            Semangat untuk kesempatan selanjutnya!
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif
</div>
@endsection