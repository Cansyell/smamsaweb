@extends('layouts.app')

@section('title', 'Detail Kuota Spesialisasi')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.specialization-quotas.index') }}" 
                    class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Detail Kuota Spesialisasi</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap kuota untuk {{ $specializationQuota->academicYear->year }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.specialization-quotas.edit', $specializationQuota) }}" 
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Academic Year Header Card -->
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-md p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $specializationQuota->academicYear->year }}</h3>
                        <p class="text-indigo-100 mt-1">{{ $specializationQuota->academicYear->name ?? 'Tahun Ajaran' }}</p>
                        <div class="mt-4 flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $specializationQuota->academicYear->start_date->format('d M Y') }} - {{ $specializationQuota->academicYear->end_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($specializationQuota->is_active)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-400 text-white shadow-lg">
                                Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-white/30 text-white">
                                Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quota Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Tahfiz Quota -->
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-medium text-gray-600">Kuota Tahfiz</h4>
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $specializationQuota->tahfiz_quota }}</div>
                    <div class="text-sm text-gray-500">{{ $specializationQuota->tahfiz_percentage }}% dari total</div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-600">Program Tahfidz Al-Quran</p>
                    </div>
                </div>

                <!-- Language Quota -->
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-medium text-gray-600">Kuota Bahasa</h4>
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ $specializationQuota->language_quota }}</div>
                    <div class="text-sm text-gray-500">{{ $specializationQuota->language_percentage }}% dari total</div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-600">Program Bahasa Internasional</p>
                    </div>
                </div>

                <!-- Total Quota -->
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-indigo-500">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-medium text-gray-600">Total Kuota</h4>
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $specializationQuota->total_quota }}</div>
                    <div class="text-sm text-gray-500">Kapasitas maksimal</div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-600">Total siswa diterima</p>
                    </div>
                </div>
            </div>

            <!-- Distribution Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Distribusi Kuota Peminatan
                </h4>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex gap-1 h-12 rounded-lg overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold transition-all" 
                             style="width: {{ $specializationQuota->tahfiz_percentage }}%">
                            @if($specializationQuota->tahfiz_percentage > 20)
                                <span class="text-sm">{{ $specializationQuota->tahfiz_percentage }}%</span>
                            @endif
                        </div>
                        <div class="bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-semibold transition-all" 
                             style="width: {{ $specializationQuota->language_percentage }}%">
                            @if($specializationQuota->language_percentage > 20)
                                <span class="text-sm">{{ $specializationQuota->language_percentage }}%</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                        <div class="w-4 h-4 bg-gradient-to-br from-blue-400 to-blue-600 rounded"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Program Tahfiz</p>
                            <p class="text-xs text-gray-500">{{ $specializationQuota->tahfiz_quota }} siswa ({{ $specializationQuota->tahfiz_percentage }}%)</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                        <div class="w-4 h-4 bg-gradient-to-br from-purple-400 to-purple-600 rounded"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Program Bahasa</p>
                            <p class="text-xs text-gray-500">{{ $specializationQuota->language_quota }} siswa ({{ $specializationQuota->language_percentage }}%)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Year Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Detail Tahun Ajaran
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Periode</label>
                        <p class="mt-1 text-gray-900">
                            {{ $specializationQuota->academicYear->start_date->format('d F Y') }} - 
                            {{ $specializationQuota->academicYear->end_date->format('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Durasi</label>
                        <p class="mt-1 text-gray-900">{{ $specializationQuota->academicYear->duration_in_days }} hari</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status Tahun Ajaran</label>
                        <p class="mt-1">
                            @if($specializationQuota->academicYear->is_active)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Total Siswa Terdaftar</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $specializationQuota->academicYear->student_count }} siswa</p>
                    </div>
                    @if($specializationQuota->academicYear->description)
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="mt-1 text-gray-900">{{ $specializationQuota->academicYear->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status Kuota
                </h4>
                
                @if($specializationQuota->is_active)
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm font-medium text-green-800 mb-1">Kuota Aktif</p>
                        <p class="text-xs text-green-600">Kuota ini sedang digunakan untuk penerimaan siswa baru</p>
                    </div>
                @else
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-sm font-medium text-gray-800 mb-1">Kuota Tidak Aktif</p>
                        <p class="text-xs text-gray-600">Kuota ini tidak digunakan untuk penerimaan</p>
                    </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Ringkasan
                </h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Total Kapasitas</span>
                        <span class="font-bold text-indigo-600">{{ $specializationQuota->total_quota }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Tahfiz</span>
                        <span class="font-bold text-blue-600">{{ $specializationQuota->tahfiz_quota }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Bahasa</span>
                        <span class="font-bold text-purple-600">{{ $specializationQuota->language_quota }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h4>
                
                <div class="space-y-2">
                    <form action="{{ route('admin.specialization-quotas.toggle-active', $specializationQuota) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="w-full px-4 py-2 {{ $specializationQuota->is_active ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition text-sm font-medium">
                            {{ $specializationQuota->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Kuota
                        </button>
                    </form>
                    
                    @if(!$specializationQuota->is_active)
                        <form action="{{ route('admin.specialization-quotas.destroy', $specializationQuota) }}" 
                            method="POST" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuota ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-sm font-medium">
                                Hapus Kuota
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Sistem</h4>
                <div class="space-y-3 text-sm">
                    <div>
                        <label class="text-gray-500">Dibuat pada:</label>
                        <p class="text-gray-900 font-medium">{{ $specializationQuota->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500">Terakhir diupdate:</label>
                        <p class="text-gray-900 font-medium">{{ $specializationQuota->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection