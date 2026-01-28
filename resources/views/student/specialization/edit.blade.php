@extends('layouts.app')

@section('title', 'Ubah Peminatan')

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

    <!-- Warning Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="font-semibold text-yellow-800 mb-1">Perhatian!</h3>
                <p class="text-sm text-yellow-700">
                    Anda akan mengubah pilihan peminatan. Pastikan pilihan baru sudah tepat karena 
                    pilihan tidak dapat diubah lagi setelah mengikuti tes interview.
                </p>
            </div>
        </div>
    </div>

    <!-- Current Selection Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm text-blue-700">Pilihan saat ini:</p>
                <p class="text-lg font-bold text-blue-900">
                    @if($student->specialization === 'tahfiz')
                        🕌 Kelas Tahfiz
                    @elseif($student->specialization === 'language')
                        🌍 Kelas Bahasa
                    @else
                        📚 Kelas Reguler
                    @endif
                </p>
            </div>
        </div>
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
                <h2 class="text-2xl font-bold text-gray-800">Ubah Kelas Peminatan</h2>
                <p class="text-sm text-gray-600 mt-1">Pilih peminatan baru sesuai minat dan kemampuan Anda</p>
            </div>
            <a href="{{ route('student.specialization.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
            </a>
        </div>

        <form action="{{ route('student.specialization.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Specialization Options -->
            <div class="space-y-4 mb-6">
                <!-- Tahfiz Option -->
                <label class="relative block cursor-pointer">
                    <input type="radio" name="specialization" value="tahfiz" 
                           class="peer sr-only" 
                           {{ old('specialization', $student->specialization) === 'tahfiz' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-4xl mr-4">🕌</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Tahfiz</h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Fokus pada hafalan Al-Quran dan pemahaman ilmu agama Islam.
                                </p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Program Tahfiz 30 Juz</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Pembinaan Akhlak</span>
                                </div>
                                <div class="mt-3">
                                    <span class="text-xs text-gray-600">
                                        Kuota: {{ $quotaInfo['tahfiz']['available'] ?? 0 }} tempat tersisa ({{ $quotaInfo['tahfiz']['accepted'] ?? 0 }}/{{ $quotaInfo['tahfiz']['quota'] ?? 0 }} diterima)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Language Option -->
                <label class="relative block cursor-pointer">
                    <input type="radio" name="specialization" value="language" 
                           class="peer sr-only"
                           {{ old('specialization', $student->specialization) === 'language' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-4xl mr-4">🌍</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Bahasa</h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Fokus pada penguasaan bahasa asing (Arab & Inggris) dan komunikasi internasional.
                                </p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Bilingual Program</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Public Speaking</span>
                                </div>
                                <div class="mt-3">
                                    <span class="text-xs text-gray-600">
                                        Kuota: {{ $quotaInfo['language']['available'] ?? 0 }} tempat tersisa ({{ $quotaInfo['language']['accepted'] ?? 0 }}/{{ $quotaInfo['language']['quota'] ?? 0 }} diterima)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Regular Option -->
                <label class="relative block cursor-pointer">
                    <input type="radio" name="specialization" value="regular" 
                           class="peer sr-only"
                           {{ old('specialization', $student->specialization) === 'regular' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-4xl mr-4">📚</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelas Reguler</h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Program pembelajaran umum dengan kurikulum standar nasional.
                                </p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">Kurikulum Seimbang</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">Beragam Ekskul</span>
                                </div>
                                <div class="mt-3">
                                    <span class="text-xs text-gray-600">
                                        Kuota: {{ $quotaInfo['regular']['available'] ?? 0 }} tempat tersisa ({{ $quotaInfo['regular']['accepted'] ?? 0 }}/{{ $quotaInfo['regular']['quota'] ?? 0 }} diterima)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            @error('specialization')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

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
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-red-800">
                        <p class="font-semibold mb-1">Penting!</p>
                        <ul class="space-y-1 text-xs">
                            <li>• Pastikan pilihan baru sudah tepat</li>
                            <li>• Tidak dapat diubah setelah tes interview</li>
                            <li>• Peringkat akan dihitung ulang jika diperlukan</li>
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection