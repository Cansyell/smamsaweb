@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<!-- Header -->
<div class="mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pengguna</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap akun pengguna</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                <span class="text-indigo-700 font-bold text-2xl">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </span>
            </div>
            <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $user->email }}</p>

            <div class="mt-3">
                @php
                    $roleConfig = [
                        'admin'     => ['bg-red-100 text-red-800',       'Admin'],
                        'committee' => ['bg-yellow-100 text-yellow-800',  'Panitia'],
                        'student'   => ['bg-blue-100 text-blue-800',      'Siswa'],
                    ];
                    [$cls, $label] = $roleConfig[$user->role] ?? ['bg-gray-100 text-gray-800', ucfirst($user->role)];
                @endphp
                <span class="px-3 py-1 text-sm rounded-full font-medium {{ $cls }}">
                    {{ $label }}
                </span>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                @if($user->email_verified_at)
                    <span class="inline-flex items-center gap-1 text-sm text-green-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Email Terverifikasi
                    </span>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $user->email_verified_at->format('d M Y, H:i') }}
                    </p>
                @else
                    <span class="inline-flex items-center gap-1 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Email Belum Terverifikasi
                    </span>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-5 flex flex-col gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Pengguna
                </a>
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $user->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Pengguna
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Info Akun -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                Informasi Akun
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">ID Pengguna</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-medium">#{{ $user->id }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Role</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs rounded-full font-medium {{ $cls }}">{{ $label }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Daftar</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Terakhir Diperbarui</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Data Siswa (jika ada) -->
        @if($user->role === 'student' && $user->student)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                Data Pendaftaran Siswa
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">ID Siswa</dt>
                    <dd class="mt-1 text-sm text-indigo-600 font-medium">{{ $user->student->student_id }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">NISN</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->student->nisn }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status Validasi</dt>
                    <dd class="mt-1">{!! $user->student->status_badge !!}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Spesialisasi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->student->specialization_label ?? '-' }}</dd>
                </div>
            </dl>
            <div class="mt-4 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.students.show', $user->student->id) }}"
                   class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Detail Siswa
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection