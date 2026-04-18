@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $student->full_name)

@section('content')

<!-- Flash Messages -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
@endif

<!-- Header Section -->
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.students.index') }}" class="hover:text-indigo-600 transition">Daftar Siswa</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700">Detail Siswa</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Siswa</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap data pendaftar</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            <a href="{{ route('admin.students.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Identity Banner Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            <div class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold
                {{ $student->gender == 'M' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' }}">
                {{ strtoupper(substr($student->full_name, 0, 1)) }}
            </div>
        </div>

        <!-- Main Info -->
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-3 mb-1">
                <h2 class="text-xl font-bold text-gray-900">{{ $student->full_name }}</h2>
                {!! $student->status_badge !!}
                <span class="px-2 py-1 text-xs rounded-full {{ $student->gender == 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                    {{ $student->gender == 'M' ? 'Laki-laki' : 'Perempuan' }}
                </span>
                @if($student->specialization)
                    <span class="px-2 py-1 text-xs rounded-full {{ $student->specialization == 'tahfiz' ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800' }}">
                        {{ $student->specialization_label }}
                    </span>
                @endif
            </div>
            <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                    </svg>
                    ID: <span class="font-medium text-indigo-600">{{ $student->student_id }}</span>
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    NISN: <span class="font-medium">{{ $student->nisn }}</span>
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $student->phone_number }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Detail Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Data Pribadi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Data Pribadi
            </h3>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="px-6 py-3 flex justify-between items-start">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Nama Lengkap</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->full_name }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-start">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Jenis Kelamin</span>
                <span class="text-sm text-right">
                    <span class="px-2 py-1 text-xs rounded-full {{ $student->gender == 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                        {{ $student->gender == 'M' ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                </span>
            </div>
            <div class="px-6 py-3 flex justify-between items-start">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Tempat Lahir</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->place_of_birth }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-start">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Tanggal Lahir</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->date_of_birth->format('d F Y') }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-start">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Umur</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->age }} tahun</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-start">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">No. Telepon</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->phone_number }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-start">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Alamat</span>
                <span class="text-sm font-medium text-gray-900 text-right max-w-xs">{{ $student->address }}</span>
            </div>
        </div>
    </div>

    <!-- Data Orang Tua -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Data Orang Tua
            </h3>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Nama Ayah</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->father_name }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Nama Ibu</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->mother_name }}</span>
            </div>
        </div>

        <!-- Data Pendidikan -->
        <div class="px-6 py-4 bg-gray-50 border-t border-b border-gray-200 mt-4">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
                Data Pendidikan
            </h3>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Sekolah Asal</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->previous_school }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Tahun Lulus</span>
                <span class="text-sm font-medium text-gray-900 text-right">{{ $student->graduation_year }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Spesialisasi</span>
                <span class="text-sm text-right">
                    @if($student->specialization)
                        <span class="px-2 py-1 text-xs rounded-full {{ $student->specialization == 'tahfiz' ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800' }}">
                            {{ $student->specialization_label }}
                        </span>
                    @else
                        <span class="text-gray-400 text-xs">-</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

</div>

<!-- Row 2: Identitas Pendaftaran & KIP -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Identitas Pendaftaran -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Identitas Pendaftaran
            </h3>
        </div>
        <div class="divide-y divide-gray-100">
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">ID Siswa</span>
                <span class="text-sm font-semibold text-indigo-600">{{ $student->student_id }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">NISN</span>
                <span class="text-sm font-medium text-gray-900">{{ $student->nisn }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Status Validasi</span>
                <span>{!! $student->status_badge !!}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Terdaftar Pada</span>
                <span class="text-sm font-medium text-gray-900">{{ $student->created_at->format('d F Y, H:i') }}</span>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-500 w-40 flex-shrink-0">Terakhir Diperbarui</span>
                <span class="text-sm font-medium text-gray-900">{{ $student->updated_at->format('d F Y, H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- KIP -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Kartu Indonesia Pintar (KIP)
            </h3>
        </div>
        <div class="px-6 py-6">
            @if($student->has_kip)
                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-800">Memiliki KIP</p>
                        <p class="text-sm text-green-700 mt-0.5">No. KIP: <span class="font-semibold">{{ $student->kip_number }}</span></p>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tidak Memiliki KIP</p>
                        <p class="text-sm text-gray-500 mt-0.5">Siswa ini tidak mendaftar dengan KIP</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Documents Section (jika ada relasi dokumen) -->
@if($student->documents && $student->documents->count() > 0)
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Dokumen Pendukung
        </h3>
    </div>
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($student->documents as $document)
        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
           class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-indigo-400 hover:bg-indigo-50 transition group">
            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $document->document_name ?? $document->type }}</p>
                <p class="text-xs text-gray-500">Klik untuk melihat</p>
            </div>
            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection