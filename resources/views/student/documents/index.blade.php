@extends('layouts.app')

@section('title', 'Dokumen Saya')

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

    @if(session('info'))
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-blue-700">{{ session('info') }}</p>
        </div>
    </div>
    @endif

    <!-- Documents Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Dokumen Saya</h2>
            <a href="{{ route('student.documents.create') }}" 
               class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Upload Dokumen
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Dokumen</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $statistics['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pending</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $statistics['pending'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Valid</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $statistics['valid'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-pink-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Invalid</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $statistics['invalid'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('student.documents.index') }}" 
                   class="px-4 py-2 rounded-lg {{ (!request('filter') && !request('type')) ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Semua
                </a>
                <a href="{{ route('student.documents.index', ['filter' => 'pending']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('filter') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Pending
                </a>
                <a href="{{ route('student.documents.index', ['filter' => 'valid']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('filter') == 'valid' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Valid
                </a>
                <a href="{{ route('student.documents.index', ['filter' => 'invalid']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('filter') == 'invalid' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Invalid
                </a>
                <span class="border-l border-gray-300 mx-1"></span>
                <a href="{{ route('student.documents.index', ['type' => 'certificate']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('type') == 'certificate' ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Ijazah
                </a>
                <a href="{{ route('student.documents.index', ['type' => 'report']) }}" 
                   class="px-4 py-2 rounded-lg {{ request('type') == 'report' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Rapor
                </a>
            </div>
        </div>

        <!-- Documents List -->
        @if($documents->count() > 0)
        <div class="space-y-4">
            @foreach($documents as $document)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @else
                            <div class="p-3 bg-red-100 rounded-lg">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-base font-semibold text-gray-900 truncate">
                                    {{ Str::limit($document->file_name, 50) }}
                                </h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full flex-shrink-0
                                    {{ $document->document_type == 'certificate' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $document->type_label }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $document->file_size }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $document->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>

                            <div class="mt-2">
                                {!! $document->status_badge !!}
                            </div>

                            @if($document->isInvalid() && $document->notes)
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded text-sm">
                                <p class="font-medium text-red-800 mb-1">Alasan Ditolak:</p>
                                <p class="text-red-700">{{ $document->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 ml-4">
                        <a href="{{ route('student.documents.show', $document) }}" 
                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                           title="Lihat Detail">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        
                        @if(!$document->isValid())
                        <a href="{{ route('student.documents.edit', $document) }}" 
                           class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                           title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        
                        <form action="{{ route('student.documents.destroy', $document) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" 
                                    title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Dokumen</h3>
            <p class="text-gray-600 mb-6">Silakan upload dokumen ijazah atau rapor Anda untuk proses validasi</p>
            <a href="{{ route('student.documents.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Upload Dokumen Sekarang
            </a>
        </div>
        @endif
    </div>
</div>
@endsection