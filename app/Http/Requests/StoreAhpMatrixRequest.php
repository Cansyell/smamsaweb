<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAhpMatrixRequest extends FormRequest
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
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'specialization' => ['required', 'in:tahfiz,language'],
            'criteria_row_id' => ['required', 'exists:criterias,id'],
            'criteria_col_id' => ['required', 'exists:criterias,id', 'different:criteria_row_id'],
            'comparison_value' => ['required', 'numeric', 'min:0.111', 'max:9'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Tahun akademik harus dipilih',
            'academic_year_id.exists' => 'Tahun akademik tidak valid',
            'specialization.required' => 'Spesialisasi harus dipilih',
            'specialization.in' => 'Spesialisasi hanya boleh tahfiz atau language',
            'criteria_row_id.required' => 'Kriteria baris harus dipilih',
            'criteria_row_id.exists' => 'Kriteria baris tidak valid',
            'criteria_col_id.required' => 'Kriteria kolom harus dipilih',
            'criteria_col_id.exists' => 'Kriteria kolom tidak valid',
            'criteria_col_id.different' => 'Kriteria baris dan kolom harus berbeda',
            'comparison_value.required' => 'Nilai perbandingan harus diisi',
            'comparison_value.numeric' => 'Nilai perbandingan harus berupa angka',
            'comparison_value.min' => 'Nilai perbandingan minimal 1/9 (0.111)',
            'comparison_value.max' => 'Nilai perbandingan maksimal 9',
            'notes.max' => 'Catatan maksimal 500 karakter',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert fraction input to decimal if needed
        if ($this->has('comparison_value') && is_string($this->comparison_value)) {
            $value = $this->comparison_value;
            
            // Check if it's a fraction like "1/3", "1/5", etc.
            if (preg_match('/^(\d+)\/(\d+)$/', $value, $matches)) {
                $numerator = (float) $matches[1];
                $denominator = (float) $matches[2];
                
                if ($denominator != 0) {
                    $this->merge([
                        'comparison_value' => $numerator / $denominator,
                    ]);
                }
            }
        }
    }
}