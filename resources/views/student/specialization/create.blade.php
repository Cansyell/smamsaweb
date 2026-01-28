@extends('layouts.app')

@section('title', 'Pilih Peminatan')

@section('content')
<div class="space-y-6">
    <!-- Progress Bar Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Progress Pendaftaran</h3>
            <span class="text-2xl font-bold text-indigo-600">{{ number_format($progress['percentage'], 0) }}%</span>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500" 
                 style="width: {{ $progress['percentage'] }}%"></div>
        </div>
        
        <p class="text-sm text-gray-600">
            {{ $progress['completed'] }} dari {{ $progress['total'] }} langkah telah diselesaikan
        </p>
    </div>

    <!-- Recommendation Alert -->
    @if($recommendation && $recommendation['recommended'])
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex items-start">
            <div class="p-3 bg-blue-100 rounded-full mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-blue-800 mb-1">Rekomendasi Sistem</h3>
                <p class="text-sm text-blue-700">
                    Berdasarkan nilai rapor Anda, sistem merekomendasikan kelas 
                    <strong>{{ ucfirst($recommendation['recommended']) }}</strong>.
                    <br>
                    <span class="text-xs mt-1 block">{{ $recommendation['reason'] }}</span>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pilih Kelas Peminatan</h2>
                <p class="text-sm text-gray-600 mt-1">Pilih salah satu peminatan sesuai minat dan kemampuan Anda</p>
            </div>
            <a href="{{ route('student.specialization.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
            </a>
        </div>

        <form action="{{ route('student.specialization.store') }}" method="POST" id="specializationForm">
            @csrf

            <!-- Specialization Options -->
            <div class="space-y-4 mb-6">
                <!-- Tahfiz Option -->
                <label class="relative block cursor-pointer">
                    <input type="radio" name="specialization" value="tahfiz" 
                           class="peer sr-only" 
                           {{ old('specialization') === 'tahfiz' ? 'checked' : '' }}
                           {{ ($recommendation['recommended'] ?? '') === 'tahfiz' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-4xl mr-4">🕌</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Tahfiz</h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Fokus pada hafalan Al-Quran dan pemahaman ilmu agama Islam. 
                                    Cocok untuk siswa yang memiliki minat tinggi dalam bidang keagamaan.
                                </p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Program Tahfiz 30 Juz</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Pembinaan Akhlak</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Ilmu Agama Mendalam</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-xs text-gray-600">
                                        Kuota: {{ $quotaInfo['tahfiz']['available'] ?? 0 }} tempat tersisa ({{ $quotaInfo['tahfiz']['accepted'] ?? 0 }}/{{ $quotaInfo['tahfiz']['quota'] ?? 0 }} diterima)
                                    </span>
                                    @if(($recommendation['recommended'] ?? '') === 'tahfiz')
                                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded font-medium">
                                            ⭐ Direkomendasikan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Language Option -->
                <label class="relative block cursor-pointer">
                    <input type="radio" name="specialization" value="language" 
                           class="peer sr-only"
                           {{ old('specialization') === 'language' ? 'checked' : '' }}
                           {{ ($recommendation['recommended'] ?? '') === 'language' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-4xl mr-4">🌍</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Bahasa</h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Fokus pada penguasaan bahasa asing (Arab & Inggris) dan komunikasi internasional. 
                                    Cocok untuk siswa yang tertarik dengan bahasa dan budaya global.
                                </p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Bilingual Program</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Public Speaking</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Sertifikasi Internasional</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-xs text-gray-600">
                                        Kuota: {{ $quotaInfo['language']['available'] ?? 0 }} tempat tersisa ({{ $quotaInfo['language']['accepted'] ?? 0 }}/{{ $quotaInfo['language']['quota'] ?? 0 }} diterima)
                                    </span>
                                    @if(($recommendation['recommended'] ?? '') === 'language')
                                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded font-medium">
                                            ⭐ Direkomendasikan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Regular Option -->
                <label class="relative block cursor-pointer">
                    <input type="radio" name="specialization" value="regular" 
                           class="peer sr-only"
                           {{ old('specialization') === 'regular' ? 'checked' : '' }}
                           {{ ($recommendation['recommended'] ?? '') === 'regular' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-4xl mr-4">📚</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Reguler</h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Program pembelajaran umum dengan kurikulum standar nasional. 
                                    Cocok untuk siswa yang ingin mengembangkan kemampuan secara menyeluruh.
                                </p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">Kurikulum Seimbang</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">Beragam Ekskul</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">Persiapan UTBK</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-xs text-gray-600">
                                        Kuota: {{ $quotaInfo['regular']['available'] ?? 0 }} tempat tersisa ({{ $quotaInfo['regular']['accepted'] ?? 0 }}/{{ $quotaInfo['regular']['quota'] ?? 0 }} diterima)
                                    </span>
                                    @if(($recommendation['recommended'] ?? '') === 'regular')
                                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded font-medium">
                                            ⭐ Direkomendasikan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            @error('specialization')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <!-- Additional Info Section -->
            <div id="additionalInfo" class="mb-6 hidden">
                <!-- Tahfiz Info -->
                <div id="tahfizInfo" class="hidden border-t border-gray-200 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hafalan Al-Quran Saat Ini (Juz)
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quran_memorization" min="0" max="30" 
                           value="{{ old('quran_memorization') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan jumlah juz (0-30)">
                    @error('quran_memorization')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Masukkan jumlah juz Al-Quran yang sudah Anda hafal</p>
                </div>

                <!-- Language Info -->
                <div id="languageInfo" class="hidden border-t border-gray-200 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Minat Bahasa <span class="text-red-500">*</span>
                    </label>
                    <select name="language_interest" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih minat bahasa</option>
                        <option value="arabic" {{ old('language_interest') === 'arabic' ? 'selected' : '' }}>Bahasa Arab</option>
                        <option value="english" {{ old('language_interest') === 'english' ? 'selected' : '' }}>Bahasa Inggris</option>
                        <option value="both" {{ old('language_interest') === 'both' ? 'selected' : '' }}>Keduanya (Arab & Inggris)</option>
                    </select>
                    @error('language_interest')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Reason (Optional) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Memilih (Opsional)
                </label>
                <textarea name="preference_reason" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Ceritakan alasan Anda memilih peminatan ini...">{{ old('preference_reason') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Maksimal 1000 karakter</p>
                @error('preference_reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Your Grades Summary -->
            @if($student->reportGrade)
            <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Nilai Rapor Anda</h4>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">PAI</p>
                        <p class="text-xl font-bold text-green-600">{{ $student->reportGrade->pai_grade }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">B. Indonesia</p>
                        <p class="text-xl font-bold text-blue-600">{{ $student->reportGrade->indonesian_grade }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">B. Inggris</p>
                        <p class="text-xl font-bold text-purple-600">{{ $student->reportGrade->english_grade }}</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-300 text-center">
                    <p class="text-xs text-gray-600 mb-1">Rata-rata</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($student->reportGrade->average_grade, 1) }}</p>
                </div>
            </div>
            @endif

            <!-- Important Note -->
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Perhatian:</p>
                        <ul class="space-y-1 text-xs">
                            <li>• Pilihan peminatan dapat diubah sebelum tes interview</li>
                            <li>• Penempatan kelas berdasarkan peringkat dan kuota</li>
                            <li>• Pastikan pilihan Anda sudah tepat</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                <a href="{{ route('student.specialization.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pilihan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="specialization"]');
    const additionalInfo = document.getElementById('additionalInfo');
    const tahfizInfo = document.getElementById('tahfizInfo');
    const languageInfo = document.getElementById('languageInfo');

    function updateAdditionalInfo() {
        const selectedValue = document.querySelector('input[name="specialization"]:checked')?.value;
        
        // Reset all
        additionalInfo.classList.add('hidden');
        tahfizInfo.classList.add('hidden');
        languageInfo.classList.add('hidden');

        // Show relevant section
        if (selectedValue === 'tahfiz') {
            additionalInfo.classList.remove('hidden');
            tahfizInfo.classList.remove('hidden');
        } else if (selectedValue === 'language') {
            additionalInfo.classList.remove('hidden');
            languageInfo.classList.remove('hidden');
        }
    }

    radioButtons.forEach(radio => {
        radio.addEventListener('change', updateAdditionalInfo);
    });

    // Initialize on page load
    updateAdditionalInfo();
});
</script>
@endpush
@endsection