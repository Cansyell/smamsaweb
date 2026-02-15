@extends('layouts.app')

@section('title', 'Edit Nilai Raport')

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

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-red-800 font-semibold mb-2">Terdapat {{ $errors->count() }} kesalahan:</h3>
                <ul class="list-disc list-inside text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Edit Nilai Raport</h2>
            <p class="text-gray-600">Perbarui nilai raport semester 1-5 dari SMP/MTs Anda</p>
        </div>

        <!-- Info Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Nilai yang diinput adalah rata-rata nilai dari semester 1 sampai semester 5</li>
                        <li>Rentang nilai: 0 - 100</li>
                        <li>Kolom bertanda <span class="text-red-500 font-bold">*</span> wajib diisi</li>
                        <li>Nilai rata-rata akan dihitung secara otomatis dari semua nilai yang diisi</li>
                    </ul>
                </div>
            </div>
        </div>

        <form action="{{ route('student.report-grades.update', $reportGrade) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Student Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $student->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NISN</p>
                        <p class="font-semibold text-gray-800">{{ $student->nisn }}</p>
                    </div>
                </div>
            </div>

            <!-- Mata Pelajaran Wajib -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Mata Pelajaran Wajib</h3>
                <p class="text-sm text-gray-500 mb-4">Ketiga mata pelajaran berikut wajib diisi</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Pendidikan Agama Islam -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pendidikan Agama Islam <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="islamic_studies"
                                   value="{{ old('islamic_studies', $reportGrade->islamic_studies) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('islamic_studies') border-red-500 @enderror"
                                   placeholder="0.00" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('islamic_studies')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bahasa Indonesia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bahasa Indonesia <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="indonesian_language"
                                   value="{{ old('indonesian_language', $reportGrade->indonesian_language) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('indonesian_language') border-red-500 @enderror"
                                   placeholder="0.00" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('indonesian_language')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bahasa Inggris -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bahasa Inggris <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="english_language"
                                   value="{{ old('english_language', $reportGrade->english_language) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('english_language') border-red-500 @enderror"
                                   placeholder="0.00" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('english_language')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Mata Pelajaran Opsional -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Mata Pelajaran Lainnya</h3>
                <p class="text-sm text-gray-500 mb-4">Opsional — kosongkan jika tidak tersedia di raport Anda</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- PPKn -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PPKn</label>
                        <div class="relative">
                            <input type="number" name="ppkn"
                                   value="{{ old('ppkn', $reportGrade->ppkn) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('ppkn') border-red-500 @enderror"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('ppkn')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Matematika -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Matematika</label>
                        <div class="relative">
                            <input type="number" name="mtk"
                                   value="{{ old('mtk', $reportGrade->mtk) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('mtk') border-red-500 @enderror"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('mtk')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IPA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IPA</label>
                        <div class="relative">
                            <input type="number" name="ipa"
                                   value="{{ old('ipa', $reportGrade->ipa) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('ipa') border-red-500 @enderror"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('ipa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Seni Budaya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Seni Budaya</label>
                        <div class="relative">
                            <input type="number" name="seni_budaya"
                                   value="{{ old('seni_budaya', $reportGrade->seni_budaya) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('seni_budaya') border-red-500 @enderror"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('seni_budaya')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pendidikan Jasmani -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Jasmani</label>
                        <div class="relative">
                            <input type="number" name="penjas"
                                   value="{{ old('penjas', $reportGrade->penjas) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('penjas') border-red-500 @enderror"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('penjas')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prakarya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prakarya</label>
                        <div class="relative">
                            <input type="number" name="prakarya"
                                   value="{{ old('prakarya', $reportGrade->prakarya) }}"
                                   step="0.01" min="0" max="100"
                                   class="grade-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('prakarya') border-red-500 @enderror"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('prakarya')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Average Preview -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nilai Rata-rata (Preview)</p>
                        <p class="text-2xl font-bold text-indigo-600" id="averagePreview">{{ number_format($reportGrade->average_grade, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1" id="countPreview"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" id="statusBadge">{{ $reportGrade->grade_status }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between">
                <button type="button" onclick="confirmDelete()"
                        class="px-6 py-2 border border-red-500 text-red-600 rounded-lg hover:bg-red-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus Data
                </button>
                <div class="flex items-center gap-4">
                    <a href="{{ route('student.report-grades.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Perbarui Nilai
                    </button>
                </div>
            </div>
        </form>

        <!-- Delete Form (Hidden) -->
        <form id="deleteForm" action="{{ route('student.report-grades.destroy', $reportGrade) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs        = document.querySelectorAll('.grade-input');
        const averageDisplay = document.getElementById('averagePreview');
        const countDisplay   = document.getElementById('countPreview');
        const statusBadge    = document.getElementById('statusBadge');

        function calculateAverage() {
            const values = Array.from(inputs)
                .map(i => i.value !== '' ? parseFloat(i.value) : null)
                .filter(v => v !== null);

            const count   = values.length;
            const average = count > 0 ? values.reduce((a, b) => a + b, 0) / count : 0;

            averageDisplay.textContent = average.toFixed(2);
            countDisplay.textContent   = `Dari ${count} mata pelajaran`;

            const requiredFilled = ['islamic_studies', 'indonesian_language', 'english_language']
                .every(name => document.querySelector(`[name="${name}"]`).value !== '');

            if (!requiredFilled) {
                statusBadge.textContent = 'Belum Lengkap';
                statusBadge.className   = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800';
            } else if (average >= 85) {
                statusBadge.textContent = 'Sangat Baik';
                statusBadge.className   = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
            } else if (average >= 75) {
                statusBadge.textContent = 'Baik';
                statusBadge.className   = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800';
            } else if (average >= 65) {
                statusBadge.textContent = 'Cukup';
                statusBadge.className   = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800';
            } else {
                statusBadge.textContent = 'Kurang';
                statusBadge.className   = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
            }
        }

        inputs.forEach(input => input.addEventListener('input', calculateAverage));
        calculateAverage(); // initial render
    });

    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus data nilai raport ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endpush
@endsection