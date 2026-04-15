@extends('layouts.app')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

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
            <h1 class="text-2xl font-bold text-gray-800">
                {{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}
            </h1>
            <p class="text-gray-600 mt-1">
                {{ isset($user) ? 'Perbarui data pengguna ' . $user->name : 'Buat akun pengguna baru' }}
            </p>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
    <form method="POST"
          action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <!-- Nama -->
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name ?? '') }}"
                placeholder="Masukkan nama lengkap"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror"
                required
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email <span class="text-red-500">*</span>
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email ?? '') }}"
                placeholder="contoh@email.com"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 @enderror"
                required
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Role -->
        <div class="mb-5">
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                Role <span class="text-red-500">*</span>
            </label>
            <select
                id="role"
                name="role"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('role') border-red-400 @enderror"
                required
            >
                <option value="">Pilih Role</option>
                <option value="admin"     {{ old('role', $user->role ?? '') == 'admin'     ? 'selected' : '' }}>Admin</option>
                <option value="committee" {{ old('role', $user->role ?? '') == 'committee' ? 'selected' : '' }}>Panitia</option>
                <option value="student"   {{ old('role', $user->role ?? '') == 'student'   ? 'selected' : '' }}>Siswa</option>
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password
                @if(!isset($user))
                    <span class="text-red-500">*</span>
                @else
                    <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                @endif
            </label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="{{ isset($user) ? 'Masukkan password baru' : 'Minimal 8 karakter' }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-400 @enderror"
                {{ !isset($user) ? 'required' : '' }}
                minlength="8"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Konfirmasi Password
                @if(!isset($user))
                    <span class="text-red-500">*</span>
                @endif
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Ulangi password"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                {{ !isset($user) ? 'required' : '' }}
                minlength="8"
            >
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
            </button>
            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection