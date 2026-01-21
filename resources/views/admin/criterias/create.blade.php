@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.criterias.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Kriteria Baru</h2>
        <p class="text-sm text-gray-600 mt-1">Tambahkan kriteria penilaian untuk perhitungan AHP dan SAW</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.criterias.store') }}" method="POST">
            @csrf

            <!-- Kode Kriteria -->
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode Kriteria</label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-500 @enderror"
                    placeholder="contoh: nilai_agama, hafalan, english_score" required>
                @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Format: huruf kecil, underscore untuk spasi (snake_case)</p>
            </div>

            <!-- Nama Kriteria -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kriteria</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                    placeholder="contoh: Nilai Agama, Hafalan Al-Quran" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Spesializasi -->
            <div class="mb-4">
                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Spesializasi</label>
                <select name="specialization" id="specialization" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('specialization') border-red-500 @enderror" required>
                    <option value="">-- Pilih Spesializasi --</option>
                    <option value="tahfiz" {{ old('specialization') == 'tahfiz' ? 'selected' : '' }}>Tahfiz</option>
                    <option value="language" {{ old('specialization') == 'language' ? 'selected' : '' }}>Language</option>
                </select>
                @error('specialization')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe Atribut -->
            <div class="mb-4">
                <label for="attribute_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Atribut (SAW)</label>
                <select name="attribute_type" id="attribute_type" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('attribute_type') border-red-500 @enderror" required>
                    <option value="benefit" {{ old('attribute_type') == 'benefit' ? 'selected' : '' }}>Benefit (Semakin Tinggi Semakin Baik)</option>
                    <option value="cost" {{ old('attribute_type') == 'cost' ? 'selected' : '' }}>Cost (Semakin Rendah Semakin Baik)</option>
                </select>
                @error('attribute_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Benefit: nilai, hafalan, prestasi. Cost: biaya, jarak, waktu</p>
            </div>

            <!-- Sumber Data -->
            <div class="mb-4">
                <label for="data_source" class="block text-sm font-medium text-gray-700 mb-2">Sumber Data (Opsional)</label>
                <input type="text" name="data_source" id="data_source" value="{{ old('data_source') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('data_source') border-red-500 @enderror"
                    placeholder="contoh: report_grades.islamic_studies, test_scores.quran_reading">
                @error('data_source')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Format: nama_tabel.nama_kolom</p>
            </div>

            <!-- Urutan -->
            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampilan</label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('order') border-red-500 @enderror"
                    min="0" required>
                @error('order')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                    placeholder="Deskripsi kriteria...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Aktifkan kriteria ini</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition">
                    Simpan Kriteria
                </button>
                <a href="{{ route('admin.criterias.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
