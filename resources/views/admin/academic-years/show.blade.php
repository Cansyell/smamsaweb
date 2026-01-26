@extends('layouts.app')

@section('title', 'Detail Tahun Ajaran')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.academic-years.index') }}" 
                    class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Detail Tahun Ajaran</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap tahun ajaran {{ $academicYear->year }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.academic-years.edit', $academicYear) }}" 
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tahun Ajaran</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $academicYear->year }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama/Label</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $academicYear->name ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Mulai</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $academicYear->start_date->format('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Selesai</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $academicYear->end_date->format('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <div class="mt-1">
                            @if($academicYear->is_active)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Durasi</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $academicYear->duration_in_days }} hari</p>
                    </div>
                </div>

                @if($academicYear->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $academicYear->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Specialization Quotas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Quota Peminatan
                    </h3>
                    <span class="px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-full">
                        {{ $academicYear->specializationQuotas->count() }} Quota
                    </span>
                </div>

                @if($academicYear->specializationQuotas->count() > 0)
                    <div class="space-y-3">
                        @foreach($academicYear->specializationQuotas as $quota)
                            <div class="border border-gray-200 rounded-lg p-5 hover:border-indigo-300 transition">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Quota Peminatan</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Tahun Ajaran {{ $academicYear->year }}
                                        </p>
                                    </div>
                                    @if($quota->is_active)
                                        <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>

                                <!-- Quota Details -->
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Tahfiz Quota -->
                                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-blue-900">Tahfiz</p>
                                                <div class="flex items-baseline gap-2 mt-1">
                                                    <p class="text-2xl font-bold text-blue-600">{{ $quota->tahfiz_quota }}</p>
                                                    <p class="text-xs text-blue-600">siswa</p>
                                                </div>
                                                <p class="text-xs text-blue-600 mt-1">{{ $quota->tahfiz_percentage }}% dari total</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Language Quota -->
                                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-purple-100 rounded-lg">
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-purple-900">Bahasa</p>
                                                <div class="flex items-baseline gap-2 mt-1">
                                                    <p class="text-2xl font-bold text-purple-600">{{ $quota->language_quota }}</p>
                                                    <p class="text-xs text-purple-600">siswa</p>
                                                </div>
                                                <p class="text-xs text-purple-600 mt-1">{{ $quota->language_percentage }}% dari total</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">Total Quota</span>
                                        <span class="text-lg font-bold text-indigo-600">{{ $quota->total_quota }} siswa</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Belum ada data quota peminatan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Statistik
                </h3>
                
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Total Siswa</p>
                                <p class="text-2xl font-bold text-blue-900 mt-1">{{ $academicYear->student_count }}</p>
                            </div>
                            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-600 font-medium">Total Quota</p>
                                <p class="text-2xl font-bold text-purple-900 mt-1">{{ $academicYear->specializationQuotas->count() }}</p>
                            </div>
                            <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600 font-medium">Durasi</p>
                                <p class="text-2xl font-bold text-green-900 mt-1">{{ $academicYear->duration_in_days }}</p>
                                <p class="text-xs text-green-600 mt-1">hari</p>
                            </div>
                            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status Waktu
                </h3>
                
                <div class="space-y-3">
                    @if($academicYear->isUpcoming())
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm font-medium text-blue-800">Akan Datang</p>
                            <p class="text-xs text-blue-600 mt-1">Tahun ajaran belum dimulai</p>
                        </div>
                    @elseif($academicYear->isCurrent())
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm font-medium text-green-800">Sedang Berjalan</p>
                            <p class="text-xs text-green-600 mt-1">Tahun ajaran sedang aktif</p>
                        </div>
                    @elseif($academicYear->isPast())
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-sm font-medium text-gray-800">Sudah Berakhir</p>
                            <p class="text-xs text-gray-600 mt-1">Tahun ajaran telah selesai</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-2">
                    <form action="{{ route('admin.academic-years.toggle-active', $academicYear) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="w-full px-4 py-2 {{ $academicYear->is_active ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition text-sm font-medium">
                            {{ $academicYear->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Tahun Ajaran
                        </button>
                    </form>
                    
                    @if(!$academicYear->is_active && $academicYear->specializationQuotas->count() == 0)
                        <form action="{{ route('admin.academic-years.destroy', $academicYear) }}" 
                            method="POST" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-sm font-medium">
                                Hapus Tahun Ajaran
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection