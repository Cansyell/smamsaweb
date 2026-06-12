@extends('layouts.app')

@section('title', 'Matriks Perbandingan AHP')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Matriks Perbandingan AHP</h1>
    <p class="text-gray-500 text-sm">Kelola perbandingan berpasangan antar kriteria untuk perhitungan bobot prioritas</p>
</div>

{{-- Flash Messages --}}
@foreach(['success' => 'green', 'error' => 'red', 'warning' => 'yellow'] as $type => $color)
    @if(session($type))
        <div data-flash class="flex items-center p-4 mb-6 bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500 rounded-lg">
            <p class="text-sm font-medium text-{{ $color }}-800">{{ session($type) }}</p>
        </div>
    @endif
@endforeach

{{-- ======================================================================= --}}
{{-- TIDAK ADA TAHUN AJARAN AKTIF                                            --}}
{{-- ======================================================================= --}}
@if(! $selectedYearId)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Tahun Ajaran Aktif</h2>
        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
            {{ $message ?? 'Fitur AHP membutuhkan tahun ajaran yang aktif.' }}
        </p>
        <a href="{{ route('admin.academic-years.index') }}"
           class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            Kelola Tahun Ajaran
        </a>
    </div>

@else

{{-- ======================================================================= --}}
{{-- FILTER                                                                   --}}
{{-- ======================================================================= --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
    <form method="GET" action="{{ route('admin.ahp-matrices.index') }}"
          class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-5">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Tahun Akademik</label>
            <select name="academic_year_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    onchange="this.form.submit()">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->year }} – {{ $year->semester }}
                        @if($year->is_active) (Aktif) @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-5">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Spesialisasi</label>
            <select name="specialization"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    onchange="this.form.submit()">
                <option value="tahfiz"   {{ $selectedSpecialization === 'tahfiz'   ? 'selected' : '' }}>Tahfiz</option>
                <option value="language" {{ $selectedSpecialization === 'language' ? 'selected' : '' }}>Language</option>
            </select>
        </div>
        <div class="md:col-span-2 flex items-end">
            <button type="submit"
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                Filter
            </button>
        </div>
    </form>
</div>

{{-- ======================================================================= --}}
{{-- BELUM ADA KRITERIA                                                       --}}
{{-- ======================================================================= --}}
@if($criterias->isEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
        <h2 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Kriteria</h2>
        <p class="text-gray-500 text-sm mb-6">
            Belum ada kriteria aktif untuk spesialisasi <strong>{{ ucfirst($selectedSpecialization) }}</strong>.
        </p>
        <a href="{{ route('admin.criterias.index') }}"
           class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            Tambah Kriteria
        </a>
    </div>

@else

{{-- ======================================================================= --}}
{{-- PANDUAN SKALA (collapsible)                                             --}}
{{-- ======================================================================= --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <button type="button" onclick="toggleScale()"
            class="w-full px-5 py-3 flex items-center justify-between text-left">
        <span class="text-sm font-semibold text-gray-700">Panduan Skala Perbandingan AHP</span>
        <svg id="scale-chevron" class="w-4 h-4 text-gray-400 transition-transform duration-200"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div id="scale-body" class="hidden border-t border-gray-100 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @foreach($comparisonScale as $value => $label)
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 flex items-center justify-center bg-blue-100 text-blue-800 rounded-full text-xs font-bold flex-shrink-0">
                        {{ $value }}
                    </span>
                    <span class="text-xs text-gray-600">{{ $label }}</span>
                </div>
            @endforeach
            <div class="sm:col-span-2 lg:col-span-3 mt-1 pt-2 border-t border-gray-100">
                <span class="text-xs text-gray-400 italic">Nilai 1/2 hingga 1/9 digunakan jika kriteria kolom yang lebih penting</span>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================= --}}
{{-- TABEL 1 — INPUT (hanya isi nilai, upper-triangle saja)                 --}}
{{-- ======================================================================= --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <span class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Matriks Perbandingan Kriteria</h3>
                <p class="text-xs text-gray-400">Klik sel di segitiga atas untuk mengisi nilai perbandingan</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-500 font-mono">{{ $filledCount }}/{{ $requiredCount }} sel terisi</span>
            <form action="{{ route('admin.ahp-matrices.reset') }}" method="POST"
                  onsubmit="return confirm('Reset semua nilai matriks untuk spesialisasi {{ ucfirst($selectedSpecialization) }}?\n\nBobot yang sudah disimpan juga akan dihapus.')">
                @csrf
                @method('DELETE')
                <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                <input type="hidden" name="specialization"   value="{{ $selectedSpecialization }}">
                <button type="submit"
                        class="text-xs text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-3 py-1.5 rounded-lg transition font-medium">
                    Reset
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-amber-50">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase border border-gray-200"
                        style="min-width:160px">Kriteria</th>
                    @foreach($criterias as $col)
                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 border border-gray-200"
                            style="min-width:100px">
                            {{ $col->code }}
                            <div class="font-normal normal-case text-gray-400 mt-0.5 text-[11px]">{{ Str::limit($col->name, 16) }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($criterias as $row)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-4 py-3 bg-amber-50 border border-gray-200">
                            <p class="text-xs font-bold text-gray-800">{{ $row->code }}</p>
                            <p class="text-xs text-gray-500 font-normal">{{ Str::limit($row->name, 22) }}</p>
                        </td>
                        @foreach($criterias as $col)
                            <td class="px-3 py-3 text-center border border-gray-200">
                                @if($row->id === $col->id)
                                    {{-- Diagonal --}}
                                    <span class="inline-flex items-center justify-center w-10 h-8 bg-gray-100 text-gray-500 rounded text-xs font-bold">1</span>

                                @elseif($row->id < $col->id)
                                    {{-- Upper-triangle: tombol input --}}
                                    @php $hasValue = isset($matrixArray[$row->id][$col->id]); @endphp
                                    <button type="button"
                                            class="min-w-[64px] px-2 py-1.5 rounded text-xs font-semibold transition-all
                                                {{ $hasValue
                                                    ? 'bg-blue-50 text-blue-700 border border-blue-300 hover:bg-blue-100'
                                                    : 'bg-white text-gray-300 border border-dashed border-gray-300 hover:border-blue-300 hover:text-blue-400' }}"
                                            onclick="openModal(
                                                {{ $row->id }}, {{ $col->id }},
                                                '{{ addslashes($row->name) }}',
                                                '{{ addslashes($col->name) }}',
                                                '{{ $matrixArray[$row->id][$col->id] ?? '' }}'
                                            )">
                                        @if($hasValue)
                                            {{ number_format($matrixArray[$row->id][$col->id], 4) }}
                                        @else
                                            <svg class="w-3 h-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        @endif
                                    </button>

                                @else
                                    {{-- Lower-triangle: read-only abu-abu --}}
                                    @php $orig = $matrixArray[$col->id][$row->id] ?? null; @endphp
                                    @if($orig !== null && (float) $orig > 0)
                                        <span class="text-xs text-gray-400 font-mono">
                                            {{ number_format(1 / (float) $orig, 4) }}
                                        </span>
                                    @else
                                        <span class="text-gray-200 text-xs">—</span>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-5 py-3 border-t border-gray-100 rounded-b-lg {{ $isComplete ? 'bg-green-50' : 'bg-amber-50' }}">
        @if($isComplete)
            <p class="text-xs text-green-700 font-medium flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Matriks lengkap — lanjut ke tahap berikutnya
            </p>
        @else
            <p class="text-xs text-amber-700 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Masih ada {{ $requiredCount - $filledCount }} sel yang belum diisi
            </p>
        @endif
    </div>
</div>

{{-- ======================================================================= --}}
{{-- TOMBOL HITUNG & SIMPAN (muncul jika matriks lengkap)                   --}}
{{-- ======================================================================= --}}
@if($isComplete && ! $savedResult)
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-5">
    <div class="flex items-center gap-3 flex-wrap justify-between">
        <div class="flex items-center gap-3">
            <span class="w-7 h-7 rounded-full bg-green-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Hitung &amp; Simpan Bobot Prioritas</h3>
                <p class="text-xs text-gray-400">Sistem akan menghitung nilai konsistensi dan bobot tiap kriteria</p>
            </div>
        </div>
        <form action="{{ route('admin.ahp-matrices.calculate-weights') }}" method="POST">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
            <input type="hidden" name="specialization"   value="{{ $selectedSpecialization }}">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 active:bg-green-800 transition text-sm font-semibold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Hitung &amp; Simpan Bobot
            </button>
        </form>
    </div>
</div>
@endif

{{-- ======================================================================= --}}
{{-- HASIL KALKULASI (muncul setelah tombol diklik)                         --}}
{{-- ======================================================================= --}}
@if($savedResult)

    {{-- Info CR / Konsistensi --}}
    <div class="mb-5 p-4 rounded-lg border flex items-start gap-3
        {{ $savedResult['is_consistent'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0 {{ $savedResult['is_consistent'] ? 'text-green-600' : 'text-red-500' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @if($savedResult['is_consistent'])
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            @endif
        </svg>
        <div class="flex-1 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold {{ $savedResult['is_consistent'] ? 'text-green-800' : 'text-red-800' }}">
                    {{ $savedResult['is_consistent'] ? 'Matriks konsisten' : 'Matriks tidak konsisten' }}
                    — CR = {{ number_format($savedResult['consistency_ratio'], 4) }}
                    {{ $savedResult['is_consistent'] ? '(≤ 0.1 ✓)' : '(> 0.1 ✗)' }}
                </p>
                @if($savedResult['calculated_at'])
                    <p class="text-xs {{ $savedResult['is_consistent'] ? 'text-green-600' : 'text-red-500' }} mt-0.5">
                        Dihitung pada {{ \Carbon\Carbon::parse($savedResult['calculated_at'])->format('d M Y, H:i') }}
                    </p>
                @endif
            </div>
            @if($isComplete)
            <form action="{{ route('admin.ahp-matrices.calculate-weights') }}" method="POST">
                @csrf
                <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
                <input type="hidden" name="specialization"   value="{{ $selectedSpecialization }}">
                <button type="submit"
                        class="text-xs font-medium px-3 py-1.5 rounded-lg border transition
                            {{ $savedResult['is_consistent'] ? 'text-green-700 border-green-300 hover:bg-green-100' : 'text-red-700 border-red-300 hover:bg-red-100' }}">
                    Hitung Ulang
                </button>
            </form>
            @endif
        </div>
    </div>

    @if($metrics)

    {{-- ================================================================== --}}
    {{-- TABEL 2 — MATRIKS DESIMAL + JUMLAH PER KOLOM                      --}}
    {{-- ================================================================== --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-3">
            <span class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Matriks Perbandingan Kriteria (Desimal)</h3>
                <p class="text-xs text-gray-400 mt-0.5">Nilai pecahan dikonversi ke desimal · Baris Jumlah = total tiap kolom</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-amber-50">
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wide border border-gray-200"
                            style="min-width:140px">Kriteria</th>
                        @foreach($criterias as $col)
                            <th class="px-3 py-3 text-center font-bold text-gray-700 border border-gray-200"
                                style="min-width:120px">{{ $col->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $i => $row)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-4 py-2.5 bg-amber-50 border border-gray-200 font-bold text-gray-800">
                                {{ $row->name }}
                            </td>
                            @foreach($criterias as $j => $col)
                                <td class="px-3 py-2.5 text-center font-mono text-gray-700 border border-gray-200">
                                    {{ number_format($metrics['matrix'][$i][$j], 10) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-amber-100">
                        <td class="px-4 py-2.5 font-bold text-gray-700 uppercase tracking-wide border border-gray-200 text-xs">
                            Jumlah
                        </td>
                        @foreach($criterias as $j => $col)
                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-800 border border-gray-200">
                                {{ number_format($metrics['colSum'][$j], 10) }}
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- TABEL 3 — NORMALISASI + PRIORITAS + EIGEN                         --}}
    {{-- ================================================================== --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-3">
            <span class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</span>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Normalisasi Matriks</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    Normalisasi = nilai ÷ jumlah kolom &nbsp;·&nbsp;
                    Prioritas = rata-rata tiap baris &nbsp;·&nbsp;
                    λmax = Σ(nilai baris × prioritas kolom)
                </p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wide border border-gray-200"
                            style="min-width:140px">Kriteria</th>
                        @foreach($criterias as $col)
                            <th class="px-3 py-3 text-center font-bold text-gray-600 border border-gray-200"
                                style="min-width:115px">{{ $col->name }}</th>
                        @endforeach
                        <th class="px-3 py-3 text-center font-bold text-blue-700 border border-gray-200 bg-blue-50"
                            style="min-width:100px">Jumlah</th>
                        <th class="px-3 py-3 text-center font-bold text-green-700 border border-gray-200 bg-green-50"
                            style="min-width:100px">Prioritas</th>
                        <th class="px-3 py-3 text-center font-bold text-purple-700 border border-gray-200 bg-purple-50"
                            style="min-width:100px">Kontribusi(λmax)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $i => $row)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-4 py-2.5 bg-amber-50 border border-gray-200 font-bold text-gray-800">
                                {{ $row->name }}
                            </td>
                            @foreach($criterias as $j => $col)
                                <td class="px-3 py-2.5 text-center font-mono text-gray-600 border border-gray-200">
                                    {{ number_format($metrics['normalized'][$i][$j], 9) }}
                                </td>
                            @endforeach
                            <td class="px-3 py-2.5 text-center font-mono font-semibold text-blue-800 bg-blue-50 border border-gray-200">
                                {{ number_format($metrics['rowSum'][$i], 9) }}
                            </td>
                            <td class="px-3 py-2.5 text-center font-mono font-bold text-green-800 bg-green-50 border border-gray-200">
                                {{ number_format($metrics['prioritas'][$i], 9) }}
                            </td>
                            <td class="px-3 py-2.5 text-center font-mono font-bold text-purple-800 bg-purple-50 border border-gray-200">
                                {{ number_format($metrics['eigen'][$i], 9) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100">
                        <td class="px-4 py-2.5 font-bold text-gray-700 uppercase tracking-wide border border-gray-200 text-xs">
                            Jumlah
                        </td>
                        @foreach($criterias as $col)
                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-700 border border-gray-200">
                                1.000000000
                            </td>
                        @endforeach
                        <td class="px-3 py-2.5 bg-blue-100 border border-gray-200"></td>
                        <td class="px-3 py-2.5 text-center font-mono font-bold text-green-900 bg-green-100 border border-gray-200">
                            {{ number_format(array_sum($metrics['prioritas']), 9) }}
                        </td>
                        <td class="px-3 py-2.5 text-center font-mono font-bold text-purple-900 bg-purple-100 border border-gray-200">
                            {{ number_format($metrics['lambdaMax'], 9) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Ringkasan CI / CR di bawah tabel, persis seperti Excel --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wide mb-1">λmax</p>
                    <p class="text-sm font-bold text-gray-800 font-mono">{{ number_format($metrics['lambdaMax'], 9) }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wide mb-1">CI = (λmax − n) / (n − 1)</p>
                    <p class="text-sm font-bold text-gray-800 font-mono">{{ number_format($metrics['ci'], 9) }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wide mb-1">RI (n = {{ $metrics['n'] }})</p>
                    <p class="text-sm font-bold text-gray-800 font-mono">{{ number_format($metrics['ri'], 2) }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 uppercase tracking-wide mb-1">CR = CI / RI</p>
                    <p class="text-sm font-bold font-mono {{ $metrics['consistent'] ? 'text-green-700' : 'text-red-600' }}">
                        {{ number_format($metrics['cr'], 9) }}
                    </p>
                    <span class="mt-1 inline-block px-2 py-0.5 text-[11px] font-semibold rounded-full
                        {{ $metrics['consistent'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $metrics['consistent'] ? '✓ Konsisten (CR ≤ 0.1)' : '✗ Tidak Konsisten (CR > 0.1)' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @endif {{-- end metrics --}}

@endif {{-- end savedResult --}}

@endif {{-- end criterias.isEmpty --}}
@endif {{-- end selectedYearId --}}

{{-- ======================================================================= --}}
{{-- MODAL INPUT NILAI PERBANDINGAN                                          --}}
{{-- ======================================================================= --}}
<div id="comparisonModal"
     class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
     role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="relative top-24 mx-auto w-11/12 md:w-[480px] bg-white rounded-xl shadow-2xl border border-gray-100">
        <form action="{{ route('admin.ahp-matrices.store') }}" method="POST">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId ?? '' }}">
            <input type="hidden" name="specialization"   value="{{ $selectedSpecialization ?? '' }}">
            <input type="hidden" name="criteria_row_id"  id="modal_row_id">
            <input type="hidden" name="criteria_col_id"  id="modal_col_id">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 id="modal-title" class="text-base font-semibold text-gray-900">Nilai Perbandingan</h3>
                <button type="button" onclick="closeModal()" aria-label="Tutup"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5">
                <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 mb-5">
                    <p class="text-[11px] text-blue-400 font-semibold uppercase tracking-wide mb-1">Pertanyaan</p>
                    <p class="text-sm text-blue-900">
                        Seberapa penting
                        <strong id="modal_row_name" class="font-semibold"></strong>
                        dibandingkan dengan
                        <strong id="modal_col_name" class="font-semibold"></strong>?
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nilai Perbandingan <span class="text-red-500">*</span>
                    </label>
                    <select name="comparison_value" id="modal_value"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">— Pilih nilai —</option>
                        <optgroup label="Kriteria baris lebih penting">
                            @foreach($comparisonScale ?? [] as $v => $label)
                                <option value="{{ $v }}">{{ $v }} — {{ $label }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kriteria kolom lebih penting">
                            <option value="0.5">          1/2 — Antara sama dan sedikit kurang penting</option>
                            <option value="0.3333333333"> 1/3 — Kurang penting</option>
                            <option value="0.25">         1/4 — Antara kurang penting</option>
                            <option value="0.2">          1/5 — Sangat kurang penting</option>
                            <option value="0.1666666667"> 1/6 — Antara sangat kurang penting</option>
                            <option value="0.1428571429"> 1/7 — Jelas kurang penting</option>
                            <option value="0.125">        1/8 — Antara jelas kurang penting</option>
                            <option value="0.1111111111"> 1/9 — Mutlak kurang penting</option>
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Catatan <span class="text-gray-400 font-normal text-xs">(opsional)</span>
                    </label>
                    <textarea name="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                              placeholder="Alasan atau penjelasan..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 active:bg-blue-800 transition shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(rowId, colId, rowName, colName, currentValue) {
    document.getElementById('modal_row_id').value         = rowId;
    document.getElementById('modal_col_id').value         = colId;
    document.getElementById('modal_row_name').textContent = rowName;
    document.getElementById('modal_col_name').textContent = colName;
    document.getElementById('modal_value').value          = currentValue || '';
    document.getElementById('comparisonModal').classList.remove('hidden');
    setTimeout(() => document.getElementById('modal_value').focus(), 50);
}

function closeModal() {
    document.getElementById('comparisonModal').classList.add('hidden');
    document.getElementById('modal_value').value = '';
    const notes = document.querySelector('textarea[name="notes"]');
    if (notes) notes.value = '';
}

function toggleScale() {
    const body    = document.getElementById('scale-body');
    const chevron = document.getElementById('scale-chevron');
    body.classList.toggle('hidden');
    chevron.style.transform = body.classList.contains('hidden') ? '' : 'rotate(180deg)';
}

document.getElementById('comparisonModal').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
});

document.querySelectorAll('[data-flash]').forEach(function (el) {
    setTimeout(function () {
        el.style.transition = 'opacity 0.4s ease';
        el.style.opacity    = '0';
        setTimeout(function () { el.remove(); }, 400);
    }, 4000);
});
</script>
@endpush
@endsection