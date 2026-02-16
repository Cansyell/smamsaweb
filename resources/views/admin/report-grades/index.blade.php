@extends('layouts.app')

@section('title', 'Nilai Rapor Siswa')

@section('content')
{{-- Flash Messages --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
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
        <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">✕</button>
    </div>
@endif

{{-- Header --}}
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Nilai Rapor Siswa</h1>
            <p class="text-gray-600 mt-1">Rekap dan kelola nilai rapor seluruh siswa terdaftar</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.report-grades.export', request()->query()) }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Siswa --}}
    <div class="bg-white rounded-lg shadow-md p-5 flex items-center gap-4">
        <div class="p-3 rounded-full bg-indigo-100">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Siswa</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_students']) }}</p>
        </div>
    </div>

    {{-- Sudah Ada Nilai --}}
    <div class="bg-white rounded-lg shadow-md p-5 flex items-center gap-4">
        <div class="p-3 rounded-full bg-green-100">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Sudah Ada Nilai</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($stats['with_grade']) }}</p>
        </div>
    </div>

    {{-- Belum Ada Nilai --}}
    <div class="bg-white rounded-lg shadow-md p-5 flex items-center gap-4">
        <div class="p-3 rounded-full bg-yellow-100">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Belum Ada Nilai</p>
            <p class="text-2xl font-bold text-yellow-700">{{ number_format($stats['without_grade']) }}</p>
        </div>
    </div>

    {{-- Rata-rata Nilai --}}
    <div class="bg-white rounded-lg shadow-md p-5 flex items-center gap-4">
        <div class="p-3 rounded-full bg-blue-100">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Rata-rata Nilai</p>
            <p class="text-2xl font-bold text-blue-700">{{ $stats['avg_grade'] ?? '-' }}</p>
        </div>
    </div>
</div>

{{-- Progress Completion --}}
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <div class="flex justify-between items-center mb-2">
        <span class="text-sm font-medium text-gray-700">Kelengkapan Data Nilai</span>
        <span class="text-sm font-semibold text-indigo-600">{{ $stats['completion_pct'] }}%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500"
             style="width: {{ $stats['completion_pct'] }}%"></div>
    </div>
    <p class="text-xs text-gray-500 mt-1">{{ $stats['with_grade'] }} dari {{ $stats['total_students'] }} siswa telah memiliki data nilai rapor</p>
</div>

{{-- Filter --}}
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('admin.report-grades.index') }}"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">

        {{-- Search --}}
        <div class="lg:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, NISN, atau ID siswa..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>

        {{-- Tahun Ajaran --}}
        <div>
            <select name="academic_year_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                        {{ $year->year }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Spesialisasi --}}
        <div>
            <select name="specialization"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">Semua Spesialisasi</option>
                <option value="tahfiz"   {{ request('specialization') == 'tahfiz' ? 'selected' : '' }}>Tahfiz</option>
                <option value="language" {{ request('specialization') == 'language' ? 'selected' : '' }}>Bahasa</option>
                <option value="regular"  {{ request('specialization') == 'regular' ? 'selected' : '' }}>Reguler</option>
            </select>
        </div>

        {{-- Status Nilai --}}
        <div>
            <select name="grade_status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">Semua Status Nilai</option>
                <option value="has_grade"  {{ request('grade_status') == 'has_grade' ? 'selected' : '' }}>Sudah Ada Nilai</option>
                <option value="no_grade"   {{ request('grade_status') == 'no_grade' ? 'selected' : '' }}>Belum Ada Nilai</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>
            <a href="{{ route('admin.report-grades.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Siswa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesialisasi</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">PAI</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">B. Indo</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">B. Ing</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">PKn</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">MTK</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">IPA</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Seni</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Penjas</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prakarya</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Predikat</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($students as $student)
                    @php $grade = $student->reportGrade; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                            {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap font-medium text-indigo-600">
                            {{ $student->student_id }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                            {{ $student->full_name }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-500">
                            {{ $student->nisn }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($student->specialization)
                                @php
                                    $specColor = match($student->specialization) {
                                        'tahfiz'   => 'bg-purple-100 text-purple-800',
                                        'language' => 'bg-teal-100 text-teal-800',
                                        'regular'  => 'bg-gray-100 text-gray-700',
                                        default    => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $specColor }}">
                                    {{ $student->specialization_label }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        {{-- Nilai per mata pelajaran --}}
                        @foreach(['islamic_studies','indonesian_language','english_language','ppkn','mtk','ipa','seni_budaya','penjas','prakarya'] as $field)
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if($grade && $grade->$field !== null)
                                    @php
                                        $val = (float) $grade->$field;
                                        $color = $val >= 85 ? 'text-green-700 font-semibold'
                                               : ($val >= 75 ? 'text-blue-700'
                                               : ($val >= 65 ? 'text-yellow-700'
                                               : 'text-red-700'));
                                    @endphp
                                    <span class="{{ $color }}">{{ number_format($val, 1) }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        @endforeach

                        {{-- Rata-rata --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            @if($grade && $grade->average_grade)
                                @php
                                    $avg = (float) $grade->average_grade;
                                    $avgColor = $avg >= 85 ? 'text-green-700' : ($avg >= 75 ? 'text-blue-700' : ($avg >= 65 ? 'text-yellow-700' : 'text-red-700'));
                                @endphp
                                <span class="font-bold {{ $avgColor }}">{{ number_format($avg, 2) }}</span>
                            @else
                                <span class="text-gray-400 text-xs italic">Belum ada</span>
                            @endif
                        </td>

                        {{-- Predikat --}}
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            @if($grade)
                                {!! $grade->grade_badge !!}
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500">Belum Input</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.report-grades.show', $student->id) }}"
                                   title="Detail"
                                   class="text-blue-600 hover:text-blue-800 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                {{-- <a href="{{ route('admin.report-grades.edit', $student->id) }}"
                                   title="Edit Nilai"
                                   class="text-green-600 hover:text-green-800 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a> --}}
                                {{-- @if($grade)
                                    <form action="{{ route('admin.report-grades.destroy', $student->id) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Hapus nilai rapor {{ $student->full_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Nilai"
                                                class="text-red-600 hover:text-red-800 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">Tidak ada data ditemukan</p>
                            <p class="text-sm mt-1 text-gray-400">Coba ubah filter pencarian Anda</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($students->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-3">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $students->firstItem() }}</span>–<span class="font-medium">{{ $students->lastItem() }}</span>
                    dari <span class="font-medium">{{ $students->total() }}</span> siswa
                </div>
                <div>{{ $students->links() }}</div>
            </div>
        </div>
    @endif
</div>
@endsection