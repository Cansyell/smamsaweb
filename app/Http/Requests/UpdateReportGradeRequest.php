<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'islamic_studies'    => 'required|numeric|between:0,100',
            'indonesian_language' => 'required|numeric|between:0,100',
            'english_language'   => 'required|numeric|between:0,100',
            'ppkn'               => 'nullable|numeric|between:0,100',
            'mtk'                => 'nullable|numeric|between:0,100',
            'ipa'                => 'nullable|numeric|between:0,100',
            'seni_budaya'        => 'nullable|numeric|between:0,100',
            'penjas'             => 'nullable|numeric|between:0,100',
            'prakarya'           => 'nullable|numeric|between:0,100',
        ];
    }

    public function attributes(): array
    {
        return [
            'islamic_studies'    => 'Nilai Pendidikan Agama Islam',
            'indonesian_language' => 'Nilai Bahasa Indonesia',
            'english_language'   => 'Nilai Bahasa Inggris',
            'ppkn'               => 'Nilai PPKn',
            'mtk'                => 'Nilai Matematika',
            'ipa'                => 'Nilai IPA',
            'seni_budaya'        => 'Nilai Seni Budaya',
            'penjas'             => 'Nilai Pendidikan Jasmani',
            'prakarya'           => 'Nilai Prakarya',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi',
            'numeric'  => ':attribute harus berupa angka',
            'between'  => ':attribute harus antara :min dan :max',
        ];
    }
}