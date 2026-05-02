@extends('layouts.app')

@section('title', 'Pengaturan Welcome Page')

@section('content')

@if(session('success'))
<div id="flash-success" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium mb-5 bg-green-50 text-green-700 border border-green-200">
    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <span class="flex-1">{{ session('success') }}</span>
    <button onclick="this.closest('div').remove()" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
</div>
@endif

@if(session('error'))
<div id="flash-error" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium mb-5 bg-red-50 text-red-700 border border-red-200">
    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <span class="flex-1">{{ session('error') }}</span>
    <button onclick="this.closest('div').remove()" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
</div>
@endif

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Welcome Page</h1>
        <p class="text-gray-600 mt-1">SMA Muhammadiyah 1 Purwokerto &mdash; T.A. {{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}</p>
    </div>
    <a href="{{ route('home') }}" target="_blank"
       class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        Preview
    </a>
</div>

{{-- ── TAB NAVIGATION (Horizontal, Scrollable, Mobile-Friendly) ── --}}
<div class="bg-white rounded-lg shadow-md mb-6">
    {{-- Scrollable tab bar --}}
    <div class="relative">
        {{-- Fade gradient kanan (hint scroll) --}}
        <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent rounded-tr-lg rounded-br-lg z-10 hidden" id="tab-fade-right"></div>

        <nav id="tab-nav"
             class="flex overflow-x-auto scrollbar-hide border-b border-gray-100 scroll-smooth"
             style="-webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;">

            @php
            $navItems = [
                [
                    'key'   => 'hero',
                    'label' => 'Hero Section',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                ],
                [
                    'key'   => 'galeri',
                    'label' => 'Galeri',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                ],
                [
                    'key'   => 'jadwal',
                    'label' => 'Jadwal PPDB',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                ],
                [
                    'key'   => 'persyaratan',
                    'label' => 'Persyaratan',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                ],
                [
                    'key'   => 'biaya',
                    'label' => 'Biaya',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'key'   => 'kontak',
                    'label' => 'Setting PPDB',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                ],
            ];
            @endphp

            @foreach($navItems as $nav)
            <button type="button"
                    data-tab="{{ $nav['key'] }}"
                    onclick="switchTab('{{ $nav['key'] }}', this)"
                    class="tab-btn flex items-center gap-2 px-5 py-3.5 text-sm font-medium whitespace-nowrap border-b-2 transition-all select-none shrink-0
                           {{ $nav['key'] === 'hero'
                               ? 'border-indigo-600 text-indigo-600 bg-indigo-50/60'
                               : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $nav['icon'] !!}
                </svg>
                <span>{{ $nav['label'] }}</span>
            </button>
            @endforeach

        </nav>
    </div>
</div>

{{-- ── TAB PANELS ── --}}

{{-- PANEL: HERO --}}
<div class="tab-panel block" id="panel-hero">
    <form method="POST"
          action="{{ $hero ? route('admin.hero.update', $hero) : route('admin.hero.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if($hero) @method('PUT') @endif

        <div class="bg-white rounded-lg shadow-md mb-5">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Konten Hero</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Teks, tombol, dan gambar latar</p>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700">Aktif</span>
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $hero?->is_active ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                </label>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Badge <span class="text-red-500">*</span></label>
                        <input type="text" name="badge_text"
                               value="{{ old('badge_text', $hero?->badge_text) }}"
                               placeholder="PPDB Tahun Ajaran 2025/2026"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('badge_text') border-red-400 @enderror">
                        @error('badge_text')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama <span class="text-red-500">*</span></label>
                        <input type="text" name="title_main"
                               value="{{ old('title_main', $hero?->title_main) }}"
                               placeholder="Wujudkan Mimpi"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title_main') border-red-400 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Teks besar putih</p>
                        @error('title_main')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Italic <span class="text-red-500">*</span></label>
                        <input type="text" name="title_italic"
                               value="{{ old('title_italic', $hero?->title_italic) }}"
                               placeholder="Bersama Kami"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title_italic') border-red-400 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Teks emas (italic)</p>
                        @error('title_italic')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul / Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="subtitle" rows="3"
                                  placeholder="Deskripsi singkat sekolah…"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('subtitle') border-red-400 @enderror">{{ old('subtitle', $hero?->subtitle) }}</textarea>
                        @error('subtitle')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label Tombol Utama <span class="text-red-500">*</span></label>
                        <input type="text" name="btn_primary_label"
                               value="{{ old('btn_primary_label', $hero?->btn_primary_label) }}"
                               placeholder="Info Pendaftaran"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Tombol Utama <span class="text-red-500">*</span></label>
                        <input type="text" name="btn_primary_url"
                               value="{{ old('btn_primary_url', $hero?->btn_primary_url) }}"
                               placeholder="#ppdb"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label Tombol Outline</label>
                        <input type="text" name="btn_outline_label"
                               value="{{ old('btn_outline_label', $hero?->btn_outline_label) }}"
                               placeholder="Kenali Sekolah Kami"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Tombol Outline</label>
                        <input type="text" name="btn_outline_url"
                               value="{{ old('btn_outline_url', $hero?->btn_outline_url) }}"
                               placeholder="#visi-misi"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Gambar Background</p>
                    <div class="flex gap-5 flex-wrap">
                        <div class="w-64 shrink-0">
                            @if($hero?->background_image)
                                <img src="{{ Storage::url($hero->background_image) }}"
                                     id="hero-img-preview"
                                     class="w-full h-40 object-cover rounded-lg border border-gray-200"
                                     alt="Hero BG">
                            @else
                                <img id="hero-img-preview" class="hidden w-full h-40 object-cover rounded-lg border border-gray-200" alt="Preview">
                            @endif
                            <div id="hero-img-placeholder"
                                 onclick="document.getElementById('hero_bg_input').click()"
                                 class="{{ $hero?->background_image ? 'hidden' : '' }} w-full h-40 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center gap-2 text-gray-400 cursor-pointer hover:border-indigo-400 hover:text-indigo-500 transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs text-center">Klik untuk pilih gambar<br><span class="text-gray-300">JPG, PNG, WebP – maks 4MB</span></span>
                            </div>
                            <input type="file" id="hero_bg_input" name="background_image" accept="image/*" class="hidden" onchange="previewHeroImg(this)">
                            @if($hero?->background_image)
                            <button type="button" onclick="document.getElementById('hero_bg_input').click()"
                                    class="mt-2 w-full text-center text-xs text-indigo-600 hover:underline">Ganti gambar</button>
                            <label class="flex items-center gap-2 mt-1.5 cursor-pointer text-red-500 text-xs">
                                <input type="checkbox" name="remove_background_image" value="1" class="rounded">
                                Hapus gambar background
                            </label>
                            @endif
                        </div>
                        <div class="flex-1 min-w-40 text-sm text-gray-400 leading-relaxed pt-2">
                            <p>Gambar latar belakang hero (dengan overlay gelap).</p>
                            <p class="mt-1">Rekomendasi: <strong class="text-gray-600">1920 × 1080 px</strong></p>
                            <p class="mt-1">Biarkan kosong untuk mempertahankan gambar saat ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md mb-5">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Statistik Hero</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Angka-angka yang tampil di hero (cth: 3.200+ Alumni)</p>
                </div>
                <button type="button" onclick="addStatRow()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 w-8"></th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Angka</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Label</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase w-28">Urutan</th>
                            <th class="px-4 py-3 w-12"></th>
                        </tr>
                    </thead>
                    <tbody id="stats-tbody">
                        @forelse($hero?->stats ?? [] as $i => $stat)
                        <tr id="stat-row-{{ $i }}" class="border-t border-gray-50 hover:bg-indigo-50/40">
                            <td class="px-4 py-3 text-center text-gray-300 cursor-grab">⠿</td>
                            <td class="px-4 py-3">
                                <input type="hidden" name="stats[{{ $i }}][id]" value="{{ $stat->id }}">
                                <input type="text" name="stats[{{ $i }}][number]" value="{{ $stat->number }}" placeholder="3.200+"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="stats[{{ $i }}][label]" value="{{ $stat->label }}" placeholder="Alumni Berprestasi"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="stats[{{ $i }}][urutan]" value="{{ $stat->urutan }}" placeholder="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </td>
                            <td class="px-4 py-3">
                                <button type="button" onclick="removeStatRow('stat-row-{{ $i }}')"
                                        class="text-red-400 hover:text-red-600 hover:bg-gray-100 rounded-lg p-1.5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="stats-empty">
                            <td colspan="5" class="py-10 text-center text-sm text-gray-400">
                                Belum ada statistik — klik <strong>Tambah</strong> untuk menambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                <button type="reset" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Reset</button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Hero Section
                </button>
            </div>
        </div>
    </form>
</div>

{{-- PANEL: GALERI --}}
<div class="tab-panel hidden" id="panel-galeri">
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-800">Item Galeri</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola foto dan video halaman publik</p>
            </div>
            <button type="button" onclick="openModal('modal-galeri-add')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Item
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Urutan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galeri as $item)
                    <tr class="border-t border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $item->id }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($item->tipe === 'foto')
                                    <img src="{{ $item->gambar_url ?? asset($item->gambar_path) }}"
                                         class="w-10 h-10 rounded-lg object-cover border border-gray-200 shrink-0"
                                         alt="{{ $item->alt_text }}">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->judul }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->caption }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($item->tipe === 'video')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">▶ Video</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">📷 Foto</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $item->urutan }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <form method="POST" action="{{ route('admin.galeri.toggle', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5 transition"
                                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        @if($item->is_active)
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <button type="button"
                                        onclick="openEditGaleri({{ $item->id }}, '{{ addslashes($item->judul) }}', '{{ addslashes($item->caption ?? '') }}', '{{ $item->tipe }}', '{{ addslashes($item->video_url ?? '') }}', '{{ addslashes($item->alt_text ?? '') }}', {{ $item->urutan }}, {{ $item->is_active ? 'true' : 'false' }})"
                                        class="text-indigo-500 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.galeri.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus item {{ addslashes($item->judul) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-14 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm font-semibold text-gray-400">Belum ada item galeri</p>
                            <p class="text-xs text-gray-300 mt-1">Tambahkan foto atau video pertama</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PANEL: JADWAL PPDB --}}
<div class="tab-panel hidden" id="panel-jadwal">
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-800">Jadwal Tahapan PPDB</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola tahapan dan status pendaftaran</p>
            </div>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('admin.ppdb.jadwal.sync') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Sync Status
                    </button>
                </form>
                <button type="button" onclick="openModal('modal-jadwal-add')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Tahapan
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase w-12">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Tahapan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Aktif</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $j)
                    <tr class="border-t border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 font-bold text-indigo-600">{{ $j->nomor_urut }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800">{{ $j->judul }}</p>
                            @if($j->deskripsi)
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($j->deskripsi, 60) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $j->tanggal_label }}</td>
                        <td class="px-4 py-3">
                            @php
                                $sCfg = [
                                    'active'   => ['bg-green-100 text-green-700',  '● Aktif'],
                                    'done'     => ['bg-blue-100 text-blue-700',    '✔ Selesai'],
                                    'upcoming' => ['bg-amber-100 text-amber-700',  '○ Upcoming'],
                                ];
                                [$sc, $sl] = $sCfg[$j->status] ?? $sCfg['upcoming'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc }}">{{ $sl }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $j->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $j->is_active ? 'Ya' : 'Tidak' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button type="button"
                                        onclick="openEditJadwal({{ $j->id }}, {{ json_encode($j->only(['tahun_ajaran','nomor_urut','tanggal_label','tanggal_mulai','tanggal_selesai','judul','deskripsi','status','is_active'])) }})"
                                        class="text-indigo-500 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.ppdb.jadwal.destroy', $j) }}"
                                      onsubmit="return confirm('Hapus tahapan {{ addslashes($j->judul) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-14 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm font-semibold text-gray-400">Belum ada jadwal PPDB</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PANEL: PERSYARATAN --}}
<div class="tab-panel hidden" id="panel-persyaratan">
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-800">Persyaratan Dokumen</h2>
                <p class="text-xs text-gray-400 mt-0.5">Daftar dokumen yang dibutuhkan calon siswa</p>
            </div>
            <button type="button" onclick="openModal('modal-persyaratan-add')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Dokumen
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Nama Dokumen</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">T.A.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Urutan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($persyaratan as $p)
                    <tr class="border-t border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $p->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->dokumen }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->tahun_ajaran }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->urutan }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button type="button"
                                        onclick="openEditPersyaratan({{ $p->id }}, '{{ addslashes($p->dokumen) }}', {{ $p->urutan }}, '{{ $p->tahun_ajaran }}', {{ $p->is_active ? 'true' : 'false' }})"
                                        class="text-indigo-500 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.ppdb.persyaratan.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus persyaratan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-14 text-center">
                            <p class="text-sm font-semibold text-gray-400">Belum ada data persyaratan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Tambah Cepat</p>
            <form method="POST" action="{{ route('admin.ppdb.persyaratan.store') }}" class="flex flex-wrap gap-2.5">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}">
                <input type="hidden" name="is_active" value="1">
                <input type="text" name="dokumen" placeholder="Nama dokumen persyaratan…" required
                       class="flex-1 min-w-48 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <input type="number" name="urutan" placeholder="Urutan" min="0"
                       value="{{ ($persyaratan->max('urutan') ?? 0) + 1 }}"
                       class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            </form>
        </div>
    </div>
</div>

{{-- PANEL: BIAYA --}}
<div class="tab-panel hidden" id="panel-biaya">
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-800">Rincian Biaya Pendidikan</h2>
                <p class="text-xs text-gray-400 mt-0.5">Daftar biaya yang tampil di halaman PPDB</p>
            </div>
            <button type="button" onclick="openModal('modal-biaya-add')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Biaya
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Nama Biaya</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Nominal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Urutan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($biaya as $b)
                    <tr class="border-t border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $b->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800">{{ $b->nama_biaya }}</p>
                            @if($b->keterangan)
                            <p class="text-xs text-gray-400">{{ $b->keterangan }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold text-indigo-600">{{ $b->nominal_rupiah }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $b->urutan }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $b->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button type="button"
                                        onclick="openEditBiaya({{ $b->id }}, '{{ addslashes($b->nama_biaya) }}', {{ $b->nominal }}, '{{ addslashes($b->keterangan ?? '') }}', {{ $b->urutan }}, '{{ $b->tahun_ajaran }}', {{ $b->is_active ? 'true' : 'false' }})"
                                        class="text-indigo-500 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.ppdb.biaya.destroy', $b) }}"
                                      onsubmit="return confirm('Hapus rincian biaya ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 hover:bg-gray-100 rounded-lg p-1.5 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-14 text-center">
                            <p class="text-sm font-semibold text-gray-400">Belum ada data biaya</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Tambah Cepat</p>
            <form method="POST" action="{{ route('admin.ppdb.biaya.store') }}" class="flex flex-wrap gap-2.5">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}">
                <input type="hidden" name="is_active" value="1">
                <input type="text" name="nama_biaya" placeholder="Nama biaya (cth: SPP per Bulan)" required
                       class="flex-1 min-w-44 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <input type="number" name="nominal" placeholder="Nominal" min="0" required
                       class="w-36 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <input type="number" name="urutan" placeholder="Urutan" min="0"
                       value="{{ ($biaya->max('urutan') ?? 0) + 1 }}"
                       class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            </form>
        </div>
    </div>
</div>

{{-- PANEL: SETTING PPDB --}}
<div class="tab-panel hidden" id="panel-kontak">
    <form method="POST"
          action="{{ $ppdbSetting ? route('admin.ppdb.setting.update', $ppdbSetting) : route('admin.ppdb.setting.store') }}">
        @csrf
        @if($ppdbSetting) @method('PUT') @endif

        <div class="bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Pengaturan PPDB</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Konfigurasi periode dan kontak pendaftaran</p>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm font-medium text-gray-700">Setting Aktif</span>
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $ppdbSetting?->is_active ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                </label>
            </div>
            <div class="p-6">
                @if($ppdbSetting)
                <div class="mb-5 px-4 py-3 rounded-lg flex items-center gap-3 text-sm
                    {{ $ppdbSetting->isPendaftaranBuka() ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-gray-50 border border-gray-200 text-gray-600' }}">
                    @if($ppdbSetting->isPendaftaranBuka())
                        <svg class="w-5 h-5 shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span><strong>Pendaftaran sedang DIBUKA</strong>
                            @if($ppdbSetting->tanggal_buka && $ppdbSetting->tanggal_tutup)
                            &mdash; {{ $ppdbSetting->tanggal_buka->format('d M Y') }} s/d {{ $ppdbSetting->tanggal_tutup->format('d M Y') }}
                            @endif
                        </span>
                    @else
                        <svg class="w-5 h-5 shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>Pendaftaran saat ini <strong>DITUTUP</strong></span>
                    @endif
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="tahun_ajaran"
                               value="{{ old('tahun_ajaran', $ppdbSetting?->tahun_ajaran ?? '2025/2026') }}"
                               placeholder="2025/2026"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('tahun_ajaran') border-red-400 @enderror">
                        @error('tahun_ajaran')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="telepon"
                               value="{{ old('telepon', $ppdbSetting?->telepon) }}"
                               placeholder="(0281) 633373"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                        <input type="text" name="jam_operasional"
                               value="{{ old('jam_operasional', $ppdbSetting?->jam_operasional) }}"
                               placeholder="Senin–Sabtu, 08.00–12.30 WIB"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Buka Pendaftaran</label>
                        <input type="date" name="tanggal_buka"
                               value="{{ old('tanggal_buka', $ppdbSetting?->tanggal_buka?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('tanggal_buka') border-red-400 @enderror">
                        @error('tanggal_buka')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tutup Pendaftaran</label>
                        <input type="date" name="tanggal_tutup"
                               value="{{ old('tanggal_tutup', $ppdbSetting?->tanggal_tutup?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('tanggal_tutup') border-red-400 @enderror">
                        @error('tanggal_tutup')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Pendaftaran Online</label>
                        <input type="text" name="link_pendaftaran"
                               value="{{ old('link_pendaftaran', $ppdbSetting?->link_pendaftaran ?? '/register') }}"
                               placeholder="/register"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('link_pendaftaran') <p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Beasiswa</label>
                        <input type="text" name="catatan_beasiswa"
                               value="{{ old('catatan_beasiswa', $ppdbSetting?->catatan_beasiswa) }}"
                               placeholder="*Tersedia beasiswa bagi siswa berprestasi dan kurang mampu"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">Tampil di bawah tabel biaya pendidikan</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Simpan Pengaturan
                    </button>
                    <button type="reset" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


{{-- ═══════════════════════════════════════════════════════
     MODALS
═══════════════════════════════════════════════════════ --}}

{{-- MODAL: Tambah Galeri --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-galeri-add">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Tambah Item Galeri</h3>
            <button onclick="closeModal('modal-galeri-add')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" placeholder="Judul item galeri" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                    <input type="text" name="caption" placeholder="Caption saat hover"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                    <select name="tipe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            onchange="toggleGaleriTipe(this.value, '')">
                        <option value="foto">📷 Foto</option>
                        <option value="video">▶ Video (YouTube)</option>
                    </select>
                </div>
                <div id="field-gambar-add">
                    <label class="block text-sm font-medium text-gray-700 mb-1">File Gambar <span class="text-red-500">*</span></label>
                    <input type="file" name="gambar" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP – maks 5MB</p>
                </div>
                <div id="field-video-add" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL YouTube <span class="text-red-500">*</span></label>
                    <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">URL biasa atau embed — akan otomatis dikonversi.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                        <input type="text" name="alt_text" placeholder="Teks aksesibilitas"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" min="0" value="{{ ($galeri->max('urutan') ?? 0) + 1 }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Aktifkan Item</p>
                        <p class="text-xs text-gray-400">Tampilkan di galeri publik</p>
                    </div>
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                </label>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-galeri-add')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan Item</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Edit Galeri --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-galeri-edit">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Edit Item Galeri</h3>
            <button onclick="closeModal('modal-galeri-edit')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" id="form-galeri-edit" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="edit-galeri-judul" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                    <input type="text" name="caption" id="edit-galeri-caption"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                    <select name="tipe" id="edit-galeri-tipe"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            onchange="toggleGaleriTipe(this.value, '-edit')">
                        <option value="foto">📷 Foto</option>
                        <option value="video">▶ Video (YouTube)</option>
                    </select>
                </div>
                <div id="field-gambar-edit">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti File Gambar</label>
                    <input type="file" name="gambar" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">Biarkan kosong untuk mempertahankan gambar saat ini</p>
                </div>
                <div id="field-video-edit" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL YouTube</label>
                    <input type="url" name="video_url" id="edit-galeri-video" placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                        <input type="text" name="alt_text" id="edit-galeri-alt"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" id="edit-galeri-urutan" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer">
                    <span class="text-sm font-medium text-gray-700">Aktifkan Item</span>
                    <input type="checkbox" name="is_active" value="1" id="edit-galeri-active" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                </label>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-galeri-edit')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Tambah Jadwal --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-jadwal-add">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Tambah Tahapan Jadwal PPDB</h3>
            <button onclick="closeModal('modal-jadwal-add')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.ppdb.jadwal.store') }}">
            @csrf
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="tahun_ajaran" value="{{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Urut <span class="text-red-500">*</span></label>
                    <input type="number" name="nomor_urut" min="1" value="{{ ($jadwals->max('nomor_urut') ?? 0) + 1 }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tahapan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" placeholder="Pendaftaran Online" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Label <span class="text-red-500">*</span></label>
                    <input type="text" name="tanggal_label" placeholder="1 Jan – 28 Feb 2025" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="upcoming">○ Upcoming</option>
                        <option value="active">● Aktif</option>
                        <option value="done">✔ Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat tahapan…"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">Tampilkan di halaman publik</span>
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-jadwal-add')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Edit Jadwal --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-jadwal-edit">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Edit Tahapan Jadwal PPDB</h3>
            <button onclick="closeModal('modal-jadwal-edit')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" id="form-jadwal-edit">
            @csrf @method('PUT')
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="tahun_ajaran" id="ej-tahun"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Urut <span class="text-red-500">*</span></label>
                    <input type="number" name="nomor_urut" id="ej-nomor" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="ej-judul" placeholder="Pendaftaran Online"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Label <span class="text-red-500">*</span></label>
                    <input type="text" name="tanggal_label" id="ej-label" placeholder="1 Jan – 28 Feb 2025"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="ej-status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="upcoming">○ Upcoming</option>
                        <option value="active">● Aktif</option>
                        <option value="done">✔ Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" id="ej-mulai"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" id="ej-selesai"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="ej-deskripsi" rows="2" placeholder="Deskripsi singkat tahapan…"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">Tampilkan di halaman publik</span>
                        <input type="checkbox" name="is_active" value="1" id="ej-active" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-jadwal-edit')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Tambah Persyaratan --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-persyaratan-add">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Tambah Persyaratan</h3>
            <button onclick="closeModal('modal-persyaratan-add')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.ppdb.persyaratan.store') }}">
            @csrf
            <input type="hidden" name="is_active" value="1">
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="dokumen" required placeholder="Fotokopi Ijazah"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="{{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" min="0" value="{{ ($persyaratan->max('urutan') ?? 0) + 1 }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-persyaratan-add')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Edit Persyaratan --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-persyaratan-edit">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Edit Persyaratan</h3>
            <button onclick="closeModal('modal-persyaratan-edit')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" id="form-persyaratan-edit">
            @csrf @method('PUT')
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="dokumen" id="ep-dokumen" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="ep-tahun"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" id="ep-urutan" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer">
                    <span class="text-sm font-medium text-gray-700">Aktif</span>
                    <input type="checkbox" name="is_active" value="1" id="ep-active" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                </label>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-persyaratan-edit')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Tambah Biaya --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-biaya-add">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Tambah Rincian Biaya</h3>
            <button onclick="closeModal('modal-biaya-add')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.ppdb.biaya.store') }}">
            @csrf
            <input type="hidden" name="is_active" value="1">
            <input type="hidden" name="tahun_ajaran" value="{{ $ppdbSetting?->tahun_ajaran ?? '2025/2026' }}">
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Biaya <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_biaya" required placeholder="SPP per Bulan"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="nominal" min="0" required placeholder="750000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" min="0" value="{{ ($biaya->max('urutan') ?? 0) + 1 }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" placeholder="Opsional"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-biaya-add')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Edit Biaya --}}
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 hidden" id="modal-biaya-edit">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Edit Rincian Biaya</h3>
            <button onclick="closeModal('modal-biaya-edit')" class="text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" id="form-biaya-edit">
            @csrf @method('PUT')
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Biaya <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_biaya" id="eb-nama" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                        <input type="number" name="nominal" id="eb-nominal" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" id="eb-urutan" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" id="eb-ket" placeholder="Opsional"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <input type="hidden" name="tahun_ajaran" id="eb-tahun">
                <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer">
                    <span class="text-sm font-medium text-gray-700">Aktif</span>
                    <input type="checkbox" name="is_active" value="1" id="eb-active" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                </label>
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                <button type="button" onclick="closeModal('modal-biaya-edit')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>


<script>
(() => {
    const $ = s => document.querySelector(s);
    const $$ = s => document.querySelectorAll(s);

    /* ── TAB SWITCHING ─────────────────────────────────────────── */
    window.switchTab = (key, button) => {
        // Hide all panels
        $$('.tab-panel').forEach(p => p.classList.add('hidden'));

        // Reset all tab buttons
        $$('.tab-btn').forEach(b => {
            b.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/60');
            b.classList.add('border-transparent', 'text-gray-500');
        });

        // Show target panel
        document.getElementById('panel-' + key)?.classList.remove('hidden');

        // Activate clicked button
        if (button) {
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-indigo-600', 'text-indigo-600', 'bg-indigo-50/60');

            // Scroll tab into view on mobile
            button.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }

        sessionStorage.setItem('ws_tab', key);
    };

    // Restore last active tab
    const savedTab = sessionStorage.getItem('ws_tab');
    if (savedTab) {
        const btn = $(`[data-tab="${savedTab}"]`);
        if (btn) switchTab(savedTab, btn);
    }

    // Show/hide right-fade hint based on scroll position
    const tabNav = $('#tab-nav');
    const fadeRight = $('#tab-fade-right');
    if (tabNav && fadeRight) {
        const checkScroll = () => {
            const canScrollRight = tabNav.scrollWidth > tabNav.clientWidth + tabNav.scrollLeft + 4;
            fadeRight.classList.toggle('hidden', !canScrollRight);
        };
        tabNav.addEventListener('scroll', checkScroll);
        window.addEventListener('resize', checkScroll);
        checkScroll();
    }

    /* ── MODAL HELPERS ─────────────────────────────────────────── */
    window.openModal = id => {
        document.getElementById(id)?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeModal = id => {
        document.getElementById(id)?.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Close on backdrop click
    $$('[id^="modal-"]').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal(modal.id);
        });
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        $$('[id^="modal-"]:not(.hidden)').forEach(m => closeModal(m.id));
    });

    /* ── HERO IMAGE PREVIEW ────────────────────────────────────── */
    window.previewHeroImg = input => {
        const file = input.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview = $('#hero-img-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            $('#hero-img-placeholder')?.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    };

    /* ── STATS TABLE ───────────────────────────────────────────── */
    let statIndex = @json($hero?->stats->count() ?? 0);

    window.addStatRow = () => {
        $('#stats-empty')?.remove();
        const tbody = $('#stats-tbody');
        if (!tbody) return;
        const i = statIndex++;
        const cls = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500';
        const row = document.createElement('tr');
        row.id = `stat-row-n-${i}`;
        row.className = 'border-t border-gray-50 hover:bg-indigo-50/40';
        row.innerHTML = `
            <td class="px-4 py-3 text-center text-gray-300 cursor-grab">⠿</td>
            <td class="px-4 py-3"><input type="text" name="stats[${i}][number]" placeholder="3.200+" class="${cls}"></td>
            <td class="px-4 py-3"><input type="text" name="stats[${i}][label]" placeholder="Alumni Berprestasi" class="${cls}"></td>
            <td class="px-4 py-3"><input type="number" name="stats[${i}][urutan]" value="${i + 1}" class="${cls}"></td>
            <td class="px-4 py-3">
                <button type="button" onclick="removeStatRow('stat-row-n-${i}')"
                        class="text-red-400 hover:text-red-600 hover:bg-gray-100 rounded-lg p-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </td>`;
        tbody.appendChild(row);
        row.querySelector('input')?.focus();
    };

    window.removeStatRow = id => document.getElementById(id)?.remove();

    /* ── GALERI TIPE TOGGLE ────────────────────────────────────── */
    window.toggleGaleriTipe = (value, suffix = '') => {
        document.getElementById(`field-gambar${suffix}`)?.classList.toggle('hidden', value !== 'foto');
        document.getElementById(`field-video${suffix}`)?.classList.toggle('hidden', value !== 'video');
    };

    /* ── EDIT MODAL HELPERS ────────────────────────────────────── */
    window.openEditGaleri = (id, judul, caption, tipe, videoUrl, alt, urutan, isActive) => {
        $('#form-galeri-edit').action = `/admin/galeri/${id}`;
        $('#edit-galeri-judul').value    = judul;
        $('#edit-galeri-caption').value  = caption;
        $('#edit-galeri-tipe').value     = tipe;
        $('#edit-galeri-video').value    = videoUrl;
        $('#edit-galeri-alt').value      = alt;
        $('#edit-galeri-urutan').value   = urutan;
        $('#edit-galeri-active').checked = isActive;
        toggleGaleriTipe(tipe, '-edit');
        openModal('modal-galeri-edit');
    };

    window.openEditJadwal = (id, data) => {
        $('#form-jadwal-edit').action = `/admin/ppdb/jadwal/${id}`;
        $('#ej-tahun').value     = data.tahun_ajaran;
        $('#ej-nomor').value     = data.nomor_urut;
        $('#ej-judul').value     = data.judul;
        $('#ej-label').value     = data.tanggal_label;
        $('#ej-mulai').value     = data.tanggal_mulai?.substring(0, 10) ?? '';
        $('#ej-selesai').value   = data.tanggal_selesai?.substring(0, 10) ?? '';
        $('#ej-deskripsi').value = data.deskripsi ?? '';
        $('#ej-status').value    = data.status;
        $('#ej-active').checked  = !!data.is_active;
        openModal('modal-jadwal-edit');
    };

    window.openEditPersyaratan = (id, dokumen, urutan, tahun, isActive) => {
        $('#form-persyaratan-edit').action = `/admin/ppdb/persyaratan/${id}`;
        $('#ep-dokumen').value  = dokumen;
        $('#ep-urutan').value   = urutan;
        $('#ep-tahun').value    = tahun;
        $('#ep-active').checked = isActive;
        openModal('modal-persyaratan-edit');
    };

    window.openEditBiaya = (id, nama, nominal, ket, urutan, tahun, isActive) => {
        $('#form-biaya-edit').action = `/admin/ppdb/biaya/${id}`;
        $('#eb-nama').value     = nama;
        $('#eb-nominal').value  = nominal;
        $('#eb-ket').value      = ket;
        $('#eb-urutan').value   = urutan;
        $('#eb-tahun').value    = tahun;
        $('#eb-active').checked = isActive;
        openModal('modal-biaya-edit');
    };

    /* ── AUTO-DISMISS FLASH ────────────────────────────────────── */
    setTimeout(() => {
        ['flash-success', 'flash-error'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 5000);
})();
</script>

@endsection