@extends('layouts.app')

@section('title', 'Hasil Seleksi PPDB')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Hasil Seleksi PPDB</h2>
                <p class="text-gray-600 mt-1">
                    Rekapitulasi hasil seleksi penerimaan peserta didik baru
                    @if($activeYear)
                        &mdash; Tahun Ajaran {{ $activeYear->name }}
                    @endif
                </p>
            </div>
            <a href="{{ route('committee.saw-results.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Total --}}
        <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total Peserta</p>
                <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="p-3 bg-indigo-100 rounded-full">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
            </div>
        </div>

        {{-- Tahfiz --}}
        <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Lulus Tahfiz</p>
                <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $stats['tahfiz'] }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>

        {{-- Bahasa --}}
        <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Lulus Bahasa</p>
                <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $stats['language'] }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
            </div>
        </div>

        {{-- Regular --}}
        <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Lulus Regular</p>
                <p class="text-3xl font-extrabold text-purple-600 mt-1">{{ $stats['regular'] }}</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                @foreach([
                    ['key' => 'all',      'label' => 'Semua',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'color' => 'indigo'],
                    ['key' => 'tahfiz',   'label' => 'Tahfiz',  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'green'],
                    ['key' => 'language', 'label' => 'Bahasa',  'icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129', 'color' => 'blue'],
                    ['key' => 'regular',  'label' => 'Regular', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'purple'],
                ] as $tab)
                <button onclick="showTab('{{ $tab['key'] }}')"
                        id="tab-{{ $tab['key'] }}"
                        class="tab-btn w-1/4 py-4 px-2 text-center border-b-2 border-transparent text-gray-500 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors focus:outline-none">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                    </svg>
                    {{ $tab['label'] }}
                </button>
                @endforeach
            </nav>
        </div>

        {{-- ===================== TAB: SEMUA ===================== --}}
        <div id="content-all" class="tab-content">
            @include('committee.selection-results.partials.table', [
                'students'      => $allStudents,
                'showBadge'     => true,
                'emptyMessage'  => 'Belum ada data peserta',
                'headerColor'   => 'from-gray-50 to-gray-100',
                'title'         => 'Semua Peserta',
                'subtitle'      => 'Menampilkan seluruh peserta beserta status dan kelas yang diterima',
            ])
        </div>

        {{-- ===================== TAB: TAHFIZ ===================== --}}
        <div id="content-tahfiz" class="tab-content hidden">
            @include('committee.selection-results.partials.table', [
                'students'      => $tahfizStudents,
                'showBadge'     => false,
                'emptyMessage'  => 'Belum ada peserta jalur Tahfiz',
                'headerColor'   => 'from-green-50 to-emerald-50',
                'title'         => 'Kelas Tahfiz',
                'subtitle'      => 'Peserta yang diterima di program Tahfiz',
            ])
        </div>

        {{-- ===================== TAB: BAHASA ===================== --}}
        <div id="content-language" class="tab-content hidden">
            @include('committee.selection-results.partials.table', [
                'students'      => $languageStudents,
                'showBadge'     => false,
                'emptyMessage'  => 'Belum ada peserta jalur Bahasa',
                'headerColor'   => 'from-blue-50 to-cyan-50',
                'title'         => 'Kelas Bahasa',
                'subtitle'      => 'Peserta yang diterima di program Bahasa',
            ])
        </div>

        {{-- ===================== TAB: REGULAR ===================== --}}
        <div id="content-regular" class="tab-content hidden">
            @include('committee.selection-results.partials.table', [
                'students'      => $regularStudents,
                'showBadge'     => false,
                'emptyMessage'  => 'Belum ada peserta jalur Regular',
                'headerColor'   => 'from-purple-50 to-pink-50',
                'title'         => 'Kelas Regular',
                'subtitle'      => 'Peserta yang diterima di program Regular',
            ])
        </div>

    </div>

    {{-- Keterangan --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-3">Keterangan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-emerald-500 text-white whitespace-nowrap">Lulus Tahfiz</span>
                <span>Diterima di kelas Tahfiz (SAW)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-500 to-cyan-500 text-white whitespace-nowrap">Lulus Bahasa</span>
                <span>Diterima di kelas Bahasa (SAW)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-purple-500 to-pink-500 text-white whitespace-nowrap">Lulus Regular</span>
                <span>Diterima di kelas Regular (FCFS)</span>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const tabColors = {
    all:      ['border-indigo-500', 'text-indigo-600'],
    tahfiz:   ['border-green-500',  'text-green-600'],
    language: ['border-blue-500',   'text-blue-600'],
    regular:  ['border-purple-500', 'text-purple-600'],
};

function showTab(name) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove(...Object.values(tabColors).flat());
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    document.getElementById('content-' + name).classList.remove('hidden');
    const btn = document.getElementById('tab-' + name);
    btn.classList.remove('border-transparent', 'text-gray-500');
    btn.classList.add(...tabColors[name]);
}

document.addEventListener('DOMContentLoaded', () => showTab('all'));
</script>
@endpush