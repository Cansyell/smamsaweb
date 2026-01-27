<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportGradeRequest extends FormRequest
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
            'islamic_studies' => 'required|numeric|between:0,100',
            'indonesian_language' => 'required|numeric|between:0,100',
            'english_language' => 'required|numeric|between:0,100',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'islamic_studies' => 'Nilai Pendidikan Agama Islam',
            'indonesian_language' => 'Nilai Bahasa Indonesia',
            'english_language' => 'Nilai Bahasa Inggris',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi',
            'numeric' => ':attribute harus berupa angka',
            'between' => ':attribute harus antara :min dan :max',
        ];
    }
}