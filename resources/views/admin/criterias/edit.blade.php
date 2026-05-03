@extends('layouts.app')

@section('title', 'Edit Kriteria')

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
        <h2 class="text-2xl font-bold text-gray-800">Edit Kriteria</h2>
        <p class="text-sm text-gray-600 mt-1">Perbarui kriteria penilaian untuk perhitungan AHP dan SAW</p>
    </div>

    <!-- Info Badge -->
    <div class="mb-4 flex items-center gap-2 bg-indigo-50 border border-indigo-200 rounded-lg px-4 py-3">
        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"></path>
        </svg>
        <p class="text-sm text-indigo-700">
            Mengubah kode kriteria dapat memengaruhi sumber data yang sudah terhubung. Pastikan perubahan sudah sesuai.
        </p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.criterias.update', $criteria) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Kode Kriteria -->
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode Kriteria</label>
                <input type="text" name="code" id="code" value="{{ old('code', $criteria->code) }}"
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
                <input type="text" name="name" id="name" value="{{ old('name', $criteria->name) }}"
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
                    <option value="tahfiz" {{ old('specialization', $criteria->specialization) == 'tahfiz' ? 'selected' : '' }}>Tahfiz</option>
                    <option value="language" {{ old('specialization', $criteria->specialization) == 'language' ? 'selected' : '' }}>Language</option>
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
                    <option value="benefit" {{ old('attribute_type', $criteria->attribute_type) == 'benefit' ? 'selected' : '' }}>Benefit (Semakin Tinggi Semakin Baik)</option>
                    <option value="cost" {{ old('attribute_type', $criteria->attribute_type) == 'cost' ? 'selected' : '' }}>Cost (Semakin Rendah Semakin Baik)</option>
                </select>
                @error('attribute_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Benefit: nilai, hafalan, prestasi. Cost: biaya, jarak, waktu</p>
            </div>

            <!-- Sumber Data -->
            <div class="mb-4">
                <label for="data_source" class="block text-sm font-medium text-gray-700 mb-2">Sumber Data (Opsional)</label>
                <input type="text" name="data_source" id="data_source" value="{{ old('data_source', $criteria->data_source) }}"
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
                <input type="number" name="order" id="order" value="{{ old('order', $criteria->order) }}"
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
                    placeholder="Deskripsi kriteria...">{{ old('description', $criteria->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $criteria->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Aktifkan kriteria ini</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition">
                    Perbarui Kriteria
                </button>
                <a href="{{ route('admin.criterias.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    @php
        $hasWeights = $criteria->weights()->exists();
        $hasStudentValues = $criteria->studentValues()->exists();
        $hasAhpMatrices = $criteria->ahpMatricesAsRow()->exists() || $criteria->ahpMatricesAsCol()->exists();
        $isUsed = $hasWeights || $hasStudentValues || $hasAhpMatrices;
    @endphp

    <div class="mt-6 bg-white rounded-lg shadow-md p-6 border border-red-100">
        <h3 class="text-sm font-semibold text-red-600 mb-1">Zona Berbahaya</h3>
        <p class="text-sm text-gray-600 mb-4">Tindakan ini bersifat permanen dan tidak dapat dibatalkan.</p>

        @if ($isUsed)
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-3">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                </svg>
                <p class="text-sm text-red-700">
                    Kriteria ini tidak dapat dihapus karena sudah digunakan dalam perhitungan. Nonaktifkan jika tidak ingin digunakan lagi.
                </p>
            </div>
            <button type="button" disabled
                class="bg-red-200 text-red-400 px-5 py-2 rounded-lg text-sm cursor-not-allowed">
                Hapus Kriteria
            </button>
        @else
            <form action="{{ route('admin.criterias.destroy', $criteria) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus kriteria \'{{ $criteria->name }}\'? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm transition">
                    Hapus Kriteria
                </button>
            </form>
        @endif
    </div>
</div>
@endsection