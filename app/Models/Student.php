<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'student_id',
        'nisn',
        'full_name',
        'father_name',
        'mother_name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'address',
        'phone_number',
        'previous_school',
        'graduation_year',
        'kip_number',
        'specialization',
        'validation_status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'graduation_year' => 'integer',
    ];

    /* =======================
     | RELATIONSHIP
     ======================= */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* =======================
     | QUERY SCOPE
     ======================= */
    public function scopePending($query)
    {
        return $query->where('validation_status', 'pending');
    }

    public function scopeValid($query)
    {
        return $query->where('validation_status', 'valid');
    }

    public function scopeInvalid($query)
    {
        return $query->where('validation_status', 'invalid');
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeGraduatedInYear($query, $year)
    {
        return $query->where('graduation_year', $year);
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */
    public static function createStudent(array $data): self
    {
        return self::create([
            'user_id'          => $data['user_id'],
            'student_id'       => self::generateStudentId(),
            'nisn'             => $data['nisn'],
            'full_name'        => $data['full_name'],
            'father_name'      => $data['father_name'],
            'mother_name'      => $data['mother_name'],
            'gender'           => $data['gender'],
            'place_of_birth'   => $data['place_of_birth'],
            'date_of_birth'    => $data['date_of_birth'],
            'address'          => $data['address'],
            'phone_number'     => $data['phone_number'],
            'previous_school'  => $data['previous_school'],
            'graduation_year'  => $data['graduation_year'],
            'kip_number'       => $data['kip_number'] ?? null,
            'specialization'   => $data['specialization'] ?? null,
            'validation_status'=> 'pending',
        ]);
    }

    public function updateStudent(array $data): bool
    {
        return $this->update($data);
    }

    public function validateStudent(string $status): bool
    {
        if (!in_array($status, ['valid', 'invalid'])) {
            return false;
        }

        return $this->update([
            'validation_status' => $status
        ]);
    }

    /* =======================
     | ACCESSOR
     ======================= */
    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'M' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->validation_status) {
            'pending' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'valid' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Valid</span>',
            'invalid' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Invalid</span>',
            default => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    public function getSpecializationLabelAttribute(): ?string
    {
        return match($this->specialization) {
            'tahfiz' => 'Tahfiz',
            'language' => 'Bahasa',
            default => null,
        };
    }

    public function getHasKipAttribute(): bool
    {
        return !empty($this->kip_number);
    }

    /* =======================
     | HELPER
     ======================= */
    private static function generateStudentId(): string
    {
        $year = now()->format('y');
        $month = now()->format('m');
        
        // Cari nomor urut terakhir di bulan ini
        $lastStudent = self::whereRaw("student_id LIKE 'STD{$year}{$month}%'")
                          ->orderBy('student_id', 'desc')
                          ->first();
        
        if ($lastStudent) {
            $lastNumber = (int) substr($lastStudent->student_id, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "STD{$year}{$month}{$newNumber}";
    }

    /* =======================
     | VALIDATION HELPER
     ======================= */
    public function isPending(): bool
    {
        return $this->validation_status === 'pending';
    }

    public function isValid(): bool
    {
        return $this->validation_status === 'valid';
    }

    public function isInvalid(): bool
    {
        return $this->validation_status === 'invalid';
    }
}