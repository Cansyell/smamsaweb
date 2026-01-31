<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'committee';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'notes' => 'required|string|min:10|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'notes.required' => 'Alasan penolakan harus diisi',
            'notes.min' => 'Alasan penolakan minimal 10 karakter',
            'notes.max' => 'Alasan penolakan maksimal 1000 karakter',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'notes' => 'alasan penolakan',
        ];
    }
}