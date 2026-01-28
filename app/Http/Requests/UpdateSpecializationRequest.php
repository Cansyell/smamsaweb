<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecializationRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'specialization' => [
                'required',
                'in:tahfiz,language,regular'
            ],
            'preference_reason' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'quran_memorization' => [
                'required_if:specialization,tahfiz',
                'nullable',
                'integer',
                'min:0',
                'max:30'
            ],
            'language_interest' => [
                'required_if:specialization,language',
                'nullable',
                'in:arabic,english,both'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'specialization.required' => 'Pilihan peminatan wajib diisi.',
            'specialization.in' => 'Pilihan peminatan tidak valid.',
            'preference_reason.max' => 'Alasan maksimal 1000 karakter.',
            'quran_memorization.required_if' => 'Hafalan Al-Quran wajib diisi untuk kelas Tahfiz.',
            'quran_memorization.min' => 'Hafalan minimal 0 juz.',
            'quran_memorization.max' => 'Hafalan maksimal 30 juz.',
            'language_interest.required_if' => 'Minat bahasa wajib dipilih untuk kelas Bahasa.',
            'language_interest.in' => 'Minat bahasa tidak valid.',
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
            'specialization' => 'peminatan',
            'preference_reason' => 'alasan memilih',
            'quran_memorization' => 'hafalan Al-Quran',
            'language_interest' => 'minat bahasa',
        ];
    }
}