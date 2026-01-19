<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('student'); // Untuk update

        return [
            'nisn'             => [
                'required',
                'string',
                'digits:10',
                'unique:students,nisn,' . $studentId
            ],
            'full_name'        => 'required|string|max:255',
            'father_name'      => 'required|string|max:255',
            'mother_name'      => 'required|string|max:255',
            'gender'           => 'required|in:M,F',
            'place_of_birth'   => 'required|string|max:255',
            'date_of_birth'    => 'required|date|before:today',
            'address'          => 'required|string',
            'phone_number'     => 'required|string|max:15|regex:/^[0-9]+$/',
            'previous_school'  => 'required|string|max:255',
            'graduation_year'  => 'required|integer|digits:4|min:2000|max:' . (date('Y') + 1),
            'kip_number'       => 'nullable|string|max:255',
            'specialization'   => 'nullable|in:tahfiz,language',
        ];
    }

    public function messages(): array
    {
        return [
            'nisn.required'           => 'NISN wajib diisi.',
            'nisn.digits'             => 'NISN harus terdiri dari 10 digit.',
            'nisn.unique'             => 'NISN sudah terdaftar.',
            
            'full_name.required'      => 'Nama lengkap wajib diisi.',
            'father_name.required'    => 'Nama ayah wajib diisi.',
            'mother_name.required'    => 'Nama ibu wajib diisi.',
            
            'gender.required'         => 'Jenis kelamin wajib dipilih.',
            'gender.in'               => 'Jenis kelamin tidak valid.',
            
            'place_of_birth.required' => 'Tempat lahir wajib diisi.',
            'date_of_birth.required'  => 'Tanggal lahir wajib diisi.',
            'date_of_birth.date'      => 'Format tanggal lahir tidak valid.',
            'date_of_birth.before'    => 'Tanggal lahir harus sebelum hari ini.',
            
            'address.required'        => 'Alamat wajib diisi.',
            
            'phone_number.required'   => 'Nomor telepon wajib diisi.',
            'phone_number.max'        => 'Nomor telepon maksimal 15 karakter.',
            'phone_number.regex'      => 'Nomor telepon hanya boleh berisi angka.',
            
            'previous_school.required' => 'Sekolah asal wajib diisi.',
            
            'graduation_year.required' => 'Tahun lulus wajib diisi.',
            'graduation_year.integer'  => 'Tahun lulus harus berupa angka.',
            'graduation_year.digits'   => 'Tahun lulus harus 4 digit.',
            'graduation_year.min'      => 'Tahun lulus minimal 2000.',
            'graduation_year.max'      => 'Tahun lulus tidak valid.',
            
            'specialization.in'       => 'Spesialisasi tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nisn'            => 'NISN',
            'full_name'       => 'nama lengkap',
            'father_name'     => 'nama ayah',
            'mother_name'     => 'nama ibu',
            'gender'          => 'jenis kelamin',
            'place_of_birth'  => 'tempat lahir',
            'date_of_birth'   => 'tanggal lahir',
            'address'         => 'alamat',
            'phone_number'    => 'nomor telepon',
            'previous_school' => 'sekolah asal',
            'graduation_year' => 'tahun lulus',
            'kip_number'      => 'nomor KIP',
            'specialization'  => 'spesialisasi',
        ];
    }
}