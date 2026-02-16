@extends('layouts.app')

@section('title', 'Perbaiki Data Pendaftaran')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('student.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Perbaiki Data Pendaftaran</h2>
                <p class="text-gray-600 mt-1">Silakan perbaiki data yang diminta oleh panitia, lalu ajukan kembali.</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Rejection Reason Banner -->
    @if($student->validation_notes)
    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-bold text-red-800">Alasan Penolakan dari Panitia</p>
                <p class="text-sm text-red-700 mt-1">{{ $student->validation_notes }}</p>
                @if($student->validated_at)
                    <p class="text-xs text-red-500 mt-1">Ditolak pada: {{ $student->validated_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Resubmission count info -->
    @if($student->resubmission_count > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        Ini adalah pengajuan ulang ke-{{ $student->resubmission_count + 1 }}. Pastikan semua data sudah benar sebelum mengajukan kembali.
    </div>
    @endif

    <!-- Progress Checklist -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Kelengkapan Data</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @php
                $check = $validationData['validation_check'];
                $steps = [
                    'Data Pribadi'  => !isset($check['missing_data']['personal_data']),
                    'Nilai Raport'  => !isset($check['missing_data']['report_grade']),
                    'Dokumen'       => !isset($check['missing_data']['documents']),
                ];
            @endphp
            @foreach($steps as $label => $done)
            <div class="flex items-center gap-3 p-3 rounded-lg {{ $done ? 'bg-green-50' : 'bg-red-50' }}">
                @if($done)
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-green-800">{{ $label }} — Lengkap</span>
                @else
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-red-700">{{ $label }} — Perlu diperbaiki</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- === EDIT PERSONAL DATA === -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Data Pribadi
        </h3>

        <form action="{{ route('student.resubmission.update-personal', $student) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('full_name') border-red-500 @enderror">
                    @error('full_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NISN <span class="text-red-500">*</span></label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nisn') border-red-500 @enderror">
                    @error('nisn')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                    <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih --</option>
                        <option value="M" {{ old('gender', $student->gender) === 'M' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="F" {{ old('gender', $student->gender) === 'F' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $student->place_of_birth) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $student->address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah Asal</label>
                    <input type="text" name="previous_school" value="{{ old('previous_school', $student->previous_school) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                    <input type="number" name="graduation_year" value="{{ old('graduation_year', $student->graduation_year) }}"
                           min="2000" max="{{ now()->year }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KIP (jika ada)</label>
                    <input type="text" name="kip_number" value="{{ old('kip_number', $student->kip_number) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                    <select name="specialization"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                            {{ !$student->canChangeSpecialization() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            {{ !$student->canChangeSpecialization() ? 'disabled' : '' }}>
                        <option value="">-- Pilih Spesialisasi --</option>
                        <option value="tahfiz"   {{ old('specialization', $student->specialization) === 'tahfiz'   ? 'selected' : '' }}>Tahfiz</option>
                        <option value="language" {{ old('specialization', $student->specialization) === 'language' ? 'selected' : '' }}>Bahasa</option>
                        <option value="regular"  {{ old('specialization', $student->specialization) === 'regular'  ? 'selected' : '' }}>Reguler</option>
                    </select>
                    @if(!$student->canChangeSpecialization())
                        <p class="text-xs text-gray-500 mt-1">Spesialisasi tidak dapat diubah karena sudah ada nilai tes.</p>
                    @endif
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Data Pribadi
                </button>
            </div>
        </form>
    </div>

    <!-- === EDIT REPORT GRADES === -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Nilai Raport
        </h3>

        <form action="{{ route('student.resubmission.update-grade', $student) }}" method="POST">
            @csrf
            @method('PUT')
            @php $rg = $student->reportGrade; @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai PAI <span class="text-red-500">*</span></label>
                    <input type="number" name="pai_grade" step="0.01" min="0" max="100" required
                           value="{{ old('pai_grade', $rg?->pai_grade) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('pai_grade') border-red-500 @enderror">
                    @error('pai_grade')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa Indonesia <span class="text-red-500">*</span></label>
                    <input type="number" name="indonesian_grade" step="0.01" min="0" max="100" required
                           value="{{ old('indonesian_grade', $rg?->indonesian_grade) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('indonesian_grade') border-red-500 @enderror">
                    @error('indonesian_grade')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa Inggris <span class="text-red-500">*</span></label>
                    <input type="number" name="english_grade" step="0.01" min="0" max="100" required
                           value="{{ old('english_grade', $rg?->english_grade) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('english_grade') border-red-500 @enderror">
                    @error('english_grade')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Nilai Raport
                </button>
            </div>
        </form>
    </div>

    <!-- === MANAGE DOCUMENTS === -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Dokumen Pendukung
        </h3>

        <!-- Existing documents -->
        @if(count($validationData['documents']) > 0)
        <div class="space-y-3 mb-6">
            @foreach($validationData['documents'] as $doc)
            <div class="flex items-center justify-between p-4 rounded-lg border
                {{ $doc['validation_status'] === 'invalid' ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="w-8 h-8 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $doc['type_label'] }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $doc['file_name'] }}</p>
                        @if($doc['validation_status'] === 'invalid' && $doc['notes'])
                            <p class="text-xs text-red-600 font-semibold mt-0.5">⚠ {{ $doc['notes'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    {!! $doc['status_badge'] !!}
                    <a href="{{ $doc['file_url'] }}" target="_blank"
                       class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Lihat</a>
                    @if($doc['validation_status'] === 'invalid' || $doc['validation_status'] === 'pending')
                        <button onclick="showReplaceModal('{{ $doc['id'] }}', '{{ addslashes($doc['type_label']) }}')"
                                class="text-xs px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">
                            Ganti
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Upload new document -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Unggah Dokumen Baru / Tambahan</h4>
            <form action="{{ route('student.resubmission.replace-document', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Dokumen <span class="text-red-500">*</span></label>
                        <select name="document_type" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="certificate">Ijazah</option>
                            <option value="report">Raport</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File <span class="text-red-500">*</span></label>
                        <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                        <p class="text-xs text-gray-400 mt-1">PDF / JPG / PNG, maks. 5MB</p>
                    </div>
                    <div>
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Unggah Dokumen
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- === SUBMIT FOR RE-VALIDATION === -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Ajukan Ulang untuk Validasi
        </h3>

        @if($validationData['resubmission']['can_resubmit'])
            <p class="text-sm text-gray-600 mb-4">
                Setelah semua data diperbaiki, klik tombol di bawah untuk mengajukan kembali ke panitia.
                Panitia akan memeriksa dan memberikan keputusan.
            </p>

            <form action="{{ route('student.resubmission.submit', $student) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan untuk Panitia (Opsional)
                    </label>
                    <textarea name="resubmission_notes" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Jelaskan perubahan yang telah dilakukan..."></textarea>
                </div>
                <button type="submit" onclick="return confirm('Apakah Anda yakin data sudah lengkap dan benar?')"
                        class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Ajukan Ulang ke Panitia
                </button>
            </form>
        @else
            <div class="bg-yellow-50 rounded-lg p-4 text-sm text-yellow-800 text-center">
                <svg class="w-8 h-8 mx-auto text-yellow-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Data sudah diajukan dan sedang menunggu review dari panitia.
            </div>
        @endif
    </div>

</div>

<!-- Replace Document Modal -->
<div id="replaceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 text-center mb-4" id="replaceModalTitle">Ganti Dokumen</h3>
        <form id="replaceForm" action="{{ route('student.resubmission.replace-document', $student) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="document_type" id="replaceDocType">
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Mengganti: <strong id="replaceDocLabel"></strong></p>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Baru <span class="text-red-500">*</span></label>
                <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full text-sm text-gray-600">
                <p class="text-xs text-gray-400 mt-1">PDF / JPG / PNG, maks. 5MB</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeReplaceModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Ganti</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showReplaceModal(docId, docLabel) {
    document.getElementById('replaceDocLabel').textContent = docLabel;
    document.getElementById('replaceModal').classList.remove('hidden');
}
function closeReplaceModal() {
    document.getElementById('replaceModal').classList.add('hidden');
}
window.onclick = function(event) {
    const modal = document.getElementById('replaceModal');
    if (event.target === modal) closeReplaceModal();
}
</script>
@endpush
@endsection