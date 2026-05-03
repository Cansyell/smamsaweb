@extends('layouts.app')

@section('title', 'Publish Hasil Seleksi')

@section('content')
<div class="space-y-6">

    {{-- ================================================================
         HEADER
    ================================================================ --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Publikasi Hasil Seleksi</h2>
                <p class="text-gray-600 mt-1">Review ringkasan data sebelum dipublikasikan kepada siswa</p>
            </div>
            <div class="flex gap-3 flex-wrap items-center">
                {{-- Status Badge --}}
                @if($activeYear->result_status === 'published')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sudah Dipublikasikan
                        <span class="text-xs font-normal opacity-75">{{ $activeYear->published_at?->format('d M Y H:i') }}</span>
                    </span>
                @elseif($activeYear->result_status === 'reviewing')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Sedang Direview
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-100 text-gray-600 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Draft
                    </span>
                @endif

                <a href="{{ route('committee.saw-results.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm">
                    ← Kembali ke Hasil SAW
                </a>
            </div>
        </div>
    </div>

    {{-- ================================================================
         ALERT MESSAGES
    ================================================================ --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-green-700 whitespace-pre-line">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif
    @if(session('warning'))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856C18.07 19 19 17.757 19 16.243V7.757C19 6.243 18.07 5 16.918 5H7.082C5.93 5 5 6.243 5 7.757v8.486C5 17.757 5.93 19 7.062 19z"/></svg>
            <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PRASYARAT CHECK
    ================================================================ --}}
    @if(!$acceptanceDone || $summary['pending'] > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-5">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="font-semibold text-red-800 mb-2">Prasyarat belum terpenuhi</p>
                <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                    @if(!$acceptanceDone || $summary['pending'] > 0)
                        <li>Masih ada <strong>{{ $summary['pending'] }} siswa</strong> dengan status "Menunggu". Tentukan status penerimaan terlebih dahulu.</li>
                    @endif
                </ul>
                <a href="{{ route('committee.saw-results.index') }}"
                   class="inline-block mt-3 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                    → Ke Halaman Hasil SAW
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         RINGKASAN DATA
    ================================================================ --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-semibold text-gray-800">Ringkasan Data yang Akan Dipublikasikan</h3>
            <p class="text-xs text-gray-500 mt-0.5">Tahun Ajaran: {{ $activeYear->year_label ?? $activeYear->year }}</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                @php
                $summaryCards = [
                    ['label' => 'Total Siswa Valid', 'value' => $summary['total'],    'color' => 'indigo', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label' => 'Lulus',             'value' => $summary['accepted'], 'color' => 'green',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Tidak Lulus',       'value' => $summary['rejected'], 'color' => 'red',    'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Dual Pass',         'value' => $summary['dual_pass'],'color' => 'amber',  'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['label' => 'Menunggu',          'value' => $summary['pending'],  'color' => $summary['pending'] > 0 ? 'red' : 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                $colorMap = [
                    'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                    'green'  => 'bg-green-50 text-green-700 border-green-100',
                    'red'    => 'bg-red-50 text-red-700 border-red-100',
                    'amber'  => 'bg-amber-50 text-amber-700 border-amber-100',
                    'gray'   => 'bg-gray-50 text-gray-500 border-gray-100',
                ];
                @endphp
                @foreach($summaryCards as $card)
                <div class="rounded-lg border p-4 {{ $colorMap[$card['color']] }}">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                        <p class="text-xs font-medium opacity-70">{{ $card['label'] }}</p>
                    </div>
                    <p class="text-3xl font-bold">{{ $card['value'] }}</p>
                </div>
                @endforeach
            </div>

            <h4 class="text-sm font-semibold text-gray-700 mb-3">Rincian per Jalur (Lulus)</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                $jalurCards = [
                    ['label' => 'Jalur Tahfiz',  'value' => $summary['tahfiz'],   'color' => 'emerald', 'method' => 'Metode SAW',  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['label' => 'Jalur Bahasa',  'value' => $summary['language'], 'color' => 'blue',    'method' => 'Metode SAW',  'icon' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
                    ['label' => 'Jalur Regular', 'value' => $summary['regular'],  'color' => 'purple',  'method' => 'Metode FCFS', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                $jalurColors = [
                    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'badge' => 'bg-emerald-100 text-emerald-800'],
                    'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-200',    'badge' => 'bg-blue-100 text-blue-800'],
                    'purple'  => ['bg' => 'bg-purple-50',  'text' => 'text-purple-700',  'border' => 'border-purple-200',  'badge' => 'bg-purple-100 text-purple-800'],
                ];
                @endphp
                @foreach($jalurCards as $j)
                @php $jc = $jalurColors[$j['color']]; @endphp
                <div class="rounded-lg border {{ $jc['border'] }} {{ $jc['bg'] }} p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 {{ $jc['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $j['icon'] }}"/>
                            </svg>
                            <span class="text-sm font-semibold {{ $jc['text'] }}">{{ $j['label'] }}</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $jc['badge'] }}">{{ $j['method'] }}</span>
                    </div>
                    <p class="text-4xl font-bold {{ $jc['text'] }}">{{ $j['value'] }}</p>
                    <p class="text-xs opacity-60 mt-1 {{ $jc['text'] }}">siswa lulus</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ================================================================
         ALUR STATUS
    ================================================================ --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Alur Publikasi</h3>
        <div class="flex items-center gap-2 flex-wrap">
            @php
            $steps = [
                ['label' => 'Draft',          'key' => 'draft'],
                ['label' => 'Sedang Direview','key' => 'reviewing'],
                ['label' => 'Dipublikasikan', 'key' => 'published'],
            ];
            $currentStatus = $activeYear->result_status ?? 'draft';
            $statusOrder   = ['draft' => 0, 'reviewing' => 1, 'published' => 2];
            $currentOrder  = $statusOrder[$currentStatus] ?? 0;
            @endphp
            @foreach($steps as $i => $step)
                @php
                    $stepOrder = $statusOrder[$step['key']];
                    $isDone    = $stepOrder < $currentOrder;
                    $isCurrent = $step['key'] === $currentStatus;
                @endphp
                <div class="flex items-center gap-2">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $isDone ? 'bg-green-500 text-white' : ($isCurrent ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : 'bg-gray-200 text-gray-400') }}">
                            @if($isDone)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="text-xs mt-1 font-medium {{ $isCurrent ? 'text-indigo-600' : ($isDone ? 'text-green-600' : 'text-gray-400') }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                    @if(!$loop->last)
                        <div class="h-0.5 w-12 mb-4 {{ $isDone ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================================================================
         ACTION PANELS
    ================================================================ --}}
    @if($activeYear->result_status === 'published')
        {{-- ── SUDAH PUBLISHED ── --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b bg-green-50 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="font-semibold text-gray-800">Hasil Telah Dipublikasikan</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium text-gray-700">Dipublikasikan pada:</span>
                        <p class="mt-0.5">{{ $activeYear->published_at?->format('d M Y, H:i') ?? '-' }}</p>
                    </div>
                    @if($activeYear->publish_notes)
                    <div>
                        <span class="font-medium text-gray-700">Catatan publikasi:</span>
                        <p class="mt-0.5">{{ $activeYear->publish_notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-sm text-gray-500 mb-3">Siswa sudah dapat melihat hasil seleksi. Tarik publikasi hanya jika diperlukan koreksi data.</p>

                    <details class="group">
                        <summary class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition select-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Tarik Publikasi (Unpublish)
                            <svg class="w-4 h-4 ml-1 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div class="mt-4 border border-red-200 rounded-lg p-4 bg-red-50">
                            <p class="text-sm text-red-700 font-medium mb-3">⚠️ Tindakan ini akan menyembunyikan hasil dari siswa. Pastikan ada alasan yang valid.</p>
                            {{-- FIX: Hapus @method('DELETE') — route hanya mendukung POST --}}
                            <form action="{{ route('committee.publish-result.unpublish') }}" method="POST" id="unpublishForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="unpublish_reason" class="block text-sm font-medium text-gray-700 mb-1">
                                        Alasan Penarikan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="unpublish_reason" name="unpublish_reason" rows="3"
                                              placeholder="Jelaskan alasan penarikan hasil publikasi (min. 10 karakter)..."
                                              class="w-full px-3 py-2 border border-red-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('unpublish_reason') border-red-500 @enderror"
                                              required minlength="10" maxlength="500">{{ old('unpublish_reason') }}</textarea>
                                    @error('unpublish_reason')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="button" onclick="confirmUnpublish()"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                                    Tarik Publikasi
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        </div>

    @else
        {{-- ── BELUM PUBLISHED ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Panel: Set Reviewing --}}
            @if($activeYear->result_status !== 'reviewing')
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b bg-yellow-50">
                    <h3 class="font-semibold text-gray-800">Langkah 1 — Mulai Review</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Tandai bahwa data sedang dalam proses review</p>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Ubah status ke "Sedang Direview" agar panitia lain tahu bahwa data sedang diperiksa.</p>
                    <form action="{{ route('committee.publish-result.set-reviewing') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition text-sm font-medium shadow">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Mulai Review
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden opacity-60">
                <div class="px-6 py-4 border-b bg-green-50">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Langkah 1 — Selesai
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Status sudah "Sedang Direview"</p>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-400">Data saat ini sedang dalam proses review. Lanjutkan ke langkah publikasi.</p>
                </div>
            </div>
            @endif

            {{-- Panel: Publish --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden {{ ($summary['pending'] > 0 || !$acceptanceDone) ? 'opacity-60 pointer-events-none' : '' }}">
                <div class="px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="font-semibold text-gray-800">Langkah 2 — Publikasikan Hasil</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Siswa akan dapat melihat hasil setelah ini</p>
                </div>
                <div class="p-6">
                    @if($summary['pending'] > 0 || !$acceptanceDone)
                        <div class="flex items-center gap-2 text-sm text-red-600 bg-red-50 rounded-lg p-3 mb-4">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Selesaikan prasyarat terlebih dahulu sebelum publish.</span>
                        </div>
                    @endif

                    <form action="{{ route('committee.publish-result.publish') }}" method="POST" id="publishForm">
                        @csrf
                        <div class="mb-4">
                            <label for="publish_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan Publikasi <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <textarea id="publish_notes" name="publish_notes" rows="3"
                                      placeholder="Catatan tambahan untuk dokumentasi internal..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('publish_notes') border-red-500 @enderror"
                                      maxlength="1000">{{ old('publish_notes') }}</textarea>
                            @error('publish_notes')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" name="confirm" value="1" id="confirmCheckbox"
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 @error('confirm') border-red-500 @enderror">
                                <span class="text-sm text-gray-700 group-hover:text-gray-900 transition">
                                    Saya telah memeriksa seluruh data dan menyatakan bahwa hasil ini siap dipublikasikan kepada siswa.
                                </span>
                            </label>
                            @error('confirm')
                                <p class="mt-1 text-xs text-red-600 ml-7">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="button" onclick="confirmPublish()" id="publishBtn"
                                class="w-full px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow font-medium text-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Publikasikan Hasil Seleksi
                        </button>
                    </form>
                </div>
            </div>

        </div>
    @endif

    {{-- ================================================================
         CATATAN PENTING
    ================================================================ --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-5">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-2">Informasi Penting Sebelum Publikasi</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li>Setelah dipublikasikan, siswa akan mendapat notifikasi dan dapat melihat hasil seleksi mereka.</li>
                    <li>Pastikan semua data sudah benar karena proses penarikan publikasi memerlukan alasan yang jelas.</li>
                    <li>Siswa dengan status <strong>Dual Pass</strong> (★) akan mendapat saran spesialisasi berdasarkan skor SAW tertinggi.</li>
                    <li>Siswa jalur Regular menggunakan metode FCFS (First Come First Served) — tidak ada skor SAW.</li>
                </ul>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function confirmPublish() {
    const checkbox = document.getElementById('confirmCheckbox');
    if (!checkbox.checked) {
        alert('Harap centang pernyataan konfirmasi terlebih dahulu.');
        checkbox.focus();
        return;
    }

    const accepted = {{ $summary['accepted'] }};
    const rejected = {{ $summary['rejected'] }};
    const total    = {{ $summary['total'] }};

    if (!confirm(
        `Publikasikan hasil seleksi untuk ${total} siswa?\n\n` +
        `✓ Lulus        : ${accepted} siswa\n` +
        `✗ Tidak Lulus  : ${rejected} siswa\n\n` +
        `Siswa akan langsung dapat melihat hasil setelah ini.\n\nLanjutkan?`
    )) return;

    const btn = document.getElementById('publishBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memproses...`;
    document.getElementById('publishForm').submit();
}

function confirmUnpublish() {
    const reason = document.getElementById('unpublish_reason').value.trim();
    if (reason.length < 10) {
        alert('Alasan penarikan minimal 10 karakter.');
        document.getElementById('unpublish_reason').focus();
        return;
    }

    if (!confirm(
        'Tarik publikasi hasil seleksi?\n\n' +
        'Siswa tidak akan lagi bisa melihat hasil setelah ini.\n\n' +
        'Lanjutkan?'
    )) return;

    document.getElementById('unpublishForm').submit();
}
</script>
@endpush
@endsection