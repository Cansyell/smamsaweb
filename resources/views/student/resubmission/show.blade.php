@extends('layouts.app')

@section('title', 'Perbaiki Data Pendaftaran')

@section('content')
@php
    $rg      = $student->reportGrade;
    $check   = $validationData['validation_check'] ?? [];
    $missing = $check['missing_data'] ?? [];

    $steps = [
        'report_grade' => ['label' => 'Nilai Raport', 'icon' => 'document-text'],
        'documents'    => ['label' => 'Dokumen',      'icon' => 'paper-clip'],
    ];

    $gradeFields = [
        'islamic_studies'     => ['label' => 'PAI',              'required' => true],
        'indonesian_language' => ['label' => 'Bahasa Indonesia',  'required' => true],
        'english_language'    => ['label' => 'Bahasa Inggris',    'required' => true],
        'ppkn'                => ['label' => 'PKn',               'required' => false],
        'mtk'                 => ['label' => 'Matematika',        'required' => false],
        'ipa'                 => ['label' => 'IPA',               'required' => false],
        'seni_budaya'         => ['label' => 'Seni Budaya',       'required' => false],
        'penjas'              => ['label' => 'Penjas',            'required' => false],
        'prakarya'            => ['label' => 'Prakarya',          'required' => false],
    ];
@endphp

<div class="max-w-4xl mx-auto space-y-6 pb-12">

    {{-- HEADER --}}
    <div class="flex items-center gap-4 pt-2">
        <a href="{{ route('student.dashboard') }}"
           class="p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Perbaiki Data Pendaftaran</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbaiki data sesuai catatan panitia, lalu ajukan kembali.</p>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <p class="text-sm font-semibold text-red-800 mb-1">Terdapat kesalahan pada form:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-700">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- REJECTION BANNER --}}
    @if($student->validation_notes)
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-5">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-red-800">Catatan Panitia — Alasan Penolakan</p>
                    <p class="text-sm text-red-700 mt-1 whitespace-pre-line">{{ $student->validation_notes }}</p>
                    @if($student->validated_at)
                        <p class="text-xs text-red-400 mt-2">
                            Ditolak pada: {{ $student->validated_at->format('d M Y, H:i') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- RESUBMISSION COUNT --}}
    @if($student->resubmission_count > 0)
        <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-sm text-amber-800">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Ini adalah pengajuan ulang ke-<strong>{{ $student->resubmission_count + 1 }}</strong>.
            Pastikan semua data sudah benar sebelum mengajukan kembali.
        </div>
    @endif

    {{-- STATUS CHECKLIST --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Status Kelengkapan Data</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @foreach($steps as $key => $step)
                @php $isDone = !isset($missing[$key]); @endphp
                <div class="flex items-center gap-3 px-4 py-3 rounded-lg
                    {{ $isDone ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    @if($isDone)
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-green-800">
                            {{ $step['label'] }} <span class="font-normal">— Lengkap</span>
                        </span>
                    @else
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-red-700">
                            {{ $step['label'] }} <span class="font-normal">— Perlu diperbaiki</span>
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- NILAI RAPORT --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h2 class="text-base font-semibold text-gray-800">Nilai Raport</h2>
            @if($rg)
                <span class="ml-auto text-xs font-medium px-2 py-1 rounded-full
                    {{ $rg->is_complete ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $rg->is_complete ? 'Lengkap' : 'Belum Lengkap' }}
                </span>
            @else
                <span class="ml-auto text-xs font-medium px-2 py-1 bg-red-100 text-red-700 rounded-full">
                    Belum Diisi
                </span>
            @endif
        </div>

        <form action="{{ route('student.resubmission.update-grade') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            {{-- Current grade summary --}}
            @if($rg)
                <div class="mb-5 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Nilai Tersimpan Saat Ini</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($gradeFields as $field => $meta)
                            @if($rg->{$field} !== null)
                                <div class="flex justify-between items-center px-3 py-2 bg-white rounded border border-gray-100 text-sm">
                                    <span class="text-gray-600">{{ $meta['label'] }}</span>
                                    <span class="font-semibold {{ (float)$rg->{$field} >= 75 ? 'text-green-700' : 'text-red-600' }}">
                                        {{ number_format($rg->{$field}, 2) }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if($rg->average_grade)
                        <div class="mt-3 flex items-center justify-between px-3 py-2 bg-indigo-50 border border-indigo-100 rounded text-sm">
                            <span class="text-indigo-700 font-medium">Rata-rata</span>
                            <span class="font-bold text-indigo-800">{{ number_format($rg->average_grade, 2) }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <p class="text-sm text-gray-600 mb-4">
                Perbarui nilai di bawah jika ada yang perlu diperbaiki.
                Nilai wajib minimal: PAI, Bahasa Indonesia, Bahasa Inggris.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($gradeFields as $field => $meta)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $meta['label'] }}
                            @if($meta['required'])
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input type="number" name="{{ $field }}"
                               step="0.01" min="0" max="100"
                               value="{{ old($field, $rg?->{$field}) }}"
                               @if($meta['required']) required @endif
                               placeholder="0–100"
                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                      focus:border-green-500 focus:ring-green-500
                                      @error($field) border-red-400 bg-red-50 @enderror">
                        @error($field)
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white
                               text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Nilai Raport
                </button>
            </div>
        </form>
    </div>

    {{-- DOKUMEN --}}
    @php
        $savedDocs      = $validationData['documents'] ?? [];
        $certLimit      = $documentLimits['certificate'] ?? ['current_count' => 0, 'limit' => 3, 'remaining' => 3];
        $reportLimit    = $documentLimits['report']      ?? ['current_count' => 0, 'limit' => 6, 'remaining' => 6];
    @endphp
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="p-2 bg-amber-100 rounded-lg">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-base font-semibold text-gray-800">Dokumen Pendukung</h2>
            @if(isset($missing['documents']))
                <span class="ml-auto text-xs font-medium px-2 py-1 bg-red-100 text-red-700 rounded-full">Perlu diperbaiki</span>
            @endif
        </div>

        <div class="p-6 space-y-5">

            {{-- Kuota ringkasan --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    <span class="text-gray-600">Ijazah</span>
                    <span class="{{ $certLimit['remaining'] > 0 ? 'text-green-700' : 'text-red-600' }} font-semibold">
                        {{ $certLimit['current_count'] }} / {{ $certLimit['limit'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    <span class="text-gray-600">Raport</span>
                    <span class="{{ $reportLimit['remaining'] > 0 ? 'text-green-700' : 'text-red-600' }} font-semibold">
                        {{ $reportLimit['current_count'] }} / {{ $reportLimit['limit'] }}
                    </span>
                </div>
            </div>

            {{-- Daftar dokumen tersimpan --}}
            @if(count($savedDocs) > 0)
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dokumen Tersimpan</p>
                    @foreach($savedDocs as $doc)
                        @php
                            $docType   = $doc['document_type']    ?? '';
                            $docLabel  = $doc['type_label']        ?? '-';
                            $docStatus = $doc['validation_status'] ?? 'pending';
                            $docNotes  = $doc['notes']             ?? '';
                            $docFile   = $doc['file_name']         ?? '';
                            $docUrl    = $doc['file_url']          ?? '#';
                            $docBadge  = $doc['status_badge']      ?? '';
                        @endphp
                        <div class="flex items-start justify-between gap-4 p-4 rounded-lg border
                            {{ $docStatus === 'invalid' ? 'border-red-200 bg-red-50'
                                : ($docStatus === 'valid'   ? 'border-green-200 bg-green-50'
                                :  'border-gray-200 bg-gray-50') }}">
                            <div class="flex items-start gap-3 min-w-0">
                                <svg class="w-8 h-8 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800">{{ $docLabel }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $docFile }}</p>
                                    @if($docStatus === 'invalid' && $docNotes)
                                        <p class="text-xs text-red-600 font-medium mt-1">
                                            <svg class="w-3 h-3 inline mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $docNotes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                {!! $docBadge !!}
                                <a href="{{ $docUrl }}" target="_blank"
                                   class="text-xs px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-md hover:bg-gray-50 transition">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Form upload dokumen baru --}}
            <div class="border-2 border-dashed border-gray-200 rounded-lg p-5 bg-gray-50">
                <p class="text-sm font-semibold text-gray-700 mb-1">Unggah Dokumen</p>
                <p class="text-xs text-gray-500 mb-4">Ijazah maks. {{ $certLimit['limit'] }} file &bull; Raport maks. {{ $reportLimit['limit'] }} file</p>
                <form action="{{ route('student.resubmission.upload-document') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Dokumen <span class="text-red-500">*</span>
                            </label>
                            <select name="document_type" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                           focus:border-amber-500 focus:ring-amber-500
                                           @error('document_type') border-red-400 @enderror">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="certificate" @selected(old('document_type') === 'certificate')
                                    {{ $certLimit['remaining'] <= 0 ? 'disabled' : '' }}>
                                    Ijazah (sisa {{ $certLimit['remaining'] }})
                                </option>
                                <option value="report" @selected(old('document_type') === 'report')
                                    {{ $reportLimit['remaining'] <= 0 ? 'disabled' : '' }}>
                                    Raport (sisa {{ $reportLimit['remaining'] }})
                                </option>
                            </select>
                            @error('document_type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                File <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full text-sm text-gray-600
                                          file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0
                                          file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700
                                          hover:file:bg-amber-100
                                          @error('file') border-red-400 @enderror">
                            <p class="text-xs text-gray-400 mt-1">PDF / JPG / PNG, maks. 5MB</p>
                            @error('file')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5
                                           bg-amber-500 text-white text-sm font-medium rounded-lg
                                           hover:bg-amber-600 transition shadow-sm">
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
    </div>

    {{-- SUBMIT RE-VALIDASI --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-base font-semibold text-gray-800">Ajukan Ulang ke Panitia</h2>
        </div>

        <div class="p-6">
            @if($validationData['resubmission']['can_resubmit'])
                <p class="text-sm text-gray-600 mb-4">
                    Setelah semua data diperbaiki, klik tombol di bawah untuk mengajukan kembali.
                    Panitia akan memeriksa dan memberikan keputusan.
                </p>
                <form action="{{ route('student.resubmission.submit') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan untuk Panitia
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="resubmission_notes" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                         focus:border-blue-500 focus:ring-blue-500
                                         @error('resubmission_notes') border-red-400 bg-red-50 @enderror"
                                  placeholder="Jelaskan perubahan yang telah Anda lakukan...">{{ old('resubmission_notes') }}</textarea>
                        @error('resubmission_notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            onclick="return confirm('Pastikan semua data sudah benar. Lanjutkan pengajuan?')"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3
                                   bg-blue-600 text-white font-semibold rounded-lg
                                   hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Ajukan Ulang ke Panitia
                    </button>
                </form>
            @else
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <svg class="w-10 h-10 text-amber-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-gray-700">Menunggu Review Panitia</p>
                    <p class="text-sm text-gray-500 mt-1">Data sudah diajukan. Harap tunggu keputusan dari panitia.</p>
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
// no modal needed
</script>
@endpush
@endsection