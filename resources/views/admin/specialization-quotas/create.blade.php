@extends('layouts.app')

@section('title', 'Tambah Kuota Spesialisasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.specialization-quotas.index') }}" 
                class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Kuota Spesialisasi</h2>
                <p class="text-sm text-gray-600 mt-1">Buat kuota penerimaan siswa baru per peminatan</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.specialization-quotas.store') }}" method="POST" class="p-6">
            @csrf

            <!-- Academic Year Field -->
            <div class="mb-6">
                <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <select name="academic_year_id" 
                    id="academic_year_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('academic_year_id') border-red-500 @enderror"
                    required>
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->year }} @if($year->name) - {{ $year->name }} @endif
                            @if($year->is_active) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
                @error('academic_year_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Pilih tahun ajaran untuk kuota ini</p>
            </div>

            <!-- Quota Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tahfiz Quota -->
                <div>
                    <label for="tahfiz_quota" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Kuota Tahfiz <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                            name="tahfiz_quota" 
                            id="tahfiz_quota" 
                            value="{{ old('tahfiz_quota', 0) }}"
                            min="0" 
                            max="1000"
                            placeholder="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tahfiz_quota') border-red-500 @enderror"
                            required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 text-sm">siswa</span>
                        </div>
                    </div>
                    @error('tahfiz_quota')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Jumlah siswa program Tahfidz</p>
                </div>

                <!-- Language Quota -->
                <div>
                    <label for="language_quota" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline text-purple-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        Kuota Bahasa <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                            name="language_quota" 
                            id="language_quota" 
                            value="{{ old('language_quota', 0) }}"
                            min="0" 
                            max="1000"
                            placeholder="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('language_quota') border-red-500 @enderror"
                            required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 text-sm">siswa</span>
                        </div>
                    </div>
                    @error('language_quota')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Jumlah siswa program Bahasa</p>
                </div>
            </div>

            <!-- Total Preview -->
            <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-indigo-900">Total Kuota</span>
                    </div>
                    <span class="text-2xl font-bold text-indigo-600" id="total-quota">0</span>
                </div>
                <p class="text-xs text-indigo-700 mt-2">Total siswa yang akan diterima</p>
            </div>

            <!-- Active Status -->
            <div class="mb-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            value="1"
                            {{ old('is_active') ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    </div>
                    <div class="ml-3">
                        <label for="is_active" class="font-medium text-gray-700">Set sebagai Kuota Aktif</label>
                        <p class="text-sm text-gray-500">Jika dicentang, kuota lain dalam tahun ajaran yang sama akan otomatis dinonaktifkan</p>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Informasi Penting:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                            <li>Setiap tahun ajaran hanya dapat memiliki satu kuota aktif</li>
                            <li>Kuota yang aktif akan digunakan untuk proses penerimaan siswa baru</li>
                            <li>Pastikan jumlah kuota sesuai dengan kapasitas sekolah</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.specialization-quotas.index') }}" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Total Calculation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tahfizInput = document.getElementById('tahfiz_quota');
    const languageInput = document.getElementById('language_quota');
    const totalDisplay = document.getElementById('total-quota');

    function updateTotal() {
        const tahfiz = parseInt(tahfizInput.value) || 0;
        const language = parseInt(languageInput.value) || 0;
        const total = tahfiz + language;
        totalDisplay.textContent = total + ' siswa';
    }

    tahfizInput.addEventListener('input', updateTotal);
    languageInput.addEventListener('input', updateTotal);
    
    // Initial calculation
    updateTotal();
});
</script>
@endsection