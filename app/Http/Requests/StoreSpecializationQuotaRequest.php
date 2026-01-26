<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecializationQuotaRequest extends FormRequest
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
            'academic_year_id' => 'required|exists:academic_years,id',
            'tahfiz_quota' => 'required|integer|min:0|max:1000',
            'language_quota' => 'required|integer|min:0|max:1000',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'academic_year_id' => 'tahun ajaran',
            'tahfiz_quota' => 'kuota tahfiz',
            'language_quota' => 'kuota bahasa',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Tahun ajaran harus dipilih',
            'academic_year_id.exists' => 'Tahun ajaran tidak valid',
            'tahfiz_quota.required' => 'Kuota tahfiz harus diisi',
            'tahfiz_quota.integer' => 'Kuota tahfiz harus berupa angka',
            'tahfiz_quota.min' => 'Kuota tahfiz minimal 0',
            'tahfiz_quota.max' => 'Kuota tahfiz maksimal 1000',
            'language_quota.required' => 'Kuota bahasa harus diisi',
            'language_quota.integer' => 'Kuota bahasa harus berupa angka',
            'language_quota.min' => 'Kuota bahasa minimal 0',
            'language_quota.max' => 'Kuota bahasa maksimal 1000',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
}