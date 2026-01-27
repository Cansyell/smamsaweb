@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="space-y-6">
    <!-- Progress Bar Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Progress Pendaftaran</h3>
            <span class="text-2xl font-bold text-indigo-600">{{ number_format($progress['percentage'], 0) }}%</span>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500" 
                 style="width: {{ $progress['percentage'] }}%"></div>
        </div>
        
        <p class="text-sm text-gray-600">
            {{ $progress['completed'] }} dari {{ $progress['total'] }} langkah telah diselesaikan
        </p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-yellow-700">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    <!-- Document Detail Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detail Dokumen</h2>
            <a href="{{ route('student.documents.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Preview Section -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Document Preview -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Dokumen</h3>
                    
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden">
                        <iframe src="{{ $document->file_url }}" 
                                class="w-full h-96"
                                frameborder="0">
                        </iframe>
                    </div>

                    <div class="mt-4">
                        <a href="{{ $document->file_url }}" 
                        download="{{ $document->file_name }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download Dokumen
                        </a>
                    </div>
                </div>

                <!-- Validation Status -->
                @if($document->isInvalid() && $document->notes)
                <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800">Dokumen Ditolak</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p class="font-medium">Alasan:</p>
                                <p class="mt-1">{{ $document->notes }}</p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('student.documents.edit', $document) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Perbaiki Dokumen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($document->isValid())
                <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-green-800">Dokumen Tervalidasi</h3>
                            @if($document->notes)
                            <div class="mt-2 text-sm text-green-700">
                                <p class="font-medium">Catatan:</p>
                                <p class="mt-1">{{ $document->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar Information -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Document Info -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Dokumen</h3>
                    
                    <div class="space-y-4">
                        <!-- File Name -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama File</p>
                            <p class="mt-1 text-sm text-gray-900 break-all">{{ $document->file_name }}</p>
                        </div>

                        <!-- Document Type -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipe Dokumen</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $document->document_type == 'certificate' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $document->type_label }}
                                </span>
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status Validasi</p>
                            <p class="mt-1">{!! $document->status_badge !!}</p>
                        </div>

                        <!-- File Size -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Ukuran File</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->file_size }}</p>
                        </div>

                        <!-- Upload Date -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Upload</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->created_at->format('d F Y, H:i') }}</p>
                        </div>

                        <!-- Last Update -->
                        @if($document->created_at != $document->updated_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $document->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>
                    
                    <div class="space-y-3">
                        @if(!$document->isValid())
                        <a href="{{ route('student.documents.edit', $document) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Dokumen
                        </a>

                        <form action="{{ route('student.documents.destroy', $document) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Dokumen
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection