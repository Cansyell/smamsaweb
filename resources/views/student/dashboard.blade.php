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

    <!-- Announcements Section -->
    @if(isset($announcements) && $announcements->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Pengumuman Terbaru
            </h3>
        </div>
        <div class="space-y-3">
            @foreach($announcements as $announcement)
            <a href="{{ route('student.announcements.show', $announcement) }}"
               class="flex items-start p-4 border border-gray-200 rounded-lg hover:shadow-md hover:border-indigo-300 transition group">
                @if($announcement->image_path)
                    <img src="{{ asset('storage/' . $announcement->image_path) }}"
                         alt="{{ $announcement->title }}"
                         class="w-16 h-16 object-cover rounded mr-4 flex-shrink-0"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-16 h-16 bg-indigo-50 rounded mr-4 flex-shrink-0 items-center justify-center" style="display:none;">
                        <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                @else
                    <div class="w-16 h-16 bg-indigo-50 rounded mr-4 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition truncate">
                        {{ $announcement->title }}
                    </h4>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $announcement->excerpt }}</p>
                    <div class="flex items-center mt-2 text-xs text-gray-400 gap-3">
                        <span class="flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $announcement->published_date }}
                        </span>
                        @if($announcement->file_path)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-red-100 text-red-600">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            PDF
                        </span>
                        @endif
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 flex-shrink-0 ml-2 mt-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if(!$finalResult['calculated'])
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
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg {{ $step['completed'] ? 'text-green-700' : 'text-gray-800' }}">
                                    {{ $step['name'] }}
                                </h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $step['description'] }}</p>
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
                                                <span class="ml-2 text-xs text-gray-500">({{ implode(', ', $step['details']['files']) }})</span>
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
                                        <div class="text-sm text-gray-600">Belum memilih peminatan</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
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
    @endif

    <!-- Validation Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Validasi Berkas</h3>
        @if($validationStatus['status'] === 'pending')
            @if($student->has_pending_resubmission)
                <!-- Resubmission Pending -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-blue-800 flex items-center">
                                Data Perbaikan Sedang Direview
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    Review ke-{{ $student->resubmission_count }}
                                </span>
                            </p>
                            <p class="text-sm text-blue-700 mt-2">
                                Anda telah mengajukan perbaikan data pada <strong>{{ $student->resubmitted_at?->format('d M Y, H:i') }}</strong>. 
                                Panitia sedang melakukan review ulang terhadap data yang telah Anda perbaiki.
                            </p>
                            @if($student->resubmission_notes)
                            <div class="mt-3 bg-blue-100 rounded-lg p-3">
                                <p class="text-xs font-medium text-blue-800 mb-1">Catatan Perbaikan Anda:</p>
                                <p class="text-sm text-blue-700">{{ $student->resubmission_notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Regular Pending -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-yellow-800">Menunggu Validasi</p>
                            <p class="text-sm text-yellow-700 mt-1">
                                Berkas Anda sedang dalam proses validasi oleh panitia.
                                @if($progress['percentage'] < 100) Pastikan semua langkah pendaftaran telah diselesaikan. @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
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
                        @if(!$finalResult['calculated'])
                        <p class="text-sm text-green-600 mt-2">Silakan menunggu jadwal tes dari panitia.</p>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($validationStatus['status'] === 'invalid')
            <!-- Rejection Card with Details -->
            <div class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-300 rounded-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-500 to-rose-500 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 mr-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg">Data Anda Ditolak</h4>
                                <p class="text-red-100 text-sm">Silakan perbaiki data sesuai catatan panitia</p>
                            </div>
                        </div>
                        @if($student->resubmission_count > 0)
                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Percobaan ke-{{ $student->resubmission_count + 1 }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4">
                    <!-- Catatan Penolakan -->
                    <div class="bg-white rounded-lg p-4 border border-red-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-800 mb-1">Catatan dari Panitia:</p>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $validationStatus['notes'] ?? 'Silakan perbaiki berkas dan data Anda.' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ditolak pada: {{ $validationStatus['validated_at']?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Bermasalah -->
                    @php
                        $rejectedDocuments = $student->documents->where('validation_status', 'invalid');
                        $pendingDocuments = $student->documents->where('validation_status', 'pending');
                    @endphp

                    @if($rejectedDocuments->count() > 0)
                    <div class="bg-white rounded-lg p-4 border border-orange-200">
                        <p class="text-sm font-semibold text-orange-800 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Dokumen yang Perlu Diperbaiki ({{ $rejectedDocuments->count() }})
                        </p>
                        <div class="space-y-2">
                            @foreach($rejectedDocuments as $doc)
                            <div class="flex items-start bg-orange-50 rounded-lg p-3 border border-orange-200">
                                <svg class="w-5 h-5 text-orange-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $doc->type_label }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5 truncate">{{ $doc->file_name }}</p>
                                    @if($doc->notes)
                                    <p class="text-xs text-orange-700 mt-1 bg-orange-100 px-2 py-1 rounded">
                                        <strong>Alasan:</strong> {{ $doc->notes }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Data Pribadi yang Bermasalah (optional indicator) -->
                    @if(str_contains(strtolower($validationStatus['notes'] ?? ''), 'data pribadi') || 
                        str_contains(strtolower($validationStatus['notes'] ?? ''), 'nisn') ||
                        str_contains(strtolower($validationStatus['notes'] ?? ''), 'nama') ||
                        str_contains(strtolower($validationStatus['notes'] ?? ''), 'tanggal lahir'))
                    <div class="bg-white rounded-lg p-4 border border-amber-200">
                        <p class="text-sm font-semibold text-amber-800 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Ada Masalah dengan Data Pribadi
                        </p>
                        <p class="text-xs text-gray-600">Silakan periksa dan perbaiki data pribadi Anda sesuai catatan panitia di atas.</p>
                    </div>
                    @endif

                    <!-- Action Button -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg p-4 mt-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-white font-semibold mb-1">Perbaiki Data Sekarang</p>
                                <p class="text-indigo-100 text-xs">Klik tombol di samping untuk mengedit data dan upload ulang dokumen</p>
                            </div>
                            <a href="{{ route('student.resubmission.show', $student) }}" 
                               class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-indigo-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all ml-4 whitespace-nowrap">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Perbaiki Data
                            </a>
                        </div>
                    </div>

                    <!-- Helpful Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-xs font-semibold text-blue-800 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tips Perbaikan:
                        </p>
                        <ul class="text-xs text-blue-700 space-y-1 ml-5 list-disc">
                            <li>Baca dengan teliti catatan dari panitia</li>
                            <li>Pastikan dokumen yang diupload jelas dan dapat dibaca</li>
                            <li>Format file: PDF, JPG, atau PNG (maksimal 5MB)</li>
                            <li>Periksa kembali data pribadi sebelum submit</li>
                            <li>Setelah perbaikan, klik "Submit Perbaikan" untuk review ulang</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Final Result -->
    @if($validationStatus['status'] === 'valid')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            @if($student->specialization === 'regular') Status Penerimaan (FCFS)
            @else Hasil Perhitungan SAW & Status Penerimaan
            @endif
        </h3>
        
        @if($student->specialization === 'regular')
            @if($finalResult['calculated'])
                @if($finalResult['status'] === 'accepted')
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-12 h-12 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-4">
                                <p class="font-bold text-green-800 text-2xl">🎉 Selamat! Anda Diterima</p>
                                <p class="text-green-700 mt-2">Anda diterima di <span class="font-bold text-lg">Kelas Regular</span></p>
                                <p class="text-sm text-green-600 mt-3 bg-green-100 rounded-lg p-3">
                                    <strong>Jalur Penerimaan:</strong> First Come First Serve (FCFS)<br>
                                    Penerimaan berdasarkan urutan pendaftaran yang telah divalidasi.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($finalResult['status'] === 'waiting_list')
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-400 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-12 h-12 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-4">
                                <p class="font-bold text-yellow-800 text-2xl">Daftar Tunggu</p>
                                <p class="text-yellow-700 mt-2">Anda berada dalam daftar tunggu untuk Kelas Regular</p>
                                <p class="text-sm text-yellow-600 mt-3 bg-yellow-100 rounded-lg p-3">Kuota kelas regular sudah terpenuhi. Anda akan dihubungi jika ada slot yang tersedia.</p>
                            </div>
                        </div>
                    </div>
                @elseif($finalResult['status'] === 'rejected')
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-12 h-12 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <div class="ml-4">
                                <p class="font-bold text-red-800 text-2xl">Mohon Maaf</p>
                                <p class="text-red-700 mt-2">Terima kasih atas partisipasi Anda dalam seleksi ini.</p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-blue-800">Jalur Penerimaan: Regular (FCFS)</p>
                            <p class="text-sm text-blue-700 mt-1">Status penerimaan Anda sedang dalam proses. Harap menunggu pengumuman dari panitia.</p>
                        </div>
                    </div>
                </div>
            @endif
        @else
            @if($sawResults->isEmpty())
                <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-gray-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-700">Belum Ada Perhitungan Nilai</p>
                            <p class="text-sm text-gray-600 mt-1">Perhitungan nilai SAW belum dilakukan oleh panitia. Harap menunggu.</p>
                        </div>
                    </div>
                </div>
            @else
                @php $studentSawResult = $sawResults->get($student->specialization); @endphp
                @if($studentSawResult)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border-2 border-indigo-300">
                            <p class="text-sm text-indigo-600 font-medium mb-1">Final Score SAW</p>
                            <p class="text-3xl font-bold text-indigo-700">{{ number_format($studentSawResult->final_score, 4) }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-2 border-purple-300">
                            <p class="text-sm text-purple-600 font-medium mb-1">Ranking</p>
                            <p class="text-3xl font-bold text-purple-700">{{ $studentSawResult->rank ?? '-' }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-2 border-blue-300">
                            <p class="text-sm text-blue-600 font-medium mb-1">Spesialisasi</p>
                            <p class="text-xl font-bold text-blue-700">{{ ucfirst($student->specialization) }}</p>
                        </div>
                    </div>
                    @if($studentSawResult->detail_calculation)
                        <details class="mb-6 bg-gray-50 rounded-lg p-4">
                            <summary class="cursor-pointer text-sm font-medium text-indigo-700 hover:text-indigo-800 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Lihat Detail Perhitungan SAW
                            </summary>
                            <div class="mt-3 bg-white rounded-lg p-4 border border-indigo-200">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-indigo-200">
                                            <th class="text-left py-2 text-indigo-700">Kriteria</th>
                                            <th class="text-center py-2 text-indigo-700">Bobot</th>
                                            <th class="text-center py-2 text-indigo-700">Nilai Normalisasi</th>
                                            <th class="text-right py-2 text-indigo-700">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($studentSawResult->detail_calculation as $code => $detail)
                                        <tr class="border-b border-gray-100">
                                            <td class="py-2 text-gray-700">{{ $detail['criteria_name'] }}</td>
                                            <td class="text-center py-2 text-gray-600">{{ number_format($detail['weight'], 4) }}</td>
                                            <td class="text-center py-2 text-gray-600">{{ number_format($detail['normalized_value'], 4) }}</td>
                                            <td class="text-right py-2 font-semibold text-indigo-600">{{ number_format($detail['score'], 4) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p class="text-xs text-gray-500 mt-3">Dihitung pada: {{ $studentSawResult->calculated_at->format('d M Y H:i') }}</p>
                            </div>
                        </details>
                    @endif
                    @if($finalResult['calculated'])
                        @if($finalResult['status'] === 'accepted')
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-lg p-6">
                                <div class="flex items-start">
                                    <svg class="w-12 h-12 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="ml-4">
                                        <p class="font-bold text-green-800 text-2xl">🎉 Selamat! Anda Diterima</p>
                                        <p class="text-green-700 mt-2">Anda diterima di <span class="font-bold text-lg">Kelas {{ ucfirst($finalResult['class_type']) }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @elseif($finalResult['status'] === 'waiting_list')
                            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-400 rounded-lg p-6">
                                <div class="flex items-start">
                                    <svg class="w-12 h-12 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="ml-4">
                                        <p class="font-bold text-yellow-800 text-2xl">Daftar Tunggu</p>
                                        <p class="text-yellow-700 mt-2">Anda berada dalam daftar tunggu untuk Kelas {{ ucfirst($finalResult['class_type']) }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($finalResult['status'] === 'rejected')
                            <div class="bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-400 rounded-lg p-6">
                                <div class="flex items-start">
                                    <svg class="w-12 h-12 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <div class="ml-4">
                                        <p class="font-bold text-red-800 text-2xl">Mohon Maaf</p>
                                        <p class="text-red-700 mt-2">Terima kasih atas partisipasi Anda dalam seleksi ini.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-700">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Status penerimaan akan diumumkan setelah perhitungan SAW selesai untuk semua siswa.
                            </p>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                        <p class="text-sm text-gray-600">Perhitungan SAW untuk spesialisasi <strong>{{ ucfirst($student->specialization) }}</strong> belum tersedia.</p>
                    </div>
                @endif
            @endif
        @endif
    </div>
    @endif
</div>
@endsection