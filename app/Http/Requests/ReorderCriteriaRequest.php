<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderCriteriaRequest extends FormRequest
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
        return [
            'orders' => [
                'required',
                'array',
                'min:1',
            ],
            'orders.*' => [
                'required',
                'integer',
                'min:1',
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
            'orders' => 'urutan',
            'orders.*' => 'urutan kriteria',
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
            'orders.required' => ':attribute wajib diisi',
            'orders.array' => ':attribute harus berupa array',
            'orders.min' => ':attribute minimal harus ada :min item',
            
            'orders.*.required' => ':attribute wajib diisi',
            'orders.*.integer' => ':attribute harus berupa angka',
            'orders.*.min' => ':attribute minimal :min',
        ];
    }
}