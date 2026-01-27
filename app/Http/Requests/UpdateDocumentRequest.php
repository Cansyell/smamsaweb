<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'document_type' => ['sometimes', 'required', 'in:certificate,report'],
            'file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120', // 5MB
            ],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'document_type' => 'tipe dokumen',
            'file' => 'file dokumen',
            'notes' => 'catatan',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'document_type.required' => ':attribute wajib dipilih.',
            'document_type.in' => ':attribute tidak valid.',
            'file.file' => ':attribute harus berupa file.',
            'file.mimes' => ':attribute harus berformat PDF.',
            'file.max' => ':attribute maksimal berukuran 5MB.',
            'notes.max' => ':attribute maksimal :max karakter.',
        ];
    }
}