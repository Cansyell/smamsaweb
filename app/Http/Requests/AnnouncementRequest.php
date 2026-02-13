<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can create/update announcements
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ];

        // Image validation (optional)
        if ($this->hasFile('image')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'; // Max 2MB
        }

        // File validation (optional, PDF only)
        if ($this->hasFile('file')) {
            $rules['file'] = 'nullable|file|mimes:pdf|max:5120'; // Max 5MB
        }

        // Delete flags
        $rules['delete_image'] = 'sometimes|boolean';
        $rules['delete_file'] = 'sometimes|boolean';

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul pengumuman wajib diisi.',
            'title.max' => 'Judul pengumuman maksimal 255 karakter.',
            'content.required' => 'Isi pengumuman wajib diisi.',
            
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus: jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            
            'file.file' => 'File harus berupa dokumen yang valid.',
            'file.mimes' => 'Format file harus PDF.',
            'file.max' => 'Ukuran file maksimal 5MB.',
            
            'published_at.date' => 'Format tanggal publikasi tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'content' => 'isi pengumuman',
            'image' => 'gambar',
            'file' => 'file PDF',
            'published_at' => 'tanggal publikasi',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox value to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }

        // Set default published_at to now if not provided
        if (!$this->has('published_at') || empty($this->published_at)) {
            $this->merge([
                'published_at' => now(),
            ]);
        }
    }
}