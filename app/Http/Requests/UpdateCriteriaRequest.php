<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCriteriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan hanya admin yang bisa akses
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $criteriaId = $this->route('criteria')->id;

        return [
            'code' => [
                'required',
                'string',
                'max:10',
                'uppercase',
                Rule::unique('criterias')->where(function ($query) {
                    return $query->where('specialization', $this->specialization);
                })->ignore($criteriaId),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'specialization' => [
                'required',
                'string',
                'in:tahfiz,language',
            ],
            'attribute_type' => [
                'required',
                'string',
                'in:benefit,cost',
            ],
            'data_source' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'order' => [
                'required',
                'integer',
                'min:1',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
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
            'code' => 'kode kriteria',
            'name' => 'nama kriteria',
            'specialization' => 'spesializasi',
            'attribute_type' => 'tipe atribut',
            'data_source' => 'sumber data',
            'description' => 'deskripsi',
            'order' => 'urutan',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => ':attribute wajib diisi',
            'code.unique' => ':attribute sudah digunakan untuk spesializasi ini',
            'code.uppercase' => ':attribute harus menggunakan huruf kapital',
            'code.max' => ':attribute maksimal :max karakter',
            
            'name.required' => ':attribute wajib diisi',
            'name.max' => ':attribute maksimal :max karakter',
            
            'specialization.required' => ':attribute wajib dipilih',
            'specialization.in' => ':attribute tidak valid',
            
            'attribute_type.required' => ':attribute wajib dipilih',
            'attribute_type.in' => ':attribute harus benefit atau cost',
            
            'data_source.max' => ':attribute maksimal :max karakter',
            
            'description.max' => ':attribute maksimal :max karakter',
            
            'order.required' => ':attribute wajib diisi',
            'order.integer' => ':attribute harus berupa angka',
            'order.min' => ':attribute minimal :min',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert code to uppercase
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper($this->code),
            ]);
        }
    }
}