@extends('layouts.app')

@section('title', 'Daftar Siswa')

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
            <h1 class="text-2xl font-bold text-gray-800">Daftar Siswa</h1>
            <p class="text-gray-600 mt-1">Kelola data siswa yang terdaftar</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            <a href="{{ route('students.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export
            </a>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </a>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Search -->
        <div class="lg:col-span-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari nama, NISN, ID siswa, nama ortu..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>

        <!-- Status Filter -->
        <div>
            <select 
                name="status" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
                <option value="invalid" {{ request('status') == 'invalid' ? 'selected' : '' }}>Invalid</option>
            </select>
        </div>

        <!-- Gender Filter -->
        <div>
            <select 
                name="gender" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <option value="">Semua Gender</option>
                <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Laki-laki</option>
                <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <!-- Specialization Filter -->
        <div>
            <select 
                name="specialization" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <option value="">Semua Spesialisasi</option>
                <option value="tahfiz" {{ request('specialization') == 'tahfiz' ? 'selected' : '' }}>Tahfiz</option>
                <option value="language" {{ request('specialization') == 'language' ? 'selected' : '' }}>Bahasa</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="lg:col-span-5 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>
            <a href="{{ route('students.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Siswa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ayah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ibu</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">JK</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">TTL</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Umur</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sekolah Asal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Lulus</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. KIP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spesialisasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 whitespace-nowrap text-gray-900">
                        {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap font-medium text-indigo-600">
                        {{ $student->student_id }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                        {{ $student->nisn }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                        {{ $student->full_name }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                        {{ $student->father_name }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                        {{ $student->mother_name }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $student->gender == 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $student->gender == 'M' ? 'L' : 'P' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $student->place_of_birth }}, {{ $student->date_of_birth->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                        {{ $student->age }} th
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $student->address }}">
                        {{ Str::limit($student->address, 50) }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                        {{ $student->phone_number }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $student->previous_school }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                        {{ $student->graduation_year }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                        @if($student->has_kip)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                {{ $student->kip_number }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($student->specialization)
                            <span class="px-2 py-1 text-xs rounded-full {{ $student->specialization == 'tahfiz' ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800' }}">
                                {{ $student->specialization_label }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        {!! $student->status_badge !!}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('students.show', $student->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" class="text-green-600 hover:text-green-800" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data siswa {{ $student->full_name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="17" class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-lg font-medium">Tidak ada data siswa</p>
                        <p class="text-sm mt-1">Silakan tambah data siswa baru</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="text-sm text-gray-700 mb-4 md:mb-0">
                Menampilkan <span class="font-medium">{{ $students->firstItem() }}</span> - 
                <span class="font-medium">{{ $students->lastItem() }}</span> dari 
                <span class="font-medium">{{ $students->total() }}</span> data
            </div>
            <div>
                {{ $students->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection