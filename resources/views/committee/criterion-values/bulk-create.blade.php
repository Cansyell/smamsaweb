@extends('layouts.app')

@section('title', 'Input Nilai Massal - ' . $criteria->name)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('committee.criterion-values.index', ['specialization' => $specialization]) }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Siswa
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Input Nilai Massal</h2>
                <p class="text-gray-600">Input nilai untuk satu kriteria ke banyak siswa sekaligus</p>
            </div>
        </div>
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

    <!-- Criteria Info Card -->
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $criteria->name }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $criteria->code }}</p>
                
                @if($criteria->description)
                <p class="text-sm text-gray-700 mb-3">{{ $criteria->description }}</p>
                @endif

                <div class="flex items-center gap-4 flex-wrap">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $criteria->attribute_type === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $criteria->attribute_type === 'benefit' ? 'Benefit (Semakin besar semakin baik)' : 'Cost (Semakin kecil semakin baik)' }}
                    </span>
                    
                    @if($criteria->data_source)
                    <span class="text-xs text-gray-600">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sumber: {{ $criteria->data_source }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="text-right">
                <p class="text-sm text-gray-600">Total Siswa</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $students->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Informasi Penting:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Anda dapat mengisi nilai untuk semua siswa atau hanya beberapa siswa</li>
                    <li>Rentang nilai: 0 - 100</li>
                    <li>Nilai yang kosong tidak akan disimpan</li>
                    <li>Nilai yang sudah ada akan ditimpa dengan nilai baru</li>
                    <li>Gunakan tombol "Isi Semua" untuk mengisi semua siswa dengan nilai yang sama</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('committee.criterion-values.bulk-store') }}" method="POST" id="bulkForm">
            @csrf
            <input type="hidden" name="criteria_id" value="{{ $criteria->id }}">

            <!-- Bulk Fill Helper -->
            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <div class="flex items-center gap-4">
                    <label class="text-sm font-medium text-gray-700">Isi Semua dengan Nilai:</label>
                    <input type="number" 
                           id="bulkValue" 
                           step="0.01"
                           min="0"
                           max="100"
                           class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                           placeholder="0.00">
                    <button type="button" 
                            onclick="fillAllValues()"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Terapkan ke Semua
                    </button>
                    <button type="button" 
                            onclick="clearAllValues()"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Kosongkan Semua
                    </button>
                </div>
            </div>

            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Nilai</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $index => $student)
                        @php
                            $existingValue = $student->criterionValues->where('criteria_id', $criteria->id)->first();
                        @endphp
                        <tr class="hover:bg-gray-50 value-row" data-student-id="{{ $student->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $student->nisn }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold">{{ substr($student->full_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->full_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <div class="relative flex-1 max-w-xs">
                                        <input type="number" 
                                               name="values[{{ $student->id }}]" 
                                               value="{{ old('values.' . $student->id, $existingValue?->raw_value) }}"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               class="value-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('values.' . $student->id) border-red-500 @enderror"
                                               placeholder="0.00"
                                               oninput="updateRowStatus(this)">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500 text-sm">/100</span>
                                        </div>
                                    </div>
                                </div>
                                @error('values.' . $student->id)
                                <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $existingValue ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $existingValue ? 'Sudah Ada' : 'Kosong' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800" id="totalStudents">{{ $students->count() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Sudah Ada Nilai</p>
                        <p class="text-2xl font-bold text-blue-600" id="existingCount">
                            {{ $students->filter(fn($s) => $s->criterionValues->where('criteria_id', $criteria->id)->isNotEmpty())->count() }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Akan Diisi</p>
                        <p class="text-2xl font-bold text-green-600" id="filledCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Kosong</p>
                        <p class="text-2xl font-bold text-gray-600" id="emptyCount">{{ $students->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('committee.criterion-values.index', ['specialization' => $specialization]) }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Batal
                </a>
                
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Semua Nilai
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Fill all values with bulk value
    function fillAllValues() {
        const bulkValue = document.getElementById('bulkValue').value;
        
        if (!bulkValue || bulkValue === '') {
            alert('Masukkan nilai terlebih dahulu!');
            return;
        }

        if (parseFloat(bulkValue) < 0 || parseFloat(bulkValue) > 100) {
            alert('Nilai harus antara 0 dan 100!');
            return;
        }

        const inputs = document.querySelectorAll('.value-input');
        inputs.forEach(input => {
            input.value = bulkValue;
            updateRowStatus(input);
        });

        updateSummary();
    }

    // Clear all values
    function clearAllValues() {
        if (!confirm('Apakah Anda yakin ingin mengosongkan semua nilai?')) {
            return;
        }

        const inputs = document.querySelectorAll('.value-input');
        inputs.forEach(input => {
            input.value = '';
            updateRowStatus(input);
        });

        updateSummary();
    }

    // Update row status when value changes
    function updateRowStatus(input) {
        const row = input.closest('.value-row');
        const badge = row.querySelector('.status-badge');
        const value = input.value;

        if (value && value !== '') {
            badge.className = 'status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
            badge.textContent = 'Akan Diisi';
            input.classList.remove('border-gray-300');
            input.classList.add('border-green-300', 'bg-green-50');
        } else {
            badge.className = 'status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800';
            badge.textContent = 'Kosong';
            input.classList.remove('border-green-300', 'bg-green-50');
            input.classList.add('border-gray-300');
        }
    }

    // Update summary statistics
    function updateSummary() {
        const inputs = document.querySelectorAll('.value-input');
        let filledCount = 0;
        let emptyCount = 0;

        inputs.forEach(input => {
            if (input.value && input.value !== '') {
                filledCount++;
            } else {
                emptyCount++;
            }
        });

        document.getElementById('filledCount').textContent = filledCount;
        document.getElementById('emptyCount').textContent = emptyCount;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all inputs
        const inputs = document.querySelectorAll('.value-input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                updateRowStatus(this);
                updateSummary();
            });

            // Initialize status for existing values
            if (input.value && input.value !== '') {
                updateRowStatus(input);
            }
        });

        // Initial summary update
        updateSummary();

        // Form validation before submit
        document.getElementById('bulkForm').addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('.value-input');
            let hasValue = false;

            inputs.forEach(input => {
                if (input.value && input.value !== '') {
                    hasValue = true;
                }
            });

            if (!hasValue) {
                e.preventDefault();
                alert('Minimal satu nilai harus diisi!');
                return false;
            }

            return confirm('Apakah Anda yakin ingin menyimpan semua nilai?');
        });
    });
</script>
@endpush
@endsection