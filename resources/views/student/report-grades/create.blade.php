@extends('layouts.app')

@section('title', 'Input Nilai Raport')

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
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Input Nilai Raport</h2>
            <p class="text-gray-600">Masukkan nilai raport semester 1-5 dari SMP/MTs Anda</p>
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
                        <li>Nilai rata-rata akan dihitung secara otomatis</li>
                    </ul>
                </div>
            </div>
        </div>

        <form action="{{ route('student.report-grades.store') }}" method="POST" class="space-y-6">
            @csrf

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

            <!-- Nilai Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Nilai Mata Pelajaran</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Pendidikan Agama Islam -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pendidikan Agama Islam <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="islamic_studies" 
                                   value="{{ old('islamic_studies') }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('islamic_studies') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
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
                            <input type="number" 
                                   name="indonesian_language" 
                                   value="{{ old('indonesian_language') }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('indonesian_language') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
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
                            <input type="number" 
                                   name="english_language" 
                                   value="{{ old('english_language') }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('english_language') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 text-sm">/100</span>
                            </div>
                        </div>
                        @error('english_language')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Average Preview (Optional with JavaScript) -->
                <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nilai Rata-rata (Preview)</p>
                            <p class="text-2xl font-bold text-indigo-600" id="averagePreview">0.00</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800" id="statusBadge">Belum Lengkap</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('student.report-grades.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Calculate average in real-time
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = {
            islamic: document.querySelector('input[name="islamic_studies"]'),
            indonesian: document.querySelector('input[name="indonesian_language"]'),
            english: document.querySelector('input[name="english_language"]')
        };
        
        const averageDisplay = document.getElementById('averagePreview');
        const statusBadge = document.getElementById('statusBadge');
        
        function calculateAverage() {
            const islamic = parseFloat(inputs.islamic.value) || 0;
            const indonesian = parseFloat(inputs.indonesian.value) || 0;
            const english = parseFloat(inputs.english.value) || 0;
            
            const average = (islamic + indonesian + english) / 3;
            averageDisplay.textContent = average.toFixed(2);
            
            // Update status badge
            if (islamic === 0 || indonesian === 0 || english === 0) {
                statusBadge.textContent = 'Belum Lengkap';
                statusBadge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800';
            } else if (average >= 85) {
                statusBadge.textContent = 'Sangat Baik';
                statusBadge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
            } else if (average >= 75) {
                statusBadge.textContent = 'Baik';
                statusBadge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800';
            } else if (average >= 65) {
                statusBadge.textContent = 'Cukup';
                statusBadge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800';
            } else {
                statusBadge.textContent = 'Kurang';
                statusBadge.className = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
            }
        }
        
        // Add event listeners
        Object.values(inputs).forEach(input => {
            input.addEventListener('input', calculateAverage);
        });
    });
</script>
@endpush
@endsection