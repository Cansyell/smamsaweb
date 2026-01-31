@extends('layouts.app')

@section('title', 'Hasil Perhitungan AHP')

@section('content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Hasil Perhitungan AHP</h1>
    <p class="text-gray-600">Hasil perhitungan bobot kriteria menggunakan metode Analytical Hierarchy Process (AHP)</p>
</div>

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
        <form method="GET" action="{{ route('admin.ahp-results.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
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

    @if($weights->isEmpty())
        <!-- Empty State -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Belum Ada Hasil Perhitungan</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Belum ada hasil perhitungan bobot untuk tahun akademik dan spesialisasi yang dipilih.</p>
                        <p class="mt-2">Silakan lakukan perhitungan bobot terlebih dahulu di halaman 
                            <a href="{{ route('admin.ahp-matrices.index', ['academic_year_id' => $selectedYearId, 'specialization' => $selectedSpecialization]) }}" 
                               class="font-semibold underline hover:text-yellow-900">
                                Perhitungan Kriteria
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Jumlah Kriteria Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jumlah Kriteria</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $weights->count() }}</h3>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Consistency Ratio Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Consistency Ratio</p>
                        <h3 class="text-3xl font-bold {{ $isConsistent ? 'text-green-600' : 'text-red-600' }}">
                            {{ $consistencyRatio ? number_format($consistencyRatio, 4) : '-' }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Target: ≤ 0.1</p>
                    </div>
                    <div class="bg-{{ $isConsistent ? 'green' : 'red' }}-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-{{ $isConsistent ? 'green' : 'red' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($isConsistent)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Konsistensi Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status Konsistensi</p>
                        @if($isConsistent)
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Konsisten
                            </span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                Tidak Konsisten
                            </span>
                        @endif
                    </div>
                    <div class="bg-gray-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Bobot Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Bobot</p>
                        <h3 class="text-3xl font-bold {{ abs($totalWeight - 1.0) < 0.001 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($totalWeight, 4) }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Harus = 1.0000</p>
                    </div>
                    <div class="bg-{{ abs($totalWeight - 1.0) < 0.001 ? 'green' : 'orange' }}-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-{{ abs($totalWeight - 1.0) < 0.001 ? 'green' : 'orange' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calculation Info -->
        @if($calculatedAt)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Informasi Perhitungan:</strong><br>
                        Dihitung pada: {{ $calculatedAt->format('d F Y H:i:s') }}<br>
                        @if($calculatedBy)
                            Oleh: {{ $calculatedBy->name }} ({{ $calculatedBy->email }})
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Consistency Status Alert -->
        @if(!$isConsistent)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Matriks Tidak Konsisten</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>Nilai Consistency Ratio ({{ number_format($consistencyRatio, 4) }}) melebihi batas toleransi (0.1).</p>
                        <p class="mt-1">Silakan perbaiki nilai perbandingan pada matriks AHP untuk mendapatkan hasil yang lebih konsisten.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Weights Table -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Bobot Prioritas Kriteria
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visualisasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($weights->sortByDesc('weight') as $index => $weight)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-blue-100 text-blue-800">
                                        {{ $weight->criteria->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $weight->criteria->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">
                                    {{ number_format($weight->weight, 4) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">
                                    {{ number_format($weight->weight * 100, 2) }}%
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-200 rounded-full h-8">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shadow-sm" 
                                             style="width: {{ $weight->weight * 100 }}%">
                                            {{ number_format($weight->weight * 100, 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total:</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                {{ number_format($totalWeight, 4) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                {{ number_format($totalWeight * 100, 2) }}%
                            </td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Chart Visualization -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Visualisasi Bobot Kriteria
                </h3>
            </div>
            <div class="p-6">
                <canvas id="weightChart" height="100"></canvas>
            </div>
        </div>
    @endif
@endif

<!-- Chart.js Script -->
@if(!$weights->isEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('weightChart').getContext('2d');
    
    const data = {
        labels: [
            @foreach($weights->sortByDesc('weight') as $weight)
                '{{ $weight->criteria->code }} - {{ $weight->criteria->name }}',
            @endforeach
        ],
        datasets: [{
            label: 'Bobot Kriteria',
            data: [
                @foreach($weights->sortByDesc('weight') as $weight)
                    {{ $weight->weight }},
                @endforeach
            ],
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(14, 165, 233, 0.8)',
                'rgba(34, 197, 94, 0.8)',
            ],
            borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(245, 158, 11, 1)',
                'rgba(239, 68, 68, 1)',
                'rgba(139, 92, 246, 1)',
                'rgba(236, 72, 153, 1)',
                'rgba(14, 165, 233, 1)',
                'rgba(34, 197, 94, 1)',
            ],
            borderWidth: 2
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y.toFixed(4);
                            label += ' (' + (context.parsed.y * 100).toFixed(2) + '%)';
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...data.datasets[0].data) * 1.1,
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(4);
                        }
                    },
                    title: {
                        display: true,
                        text: 'Bobot'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Kriteria'
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
});
</script>
@endif
@endsection