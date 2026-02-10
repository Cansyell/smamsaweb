@extends('layouts.app')

@section('title', 'Input Nilai Kriteria - ' . $student->full_name)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('committee.criterion-values.index', ['specialization' => $student->specialization]) }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Siswa
        </a>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Progress Input Nilai</h3>
            <span class="text-2xl font-bold text-indigo-600">{{ number_format($progress['percentage'], 0) }}%</span>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500" 
                 style="width: {{ $progress['percentage'] }}%"></div>
        </div>
        
        <p class="text-sm text-gray-600">
            {{ $progress['completed'] }} dari {{ $progress['total'] }} kriteria telah diisi
        </p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

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
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Input Nilai Kriteria</h2>
            <p class="text-gray-600">Masukkan nilai untuk setiap kriteria yang tersedia</p>
        </div>

        <!-- Student Info -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Siswa</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Lengkap</p>
                    <p class="font-semibold text-gray-800">{{ $student->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NISN</p>
                    <p class="font-semibold text-gray-800">{{ $student->nisn }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Spesialisasi</p>
                    <p class="font-semibold text-gray-800">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $student->specialization === 'tahfiz' ? 'bg-green-100 text-green-800' : ($student->specialization === 'language' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $student->specialization_label }}
                        </span>
                    </p>
                </div>
            </div>
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
                        <li>Rentang nilai: 0 - 100</li>
                        <li>Siswa ini akan dinilai untuk <strong>SEMUA kriteria test</strong> (bukan hanya kriteria spesialisasinya)</li>
                        <li>Nilai dapat diubah kembali sebelum perhitungan SAW dilakukan</li>
                        <li>Pastikan semua kriteria terisi dengan benar</li>
                        <li>Anda dapat menyimpan sebagian nilai dan melanjutkan nanti</li>
                    </ul>
                </div>
            </div>
        </div>

        <form action="{{ route('committee.criterion-values.store', $student) }}" method="POST" class="space-y-6">
            @csrf

            @php
                // Group criteria by specialization for better organization
                $groupedCriteria = $criterias->groupBy('specialization');
                $specializationLabels = [
                    'tahfiz' => 'Tahfiz',
                    'language' => 'Bahasa',
                    'regular' => 'Reguler'
                ];
                $specializationColors = [
                    'tahfiz' => 'green',
                    'language' => 'blue',
                    'regular' => 'gray'
                ];
            @endphp

            <!-- Criteria Values Section - Grouped by Specialization -->
            @foreach($groupedCriteria as $specialization => $groupCriterias)
            <div class="space-y-4">
                <!-- Specialization Header -->
                <div class="flex items-center gap-3 mb-4 pb-2 border-b-2 border-{{ $specializationColors[$specialization] }}-200">
                    <div class="flex-shrink-0">
                        <span class="px-4 py-2 rounded-lg text-sm font-bold 
                            bg-{{ $specializationColors[$specialization] }}-100 
                            text-{{ $specializationColors[$specialization] }}-800 
                            border-2 border-{{ $specializationColors[$specialization] }}-300">
                            {{ $specializationLabels[$specialization] ?? ucfirst($specialization) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Kriteria {{ $specializationLabels[$specialization] ?? ucfirst($specialization) }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $groupCriterias->count() }} kriteria 
                            @if($student->specialization === $specialization)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Spesialisasi Siswa
                            </span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Criteria Items -->
                @foreach($groupCriterias as $index => $criteria)
                @php
                    $existingValue = $existingValues->get($criteria->id);
                    $value = old('values.' . $criteria->id, $existingValue?->raw_value);
                    $note = old('notes.' . $criteria->id, $existingValue?->notes);
                @endphp
                
                <div class="border border-gray-200 rounded-lg p-5 hover:border-{{ $specializationColors[$specialization] }}-300 transition {{ $existingValue ? 'bg-green-50' : 'bg-white' }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full 
                                    bg-{{ $specializationColors[$specialization] }}-100 
                                    text-{{ $specializationColors[$specialization] }}-600 
                                    font-semibold text-sm">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800">{{ $criteria->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $criteria->code }}</p>
                                </div>
                            </div>
                            
                            @if($criteria->description)
                            <p class="text-sm text-gray-600 ml-11 mb-3">{{ $criteria->description }}</p>
                            @endif

                            <div class="ml-11 flex items-center gap-4 flex-wrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $criteria->attribute_type === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $criteria->attribute_type === 'benefit' ? 'Benefit (Semakin besar semakin baik)' : 'Cost (Semakin kecil semakin baik)' }}
                                </span>
                                
                                @if($criteria->data_source)
                                <span class="text-xs text-gray-500">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Sumber: {{ $criteria->data_source }}
                                </span>
                                @endif
                            </div>
                        </div>

                        @if($existingValue)
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Sudah Diisi
                        </span>
                        @endif
                    </div>

                    <div class="ml-11 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nilai Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="values[{{ $criteria->id }}]" 
                                       value="{{ $value }}"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $specializationColors[$specialization] }}-500 focus:border-transparent @error('values.' . $criteria->id) border-red-500 @enderror"
                                       placeholder="0.00"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 text-sm">/100</span>
                                </div>
                            </div>
                            @error('values.' . $criteria->id)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan <span class="text-gray-400 text-xs">(Opsional)</span>
                            </label>
                            <textarea name="notes[{{ $criteria->id }}]" 
                                      rows="2"
                                      maxlength="500"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $specializationColors[$specialization] }}-500 focus:border-transparent @error('notes.' . $criteria->id) border-red-500 @enderror"
                                      placeholder="Tambahkan catatan jika diperlukan...">{{ $note }}</textarea>
                            @error('notes.' . $criteria->id)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            <!-- Summary Card -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Kriteria</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $criterias->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sudah Diisi</p>
                        <p class="text-2xl font-bold text-green-600">{{ $existingValues->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Belum Diisi</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $criterias->count() - $existingValues->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kriteria Spesialisasi</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $groupedCriteria[$student->specialization]->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('committee.criterion-values.index', ['specialization' => $student->specialization]) }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Batal
                </a>
                
                <div class="flex gap-3">
                    <button type="submit" 
                            name="action"
                            value="save"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-save indicator (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[type="number"]');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                // You can add auto-save functionality here if needed
                // For now, just visual feedback
                this.classList.add('border-yellow-300');
                setTimeout(() => {
                    this.classList.remove('border-yellow-300');
                }, 300);
            });
        });
    });
</script>
@endpush
@endsection