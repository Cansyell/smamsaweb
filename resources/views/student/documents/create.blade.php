@extends('layouts.app')

@section('title', 'Upload Dokumen')

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

    <!-- Upload Document Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Upload Dokumen Baru</h2>
            <p class="text-gray-600 mt-1">Upload dokumen ijazah atau rapor untuk proses validasi</p>
        </div>

        <form action="{{ route('student.documents.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Section -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Document Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Tipe Dokumen <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Certificate Option -->
                            <label class="relative flex flex-col p-4 border-2 rounded-lg cursor-pointer transition document-type-option
                                {{ !$certificateLimit['can_upload'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                                data-type="certificate">
                                <input type="radio" name="document_type" value="certificate" 
                                       class="sr-only document-type-radio"
                                       {{ old('document_type') == 'certificate' ? 'checked' : '' }}
                                       {{ !$certificateLimit['can_upload'] ? 'disabled' : '' }}
                                       required>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 p-2 bg-purple-100 rounded-lg icon-box">
                                        <svg class="w-8 h-8 text-purple-600 icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 option-title">Ijazah</p>
                                        <p class="text-xs text-gray-500 mt-1 option-subtitle">
                                            {{ $certificateLimit['remaining'] }} / {{ $certificateLimit['limit'] }} tersisa
                                        </p>
                                    </div>
                                </div>
                                @if(!$certificateLimit['can_upload'])
                                <span class="absolute top-2 right-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                    Limit Penuh
                                </span>
                                @endif
                                <!-- Selected Indicator -->
                                <div class="selected-indicator hidden absolute top-2 right-2">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>

                            <!-- Report Option -->
                            <label class="relative flex flex-col p-4 border-2 rounded-lg cursor-pointer transition document-type-option
                                {{ !$reportLimit['can_upload'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                                data-type="report">
                                <input type="radio" name="document_type" value="report" 
                                       class="sr-only document-type-radio"
                                       {{ old('document_type') == 'report' ? 'checked' : '' }}
                                       {{ !$reportLimit['can_upload'] ? 'disabled' : '' }}
                                       required>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 p-2 bg-indigo-100 rounded-lg icon-box">
                                        <svg class="w-8 h-8 text-indigo-600 icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 option-title">Rapor</p>
                                        <p class="text-xs text-gray-500 mt-1 option-subtitle">
                                            {{ $reportLimit['remaining'] }} / {{ $reportLimit['limit'] }} tersisa
                                        </p>
                                    </div>
                                </div>
                                @if(!$reportLimit['can_upload'])
                                <span class="absolute top-2 right-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                    Limit Penuh
                                </span>
                                @endif
                                <!-- Selected Indicator -->
                                <div class="selected-indicator hidden absolute top-2 right-2">
                                    <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                        @error('document_type')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

    
                   <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            File Dokumen <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition"
                            id="dropZone">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span>Upload file</span>
                                        <input id="file" name="file" type="file" class="sr-only" 
                                            accept=".pdf,application/pdf"
                                            required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PDF hingga 5MB
                                </p>
                                <div id="fileInfo" class="hidden mt-4">
                                    <div class="flex items-center justify-center space-x-2 text-sm">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span id="fileName" class="text-gray-700 font-medium"></span>
                                        <span id="fileSize" class="text-gray-500"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('file')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-3">
                            Catatan <span class="text-gray-400">(Opsional)</span>
                        </label>
                        <textarea name="notes" id="notes" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Tambahkan catatan atau informasi tambahan...">{{ old('notes') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Maksimal 500 karakter</p>
                        @error('notes')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="lg:col-span-1 space-y-4">
                
                    <!-- Requirements -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Persyaratan Upload
                        </h3>
                        <ul class="space-y-2 text-sm text-blue-900">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Format: PDF
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Ukuran maksimal 5MB
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Dokumen harus jelas dan terbaca
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Maksimal 3 ijazah
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Maksimal 6 rapor
                            </li>
                        </ul>
                    </div>

                    <!-- Tips -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="font-semibold text-yellow-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Tips Upload
                        </h3>
                        <ul class="space-y-2 text-sm text-yellow-900">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                Pastikan foto/scan dokumen tidak blur
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                Gunakan pencahayaan yang baik
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                Pastikan semua teks dapat terbaca
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                Hindari bayangan pada dokumen
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between border-t border-gray-200 pt-6 mt-6">
                <a href="{{ route('student.documents.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const dropZone = document.getElementById('dropZone');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    // Handle document type selection with visual feedback
    const documentTypeOptions = document.querySelectorAll('.document-type-option');
    const documentTypeRadios = document.querySelectorAll('.document-type-radio');

    // Function to update selected state
    function updateSelectedState() {
        documentTypeOptions.forEach(option => {
            const radio = option.querySelector('.document-type-radio');
            const selectedIndicator = option.querySelector('.selected-indicator');
            const iconBox = option.querySelector('.icon-box');
            const iconSvg = option.querySelector('.icon-svg');
            const optionTitle = option.querySelector('.option-title');
            const optionSubtitle = option.querySelector('.option-subtitle');
            
            if (radio.checked) {
                // Selected state
                const type = option.dataset.type;
                
                if (type === 'certificate') {
                    option.classList.remove('border-gray-300', 'hover:border-blue-300');
                    option.classList.add('border-purple-500', 'bg-purple-50', 'shadow-lg', 'ring-2', 'ring-purple-200');
                    iconBox.classList.remove('bg-purple-100');
                    iconBox.classList.add('bg-purple-500');
                    iconSvg.classList.remove('text-purple-600');
                    iconSvg.classList.add('text-white');
                    optionTitle.classList.add('text-purple-900');
                    optionSubtitle.classList.add('text-purple-700');
                } else if (type === 'report') {
                    option.classList.remove('border-gray-300', 'hover:border-blue-300');
                    option.classList.add('border-indigo-500', 'bg-indigo-50', 'shadow-lg', 'ring-2', 'ring-indigo-200');
                    iconBox.classList.remove('bg-indigo-100');
                    iconBox.classList.add('bg-indigo-500');
                    iconSvg.classList.remove('text-indigo-600');
                    iconSvg.classList.add('text-white');
                    optionTitle.classList.add('text-indigo-900');
                    optionSubtitle.classList.add('text-indigo-700');
                }
                
                selectedIndicator.classList.remove('hidden');
            } else {
                // Unselected state
                const type = option.dataset.type;
                
                if (type === 'certificate') {
                    option.classList.remove('border-purple-500', 'bg-purple-50', 'shadow-lg', 'ring-2', 'ring-purple-200');
                    option.classList.add('border-gray-300', 'hover:border-blue-300');
                    iconBox.classList.remove('bg-purple-500');
                    iconBox.classList.add('bg-purple-100');
                    iconSvg.classList.remove('text-white');
                    iconSvg.classList.add('text-purple-600');
                    optionTitle.classList.remove('text-purple-900');
                    optionSubtitle.classList.remove('text-purple-700');
                } else if (type === 'report') {
                    option.classList.remove('border-indigo-500', 'bg-indigo-50', 'shadow-lg', 'ring-2', 'ring-indigo-200');
                    option.classList.add('border-gray-300', 'hover:border-blue-300');
                    iconBox.classList.remove('bg-indigo-500');
                    iconBox.classList.add('bg-indigo-100');
                    iconSvg.classList.remove('text-white');
                    iconSvg.classList.add('text-indigo-600');
                    optionTitle.classList.remove('text-indigo-900');
                    optionSubtitle.classList.remove('text-indigo-700');
                }
                
                selectedIndicator.classList.add('hidden');
            }
        });
    }

    // Add click event to each option
    documentTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('.document-type-radio');
            if (radio && !radio.disabled) {
                radio.checked = true;
                updateSelectedState();
            }
        });
    });

    // Update on radio change
    documentTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateSelectedState);
    });

    // Initialize selected state on page load
    updateSelectedState();

    // File upload handling
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFiles(files);
        }
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            fileName.textContent = file.name;
            fileSize.textContent = formatBytes(file.size);
            fileInfo.classList.remove('hidden');
        }
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
});
</script>
@endpush
@endsection