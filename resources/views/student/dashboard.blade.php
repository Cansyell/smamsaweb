@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p class="text-blue-100">ID Siswa: {{ $student->student_id ?? 'Belum tersedia' }}</p>
    </div>

    <!-- Registration Progress -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Progress Pendaftaran</h3>
            <span class="text-2xl font-bold text-indigo-600">{{ number_format($progress['percentage'], 0) }}%</span>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500" 
                 style="width: {{ $progress['percentage'] }}%"></div>
        </div>
        
        <p class="text-sm text-gray-600 mb-4">
            {{ $progress['completed'] }} dari {{ $progress['total'] }} langkah telah diselesaikan
        </p>

        <!-- Registration Steps -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($steps as $step)
            <a href="#" 
               class="border-2 {{ $step['completed'] ? 'border-green-500 bg-green-50' : 'border-gray-300 bg-white' }} rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            @if($step['completed'])
                                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                            <h4 class="font-semibold {{ $step['completed'] ? 'text-green-700' : 'text-gray-700' }}">
                                {{ $step['name'] }}
                            </h4>
                        </div>
                        <p class="text-sm text-gray-600">{{ $step['description'] }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Validation Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Validasi Berkas</h3>
        
        @if($validationStatus['status'] === 'pending')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-yellow-800">Menunggu Validasi</p>
                        <p class="text-sm text-yellow-700">Berkas Anda sedang dalam proses validasi oleh panitia.</p>
                    </div>
                </div>
            </div>
        @elseif($validationStatus['status'] === 'valid')
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-800">Berkas Tervalidasi</p>
                        <p class="text-sm text-green-700">Berkas Anda telah divalidasi pada {{ $validationStatus['validated_at']?->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        @elseif($validationStatus['status'] === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-red-800">Berkas Ditolak</p>
                        <p class="text-sm text-red-700 mt-1">{{ $validationStatus['notes'] ?? 'Silakan perbaiki berkas Anda.' }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Test Scores Status -->
    @if($validationStatus['status'] === 'valid')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Tes</h3>
        
        @if($testScoresStatus['completed'])
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-semibold text-blue-800">Nilai tes telah diinput oleh panitia</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Prestasi Quran</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $testScoresStatus['quran_score'] ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Wawancara</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $testScoresStatus['interview_score'] ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Public Speaking</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $testScoresStatus['speaking_score'] ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Dialog</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $testScoresStatus['dialog_score'] ?? '-' }}</p>
                </div>
            </div>
        @else
            <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                <p class="text-gray-700">Nilai tes belum diinput. Harap menunggu penjadwalan tes dari panitia.</p>
            </div>
        @endif
    </div>
    @endif

    <!-- Final Result -->
    @if($finalResult['calculated'])
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Hasil Seleksi</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-indigo-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Nilai Akhir</p>
                <p class="text-3xl font-bold text-indigo-600">{{ number_format($finalResult['final_score'], 2) }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Ranking</p>
                <p class="text-3xl font-bold text-purple-600">{{ $finalResult['ranking'] ?? '-' }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Kelas</p>
                <p class="text-2xl font-bold text-blue-600">{{ ucfirst($finalResult['class_type'] ?? '-') }}</p>
            </div>
        </div>
        
        @if($finalResult['status'] === 'accepted')
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <p class="font-semibold text-green-800 text-lg">🎉 Selamat! Anda Diterima</p>
                <p class="text-sm text-green-700 mt-1">Anda diterima di kelas {{ ucfirst($finalResult['class_type']) }}</p>
            </div>
        @elseif($finalResult['status'] === 'waiting_list')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="font-semibold text-yellow-800 text-lg">Waiting List</p>
                <p class="text-sm text-yellow-700 mt-1">Anda berada dalam daftar tunggu</p>
            </div>
        @endif
    </div>
    @endif
</div>
@endsection
