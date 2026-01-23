@extends('layouts.app')

@section('title', 'Matriks Perbandingan AHP')

@section('content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Matriks Perbandingan AHP</h1>
    <p class="text-gray-600">Kelola perbandingan berpasangan antar kriteria untuk perhitungan bobot</p>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
            </div>
        </div>
    </div>
@endif

@if(isset($message))
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">{{ $message }}</p>
            </div>
        </div>
    </div>
@else
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.ahp-matrices.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                <select name="academic_year_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->year }} - {{ $year->semester }}
                            @if($year->is_active) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Spesialisasi</label>
                <select name="specialization" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value="tahfiz" {{ $selectedSpecialization == 'tahfiz' ? 'selected' : '' }}>Tahfiz</option>
                    <option value="language" {{ $selectedSpecialization == 'language' ? 'selected' : '' }}>Language</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if($criterias->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Belum ada kriteria aktif untuk spesialisasi {{ ucfirst($selectedSpecialization) }}. Silakan tambahkan kriteria terlebih dahulu.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Jumlah Kriteria Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jumlah Kriteria</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $criterias->count() }}</h3>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Matriks Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status Matriks</p>
                        @if($isComplete)
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Lengkap</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Lengkap</span>
                        @endif
                    </div>
                    <div class="bg-{{ $isComplete ? 'green' : 'yellow' }}-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-{{ $isComplete ? 'green' : 'yellow' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Consistency Ratio Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Consistency Ratio</p>
                        @if($consistencyRatio !== null)
                            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($consistencyRatio, 4) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Target: ≤ 0.1</p>
                        @else
                            <h3 class="text-2xl font-bold text-gray-400">-</h3>
                        @endif
                    </div>
                    <div class="bg-{{ $consistencyRatio !== null && $consistencyRatio <= 0.1 ? 'green' : 'red' }}-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-{{ $consistencyRatio !== null && $consistencyRatio <= 0.1 ? 'green' : 'red' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aksi Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <p class="text-sm text-gray-600 mb-3">Aksi</p>
                @if($isComplete && $consistencyRatio !== null && $consistencyRatio <= 0.1)
                    <form action="{{ route('admin.ahp-matrices.calculate-weights') }}" method="POST">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                        <input type="hidden" name="specialization" value="{{ $selectedSpecialization }}">
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Hitung Bobot
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">Lengkapi matriks terlebih dahulu</p>
                @endif
            </div>
        </div>

        <!-- Comparison Scale Guide -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Panduan Skala Perbandingan AHP
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                    @foreach($comparisonScale as $value => $label)
                        <div class="flex items-center">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mr-2">{{ $value }}</span>
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-600 flex items-start">
                        <svg class="w-5 h-5 mr-2 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                        </svg>
                        <strong>Catatan:</strong> Nilai kebalikan (1/3, 1/5, dst.) digunakan jika kriteria kolom lebih penting dari kriteria baris.
                    </p>
                </div>
            </div>
        </div>

        <!-- AHP Matrix Table -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Matriks Perbandingan Berpasangan
                </h3>
                <form action="{{ route('admin.ahp-matrices.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset semua nilai perbandingan?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                    <input type="hidden" name="specialization" value="{{ $selectedSpecialization }}">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Matriks
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 150px;">Kriteria</th>
                            @foreach($criterias as $colCriteria)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 100px;">
                                    <div class="font-bold text-gray-800">{{ $colCriteria->code }}</div>
                                    <div class="text-gray-500 font-normal text-xs mt-1">{{ Str::limit($colCriteria->name, 20) }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($criterias as $rowCriteria)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold bg-gray-50">
                                    <div class="text-gray-800">{{ $rowCriteria->code }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ Str::limit($rowCriteria->name, 30) }}</div>
                                </td>
                                @foreach($criterias as $colCriteria)
                                    <td class="px-6 py-4 text-center">
                                        @if($rowCriteria->id === $colCriteria->id)
                                            <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm font-semibold">1</span>
                                        @elseif($rowCriteria->id < $colCriteria->id)
                                            <button type="button" 
                                                    class="comparison-btn px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-medium"
                                                    onclick="openComparisonModal({{ $rowCriteria->id }}, {{ $colCriteria->id }}, '{{ $rowCriteria->name }}', '{{ $colCriteria->name }}', '{{ $matrixArray[$rowCriteria->id][$colCriteria->id] ?? '' }}')">
                                                @if(isset($matrixArray[$rowCriteria->id][$colCriteria->id]))
                                                    {{ number_format($matrixArray[$rowCriteria->id][$colCriteria->id], 2) }}
                                                @else
                                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        @else
                                            @if(isset($matrixArray[$colCriteria->id][$rowCriteria->id]))
                                                <span class="text-gray-500 text-sm">{{ number_format(1 / $matrixArray[$colCriteria->id][$rowCriteria->id], 4) }}</span>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Priority Weights -->
        @if($weights)
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                        Bobot Prioritas Kriteria
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visualisasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($weights as $weight)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $weight['criteria']->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $weight['criteria']->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($weight['weight'], 4) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($weight['weight'] * 100, 2) }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full bg-gray-200 rounded-full h-6">
                                            <div class="bg-green-600 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold" style="width: {{ $weight['weight'] * 100 }}%">
                                                {{ number_format($weight['weight'] * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
@endif

<!-- Comparison Modal -->
<div id="comparisonModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-lg bg-white">
        <form action="{{ route('admin.ahp-matrices.store') }}" method="POST">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId ?? '' }}">
            <input type="hidden" name="specialization" value="{{ $selectedSpecialization ?? '' }}">
            <input type="hidden" name="criteria_row_id" id="modal_criteria_row_id">
            <input type="hidden" name="criteria_col_id" id="modal_criteria_col_id">

            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Input Nilai Perbandingan</h3>
                <button type="button" onclick="closeComparisonModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mb-6">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="text-sm text-blue-700">
                        <strong>Pertanyaan:</strong> Seberapa penting kriteria 
                        <strong id="row_criteria_name"></strong> dibandingkan dengan 
                        <strong id="col_criteria_name"></strong>?
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Perbandingan</label>
                    <select name="comparison_value" id="comparison_value" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Pilih Nilai --</option>
                        @foreach($comparisonScale ?? [] as $value => $label)
                            <option value="{{ $value }}">{{ $value }} - {{ $label }}</option>
                        @endforeach
                        <option value="0.5">1/2 - Antara sama penting dan sedikit lebih penting</option>
                        <option value="0.333">1/3 - Kurang penting</option>
                        <option value="0.25">1/4 - Antara kurang penting</option>
                        <option value="0.2">1/5 - Sangat kurang penting</option>
                        <option value="0.167">1/6 - Antara sangat kurang penting</option>
                        <option value="0.143">1/7 - Jelas kurang penting</option>
                        <option value="0.125">1/8 - Antara jelas kurang penting</option>
                        <option value="0.111">1/9 - Mutlak kurang penting</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Alasan/penjelasan perbandingan..."></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3">
                <button type="button" onclick="closeComparisonModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openComparisonModal(rowId, colId, rowName, colName, currentValue) {
    document.getElementById('modal_criteria_row_id').value = rowId;
    document.getElementById('modal_criteria_col_id').value = colId;
    document.getElementById('row_criteria_name').textContent = rowName;
    document.getElementById('col_criteria_name').textContent = colName;
    
    if (currentValue) {
        document.getElementById('comparison_value').value = currentValue;
    } else {
        document.getElementById('comparison_value').value = '';
    }
    
    document.getElementById('comparisonModal').classList.remove('hidden');
}

function closeComparisonModal() {
    document.getElementById('comparisonModal').classList.add('hidden');
    document.getElementById('comparison_value').value = '';
    document.querySelector('textarea[name="notes"]').value = '';
}

// Close modal when clicking outside
document.getElementById('comparisonModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeComparisonModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeComparisonModal();
    }
});
</script>
@endsection