@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center">
        <a href="{{ route('admin.announcements.index') }}" 
           class="mr-4 text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Pengumuman</h2>
            <p class="text-gray-600 mt-1">Update informasi pengumuman</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-red-700 font-medium">Terdapat kesalahan pada form:</p>
                    <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.announcements.update', $announcement) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Judul Pengumuman <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="{{ old('title', $announcement->title) }}"
                   required
                   maxlength="255"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-500 @enderror"
                   placeholder="Masukkan judul pengumuman...">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                Isi Pengumuman <span class="text-red-500">*</span>
            </label>
            <textarea id="content" 
                      name="content" 
                      rows="8"
                      required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('content') border-red-500 @enderror"
                      placeholder="Tulis isi pengumuman di sini...">{{ old('content', $announcement->content) }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Gunakan Enter untuk membuat paragraf baru</p>
        </div>

        <!-- Current Image -->
        @if($announcement->image_path)
        <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
            <p class="text-sm font-medium text-gray-700 mb-3">Gambar Saat Ini</p>
            <div class="flex items-start justify-between gap-4">
                <img src="{{ $announcement->image_url }}" 
                     alt="Current Image" 
                     class="w-32 h-32 object-cover rounded border border-gray-300"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-32 h-32 bg-gray-200 rounded border border-gray-300 items-center justify-center" style="display:none;">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Upload gambar baru di bawah untuk mengganti</p>
                    <label class="flex items-center mt-3 cursor-pointer w-fit">
                        <input type="checkbox" name="delete_image" value="1" class="mr-2 w-4 h-4 text-red-600 border-gray-300 rounded">
                        <span class="text-sm text-red-600 font-medium">Hapus gambar ini</span>
                    </label>
                </div>
            </div>
        </div>
        @endif

        <!-- Image Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ $announcement->image_path ? 'Ganti Gambar (Opsional)' : 'Upload Gambar (Opsional)' }}
            </label>
            <div class="border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition"
                 onclick="document.getElementById('image').click()">
                <div class="flex flex-col items-center justify-center p-6" id="image-preview-container">
                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload gambar baru</span></p>
                    <p class="text-xs text-gray-500">PNG, JPG, atau GIF (Maks. 2MB)</p>
                </div>
                <input id="image" name="image" type="file" accept="image/*" class="hidden"
                       onchange="previewImage(this)">
            </div>
            @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Current File -->
        @if($announcement->file_path)
        <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
            <p class="text-sm font-medium text-gray-700 mb-3">File PDF Saat Ini</p>
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg mr-3">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900 font-medium break-all">{{ $announcement->file_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Ukuran: {{ $announcement->file_size }}</p>
                        <a href="{{ $announcement->file_url }}" target="_blank"
                           class="text-xs text-indigo-600 hover:text-indigo-800 mt-1 inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Lihat File
                        </a>
                    </div>
                </div>
                <label class="flex items-center cursor-pointer w-fit flex-shrink-0">
                    <input type="checkbox" name="delete_file" value="1" class="mr-2 w-4 h-4 text-red-600 border-gray-300 rounded">
                    <span class="text-sm text-red-600 font-medium">Hapus file ini</span>
                </label>
            </div>
        </div>
        @endif

        <!-- File Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ $announcement->file_path ? 'Ganti File PDF (Opsional)' : 'Upload File PDF (Opsional)' }}
            </label>
            <div class="border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition"
                 onclick="document.getElementById('file').click()">
                <div class="flex flex-col items-center justify-center p-6" id="file-preview-container">
                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500"><span class="font-semibold">Upload File PDF Baru</span></p>
                    <p class="text-xs text-gray-500">Maks. 5MB</p>
                </div>
                <input id="file" name="file" type="file" accept="application/pdf" class="hidden"
                       onchange="previewFile(this)">
            </div>
            @error('file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Published At -->
        <div>
            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Publikasi
            </label>
            <input type="datetime-local" 
                   id="published_at" 
                   name="published_at" 
                   value="{{ old('published_at', $announcement->published_at?->format('Y-m-d\TH:i')) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('published_at') border-red-500 @enderror">
            @error('published_at')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Kosongkan untuk publikasi langsung</p>
        </div>

        <!-- Is Active -->
        <div class="flex items-center">
            <input type="checkbox" 
                   id="is_active" 
                   name="is_active" 
                   value="1"
                   {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                Aktifkan pengumuman ini
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.announcements.index') }}" 
               class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Pengumuman
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const container = document.getElementById('image-preview-container');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            container.innerHTML = `
                <img src="${e.target.result}" class="max-h-44 rounded object-contain mb-2" alt="Preview">
                <p class="text-xs text-gray-500">${input.files[0].name}</p>
                <p class="text-xs text-indigo-500 mt-1">Klik untuk ganti gambar</p>
            `;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewFile(input) {
    const container = document.getElementById('file-preview-container');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        container.innerHTML = `
            <svg class="w-10 h-10 mb-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-gray-700 font-medium">${file.name}</p>
            <p class="text-xs text-gray-500">${sizeMB} MB</p>
            <p class="text-xs text-indigo-500 mt-1">Klik untuk ganti file</p>
        `;
    }
}
</script>
@endpush
@endsection