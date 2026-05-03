@extends('layouts.app')

@section('title', 'Hasil Belum Dipublikasikan')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full">

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            {{-- Top Banner --}}
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-8 py-6 text-white">
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="text-sm font-medium tracking-widest uppercase opacity-70">Status Pengumuman</span>
                </div>
                <h1 class="text-2xl font-bold">Hasil Seleksi</h1>
            </div>

            {{-- Body --}}
            <div class="px-8 py-10 text-center">

                {{-- Animated lock icon --}}
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-amber-50 border-4 border-amber-200 mb-6 relative">
                    <svg class="w-11 h-11 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    {{-- Pulsing ring --}}
                    <span class="absolute inset-0 rounded-full border-4 border-amber-300 opacity-0 animate-ping-slow"></span>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-3">
                    Hasil Belum Dipublikasikan
                </h2>
                <p class="text-gray-500 text-base leading-relaxed max-w-md mx-auto mb-8">
                    Panitia seleksi sedang memproses data. Hasil ranking dan status penerimaan akan segera diumumkan. Silakan periksa kembali secara berkala.
                </p>

                {{-- Academic year info box --}}
                @if($activeYear)
                <div class="bg-gray-50 border border-gray-200 rounded-xl px-6 py-5 mb-8 text-left">
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Informasi Tahun Ajaran Aktif</p>
                    <div class="grid grid-cols-2 gap-y-3 text-sm">
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Tahun Ajaran</p>
                            <p class="font-semibold text-gray-800">{{ $activeYear->year }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Status Hasil</p>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $activeYear->result_status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $activeYear->result_status === 'published' ? 'bg-green-500' : 'bg-amber-500' }}
                                    inline-block"></span>
                                {{ $activeYear->result_status === 'published' ? 'Dipublikasikan' : 'Belum Dipublikasikan' }}
                            </span>
                        </div>
                        @if($activeYear->registration_start && $activeYear->registration_end)
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Periode Pendaftaran</p>
                            <p class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($activeYear->registration_start)->format('d M Y') }}
                                –
                                {{ \Carbon\Carbon::parse($activeYear->registration_end)->format('d M Y') }}
                            </p>
                        </div>
                        @endif
                        @if($activeYear->announcement_date)
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Rencana Pengumuman</p>
                            <p class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($activeYear->announcement_date)->format('d M Y') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="bg-red-50 border border-red-200 rounded-xl px-6 py-5 mb-8 text-left">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-700 mb-1">Tidak Ada Tahun Ajaran Aktif</p>
                            <p class="text-sm text-red-600">Saat ini tidak ada tahun ajaran yang sedang aktif. Hubungi panitia untuk informasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tips --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-6 py-4 mb-8 text-left">
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-blue-700">
                            Pastikan data pribadi dan dokumen persyaratan Anda sudah lengkap dan tervalidasi agar dapat diproses oleh panitia.
                        </p>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('student.dashboard') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ke Dashboard
                    </a>
                    <button onclick="window.location.reload()"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-xl border border-gray-200 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Muat Ulang
                    </button>
                </div>
            </div>

            {{-- Footer strip --}}
            <div class="bg-gray-50 border-t border-gray-100 px-8 py-4">
                <p class="text-xs text-center text-gray-400">
                    Jika ada pertanyaan, hubungi panitia seleksi melalui kontak resmi sekolah.
                </p>
            </div>
        </div>

    </div>
</div>

<style>
@keyframes ping-slow {
    0%   { transform: scale(1); opacity: 0.6; }
    70%  { transform: scale(1.4); opacity: 0; }
    100% { transform: scale(1.4); opacity: 0; }
}
.animate-ping-slow {
    animation: ping-slow 2.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
@endsection