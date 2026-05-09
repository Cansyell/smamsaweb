@extends('layouts.app')

@section('title', 'Detail Validasi Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('committee.validation.index') }}" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Detail Validasi Siswa</h2>
                    <p class="text-gray-600 mt-1">{{ $student->full_name }} ({{ $student->student_id }})</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($student->has_pending_resubmission)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300 animate-pulse">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        Data Diperbaiki #{{ $student->resubmission_count }}
                    </span>
                @endif
                {!! $student->status_badge !!}
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Resubmission Banner -->
    @if($student->has_pending_resubmission)
    <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold text-blue-800">Siswa Telah Memperbaiki Data</p>
                <p class="text-sm text-blue-700 mt-1">
                    Data diperbaiki pada <strong>{{ $student->resubmitted_at?->format('d M Y, H:i') }}</strong> 
                    (Pengajuan ke-{{ $student->resubmission_count }})
                </p>
                @if($student->resubmission_notes)
                    <p class="text-sm text-blue-700 mt-2 italic">"{{ $student->resubmission_notes }}"</p>
                @endif
                <p class="text-sm text-blue-600 mt-2 font-medium">Silakan periksa perubahan data dan dokumen di bawah, lalu lakukan validasi ulang.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Incomplete Warning -->
    @if(!$validationData['can_approve'])
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
            <p class="font-bold">Peringatan!</p>
            <p>Data siswa belum lengkap. Siswa tidak dapat divalidasi sampai semua data dilengkapi.</p>
        </div>
    @endif

    <!-- Validation Progress -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Kelengkapan Data</h3>
        <div class="mb-2">
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-700">Kelengkapan Data</span>
                <span class="text-sm font-medium text-gray-700">{{ number_format($validationData['validation_check']['completeness_percentage'], 0) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500"
                     style="width: {{ $validationData['validation_check']['completeness_percentage'] }}%"></div>
            </div>
        </div>
        @if(!$validationData['validation_check']['is_complete'])
            <div class="mt-4 space-y-2">
                <p class="text-sm font-medium text-red-600">Data yang belum lengkap:</p>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                    @foreach($validationData['validation_check']['missing_data'] as $category => $errors)
                        @foreach($errors as $error)
                            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Personal Data -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Data Pribadi
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-medium text-gray-600">ID Siswa</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['student_id'] }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">NISN</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['nisn'] }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['full_name'] }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Email</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['email'] }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Nama Ayah</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['father_name'] ?? '-' }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Nama Ibu</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['mother_name'] ?? '-' }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Jenis Kelamin</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['gender'] }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Tempat, Tanggal Lahir</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['place_of_birth'] ?? '-' }}, {{ $validationData['personal_data']['date_of_birth'] ?? '-' }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Umur</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['age'] }} tahun</p></div>
            <div><label class="block text-sm font-medium text-gray-600">No. Telepon</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['phone_number'] ?? '-' }}</p></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Alamat</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['address'] ?? '-' }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Sekolah Asal</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['previous_school'] ?? '-' }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Tahun Lulus</label>
                <p class="mt-1 text-gray-900">{{ $validationData['personal_data']['graduation_year'] ?? '-' }}</p></div>
            <div><label class="block text-sm font-medium text-gray-600">Nomor KIP</label>
                <p class="mt-1 text-gray-900">
                    @if($validationData['personal_data']['has_kip'])
                        <span class="text-green-600">{{ $validationData['personal_data']['kip_number'] }}</span>
                    @else
                        <span class="text-gray-400">Tidak memiliki KIP</span>
                    @endif
                </p></div>
            <div><label class="block text-sm font-medium text-gray-600">Spesialisasi</label>
                <p class="mt-1">
                    @if($validationData['personal_data']['specialization'])
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $student->specialization === 'tahfiz' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $validationData['personal_data']['specialization'] }}
                        </span>
                    @else
                        <span class="text-red-500">Belum dipilih</span>
                    @endif
                </p></div>
        </div>
    </div>

    <!-- Report Grades -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Nilai Raport
        </h3>
        @if($validationData['report_grade'])
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">PAI</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $validationData['report_grade']['islamic_studies'] }}</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Bahasa Indonesia</p>
                    <p class="text-3xl font-bold text-green-600">{{ $validationData['report_grade']['indonesian_language'] }}</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Bahasa Inggris</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $validationData['report_grade']['english_language'] }}</p>
                </div>
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Rata-rata</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ number_format($validationData['report_grade']['average_grade'], 2) }}</p>
                </div>
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <p class="mt-2 text-sm text-red-500 font-medium">Nilai raport belum diinput</p>
            </div>
        @endif
    </div>

    <!-- Test Scores -->
    @if($validationData['test_score'])
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Nilai Tes Masuk
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Hafalan Quran</p>
                <p class="text-2xl font-bold text-purple-600">{{ $validationData['test_score']['quran_achievement'] }}</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Baca Quran</p>
                <p class="text-2xl font-bold text-blue-600">{{ $validationData['test_score']['quran_reading'] }}</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Interview</p>
                <p class="text-2xl font-bold text-green-600">{{ $validationData['test_score']['interview'] }}</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Public Speaking</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $validationData['test_score']['public_speaking'] }}</p>
            </div>
            <div class="text-center p-4 bg-pink-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Dialogue</p>
                <p class="text-2xl font-bold text-pink-600">{{ $validationData['test_score']['dialogue'] }}</p>
            </div>
            <div class="text-center p-4 bg-indigo-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Rata-rata</p>
                <p class="text-2xl font-bold text-indigo-600">{{ number_format($validationData['test_score']['average_score'], 2) }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Documents -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Dokumen Pendukung
            </h3>
            @php
                $totalDocs = count($validationData['documents']);
                $validDocs = collect($validationData['documents'])->where('validation_status', 'valid')->count();
                $invalidDocs = collect($validationData['documents'])->where('validation_status', 'invalid')->count();
                $pendingDocs = collect($validationData['documents'])->where('validation_status', 'pending')->count();
            @endphp
            <div class="flex items-center gap-2">
                @if($validDocs === $totalDocs && $totalDocs > 0)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Semua Diterima
                    </span>
                @else
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                        {{ $validDocs }}/{{ $totalDocs }} Diterima
                    </span>
                    @if($invalidDocs > 0)
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">
                            {{ $invalidDocs }} Ditolak
                        </span>
                    @endif
                    @if($pendingDocs > 0)
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                            {{ $pendingDocs }} Pending
                        </span>
                    @endif
                @endif
            </div>
        </div>

        <!-- Document Status Alert -->
        @if($invalidDocs > 0 || $pendingDocs > 0)
        <div class="mb-4 p-3 {{ $invalidDocs > 0 ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }} border rounded-lg">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 {{ $invalidDocs > 0 ? 'text-red-600' : 'text-yellow-600' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="text-sm">
                    @if($invalidDocs > 0)
                        <p class="font-semibold text-red-800">{{ $invalidDocs }} dokumen ditolak</p>
                        <p class="text-red-700 mt-1">Siswa tidak dapat divalidasi sampai semua dokumen yang ditolak diperbaiki dan diterima.</p>
                    @elseif($pendingDocs > 0)
                        <p class="font-semibold text-yellow-800">{{ $pendingDocs }} dokumen belum divalidasi</p>
                        <p class="text-yellow-700 mt-1">Validasi semua dokumen terlebih dahulu sebelum memvalidasi data siswa.</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if(count($validationData['documents']) > 0)
            <div class="space-y-4">
                @foreach($validationData['documents'] as $doc)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition
                    {{ $doc['validation_status'] === 'invalid' ? 'border-red-200 bg-red-50' : '' }}
                    {{ $doc['validation_status'] === 'valid' ? 'border-green-200 bg-green-50' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="flex-shrink-0">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-gray-900">{{ $doc['type_label'] }}</p>
                                    @if($doc['validation_status'] === 'invalid')
                                        <span class="text-xs text-red-600 font-semibold">(Perlu diganti)</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 truncate">{{ $doc['file_name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $doc['file_size'] }}</p>
                                @if($doc['notes'])
                                    <p class="text-xs text-red-600 mt-1 font-medium">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Catatan: {{ $doc['notes'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            {!! $doc['status_badge'] !!}
                            <a href="{{ $doc['file_url'] }}" target="_blank"
                               class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-sm">
                                Lihat
                            </a>
                            @if(in_array($student->validation_status, ['pending']))
                                <button onclick="validateDocument({{ $doc['id'] }}, 'valid')"
                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 text-sm">
                                    Terima
                                </button>
                                <button onclick="validateDocument({{ $doc['id'] }}, 'invalid')"
                                        class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-sm">
                                    Tolak
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <p class="mt-2 text-sm text-red-500 font-medium">Belum ada dokumen yang diupload</p>
            </div>
        @endif
    </div>

    <!-- Validation Notes (if exists) -->
    @if($student->validation_notes && $student->validation_status !== 'invalid')
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="ml-3">
                <p class="text-sm text-yellow-700 font-medium">Catatan Validasi:</p>
                <p class="mt-1 text-sm text-yellow-700">{{ $student->validation_notes }}</p>
                @if($student->validated_at)
                    <p class="mt-1 text-xs text-yellow-600">Divalidasi pada: {{ $student->validated_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    @if($student->validation_status === 'pending')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Tindakan Validasi
            @if($student->has_pending_resubmission)
                <span class="text-sm font-normal text-blue-600 ml-2">(Validasi Ulang — Pengajuan ke-{{ $student->resubmission_count }})</span>
            @endif
        </h3>

        <!-- Warning if cannot approve -->
        @if(!$validationData['can_approve'])
        <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-yellow-800 mb-1">Tombol Validasi Tidak Dapat Diklik</p>
                    <p class="text-sm text-yellow-700">Pastikan kondisi berikut terpenuhi:</p>
                    <ul class="mt-2 text-sm text-yellow-700 space-y-1 list-disc list-inside">
                        @if(!$validationData['validation_check']['is_complete'])
                            <li>Data siswa harus lengkap ({{ number_format($validationData['validation_check']['completeness_percentage'], 0) }}% saat ini)</li>
                        @endif
                        @php
                            $rejectedDocs = collect($validationData['documents'])->where('validation_status', 'invalid');
                            $pendingDocs = collect($validationData['documents'])->where('validation_status', 'pending');
                        @endphp
                        @if($rejectedDocs->count() > 0)
                            <li class="font-semibold">Semua dokumen yang ditolak harus diterima ({{ $rejectedDocs->count() }} dokumen ditolak)</li>
                        @endif
                        @if($pendingDocs->count() > 0)
                            <li class="font-semibold">Semua dokumen harus divalidasi ({{ $pendingDocs->count() }} dokumen belum divalidasi)</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button onclick="showApproveModal()"
                    {{ !$validationData['can_approve'] ? 'disabled' : '' }}
                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-2 disabled:opacity-60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Validasi Data Siswa
                @if(!$validationData['can_approve'])
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </button>
            <button onclick="showRejectModal()"
                    class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tolak Data Siswa
            </button>
        </div>
    </div>
    @endif

    <!-- Validation History Timeline -->
    @if(count($validationData['history']) > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Validasi
        </h3>
        <div class="relative">
            <!-- Vertical line -->
            <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gray-200"></div>

            <div class="space-y-6">
                @foreach($validationData['history'] as $log)
                <div class="relative flex gap-4">
                    <!-- Dot -->
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center z-10 shadow
                        {{ $log['action'] === 'approved' ? 'bg-green-100' : '' }}
                        {{ $log['action'] === 'rejected' ? 'bg-red-100' : '' }}
                        {{ $log['action'] === 'resubmitted' ? 'bg-blue-100' : '' }}
                        {{ in_array($log['action'], ['doc_validated','doc_rejected']) ? 'bg-gray-100' : '' }}">
                        @if($log['action'] === 'approved')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @elseif($log['action'] === 'rejected')
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @elseif($log['action'] === 'resubmitted')
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 bg-gray-50 rounded-lg p-4 min-w-0">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2">
                                {!! $log['action_badge'] !!}
                                @if($log['action'] === 'resubmitted')
                                    <span class="text-xs text-gray-500">(Pengajuan ke-{{ $log['resubmission_count'] }})</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-400 flex-shrink-0">{{ $log['created_at'] }}</span>
                        </div>
                        <p class="text-sm text-gray-700 mt-1.5">
                            oleh <strong>{{ $log['actor_name'] }}</strong>
                        </p>
                        @if($log['notes'])
                            <p class="text-sm text-gray-600 mt-1.5 italic border-l-2 border-gray-300 pl-2">
                                "{{ $log['notes'] }}"
                            </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mt-4">
                @if($student->has_pending_resubmission) Validasi Ulang Data Siswa
                @else Validasi Data Siswa @endif
            </h3>
            @if($student->has_pending_resubmission)
                <p class="text-sm text-center text-blue-600 mt-1">Pengajuan ke-{{ $student->resubmission_count }}</p>
            @endif
            <form action="{{ route('committee.validation.approve', $student) }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeApproveModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Validasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mt-4">Tolak Data Siswa</h3>
            <form action="{{ route('committee.validation.reject', $student) }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="notes" rows="4" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                              placeholder="Jelaskan alasan penolakan (minimal 10 karakter)..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Alasan penolakan akan dikirimkan ke siswa</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Tolak Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Validation Modal -->
<div id="documentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center" id="documentModalTitle">Validasi Dokumen</h3>
            <form id="documentForm" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="status" id="documentStatus">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan <span id="documentNotesRequired" class="text-red-500 hidden">*</span>
                    </label>
                    <textarea name="notes" id="documentNotes" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Tambahkan catatan..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDocumentModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit" id="documentSubmitBtn"
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApproveModal() { document.getElementById('approveModal').classList.remove('hidden'); }
function closeApproveModal() { document.getElementById('approveModal').classList.add('hidden'); }
function showRejectModal() { document.getElementById('rejectModal').classList.remove('hidden'); }
function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }

function validateDocument(documentId, status) {
    const modal      = document.getElementById('documentModal');
    const form       = document.getElementById('documentForm');
    const title      = document.getElementById('documentModalTitle');
    const statusInput= document.getElementById('documentStatus');
    const notesInput = document.getElementById('documentNotes');
    const notesReq   = document.getElementById('documentNotesRequired');
    const submitBtn  = document.getElementById('documentSubmitBtn');

    form.action      = `/committee/validation/documents/${documentId}/validate`;
    statusInput.value= status;

    if (status === 'valid') {
        title.textContent = 'Terima Dokumen';
        notesInput.required = false;
        notesReq.classList.add('hidden');
        submitBtn.className = 'flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700';
        submitBtn.textContent = 'Terima';
    } else {
        title.textContent = 'Tolak Dokumen';
        notesInput.required = true;
        notesReq.classList.remove('hidden');
        submitBtn.className = 'flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700';
        submitBtn.textContent = 'Tolak';
    }
    modal.classList.remove('hidden');
}

function closeDocumentModal() {
    document.getElementById('documentModal').classList.add('hidden');
    document.getElementById('documentNotes').value = '';
}

window.onclick = function(event) {
    ['approveModal','rejectModal','documentModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (event.target === modal) modal.classList.add('hidden');
    });
}
</script>
@endpush
@endsection