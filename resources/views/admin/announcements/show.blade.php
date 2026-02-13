@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        
        @if(auth()->user()->role === 'admin')
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.announcements.edit', $announcement) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.announcements.destroy', $announcement) }}" 
                  method="POST" 
                  onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-8">

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $announcement->title }}</h1>

            <!-- Meta -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ $announcement->creator->name }}
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $announcement->published_date }}
                </div>
                {!! $announcement->status_badge !!}
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Preview Area -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Gambar -->
                    @if($announcement->image_path)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Header -->
                        <div class="flex items-center px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Gambar Pengumuman</span>
                        </div>
                        <!-- Image Preview -->
                        <div class="bg-gray-100 flex items-center justify-center min-h-48" id="img-container">
                            <img src="{{ $announcement->image_url }}" 
                                 alt="{{ $announcement->title }}"
                                 class="w-full max-h-[480px] object-contain"
                                 onerror="document.getElementById('img-container').innerHTML = '<div class=\'flex flex-col items-center justify-center py-12 text-gray-400\'><svg class=\'w-12 h-12 mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg><p class=\'text-sm\'>Gambar tidak dapat dimuat</p></div>'">
                        </div>
                        <!-- Actions -->
                        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-t border-gray-200">
                            <a href="{{ $announcement->image_url }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Buka di Tab Baru
                            </a>
                            <a href="{{ $announcement->image_url }}" 
                               download
                               class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download Gambar
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- PDF -->
                    @if($announcement->file_path)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Header -->
                        <div class="flex items-center px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Lampiran PDF</span>
                            @if($announcement->file_name)
                                <span class="ml-2 text-xs text-gray-500">— {{ $announcement->file_name }}</span>
                            @endif
                        </div>
                        <!-- PDF Iframe -->
                        <div class="bg-gray-100" id="pdf-container">
                            <iframe 
                                src="{{ $announcement->file_url }}"
                                class="w-full block"
                                style="height: 520px;"
                                frameborder="0"
                                onload="this.style.background='transparent'"
                                onerror="document.getElementById('pdf-fallback').style.display='flex'; this.style.display='none';">
                            </iframe>
                            <!-- Fallback jika iframe blocked -->
                            <div id="pdf-fallback" 
                                 class="hidden flex-col items-center justify-center py-16 text-center px-6">
                                <svg class="w-14 h-14 mb-3 text-red-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-700">Preview PDF tidak tersedia</p>
                                <p class="text-xs text-gray-500 mt-1">Gunakan tombol di bawah untuk membuka atau mengunduh file</p>
                            </div>
                        </div>
                        <!-- Actions -->
                        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-t border-gray-200">
                            <a href="{{ $announcement->file_url }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Buka di Tab Baru
                            </a>
                            <a href="{{ $announcement->file_url }}" 
                               download="{{ $announcement->file_name ?? 'lampiran.pdf' }}"
                               class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download PDF
                            </a>
                            @if($announcement->file_size)
                            <span class="text-xs text-gray-400 ml-auto">{{ $announcement->file_size }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Isi Pengumuman -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="flex items-center px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Isi Pengumuman</span>
                        </div>
                        <div class="p-6">
                            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right: Sidebar -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Info -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700">Informasi Pengumuman</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</p>
                                <p class="mt-1">{!! $announcement->status_badge !!}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Publikasi</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $announcement->published_date }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dibuat Oleh</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $announcement->creator->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Dibuat</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $announcement->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            @if($announcement->created_at->ne($announcement->updated_at))
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Terakhir Diupdate</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $announcement->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Lampiran</p>
                                <div class="mt-1 flex flex-col gap-1.5">
                                    @if($announcement->image_path)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800 w-fit">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                            Gambar
                                        </span>
                                    @endif
                                    @if($announcement->file_path)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-100 text-red-800 w-fit">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                            </svg>
                                            PDF
                                        </span>
                                    @endif
                                    @if(!$announcement->image_path && !$announcement->file_path)
                                        <span class="text-xs text-gray-400">Tidak ada lampiran</span>
                                    @endif
                                </div>
                            </div>
                            @if($announcement->file_path)
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama File PDF</p>
                                <p class="mt-1 text-sm text-gray-900 break-all">{{ $announcement->file_name }}</p>
                                @if($announcement->file_size)
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $announcement->file_size }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    @if(auth()->user()->role === 'admin')
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700">Aksi Admin</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Pengumuman
                            </a>

                            @if($announcement->is_active)
                                <form action="{{ route('admin.announcements.unpublish', $announcement) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        Nonaktifkan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.announcements.publish', $announcement) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Publikasikan
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Pengumuman
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection