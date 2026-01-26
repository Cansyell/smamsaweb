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
        'academic_year_id',
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
        'validation_notes',
        'validated_at',
        'ranking',
        'final_class_type',
        'final_status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'graduation_year' => 'integer',
        'validated_at' => 'datetime',
    ];

    /* =======================
     | RELATIONSHIPS
     ======================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function testScore()
    {
        return $this->hasOne(TestScore::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function reportGrade()
    {
        return $this->hasOne(ReportGrade::class);
    }

    public function finalScore()
    {
        return $this->hasOne(FinalScore::class);
    }

    /* =======================
     | SCOPES
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

    public function scopeByAcademicYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */

    public static function createStudent(array $data): self
    {
        return self::create([
            'user_id'          => $data['user_id'],
            'academic_year_id' => $data['academic_year_id'] ?? null,
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

    public function validateStudent(string $status, ?string $notes = null): bool
    {
        if (!in_array($status, ['valid', 'invalid'])) {
            return false;
        }

        return $this->update([
            'validation_status' => $status,
            'validation_notes' => $notes,
            'validated_at' => now(),
        ]);
    }

    /* =======================
     | REGISTRATION PROGRESS & DETAILS
     ======================= */

    public function isPersonalDataCompleted(): bool
    {
        return !empty($this->full_name)
            && !empty($this->nisn)
            && !empty($this->date_of_birth)
            && !empty($this->gender)
            && !empty($this->phone_number)
            && !empty($this->address);
    }

    public function isReportGradeCompleted(): bool
    {
        return $this->reportGrade()->exists();
    }

    public function isDocumentsCompleted(): bool
    {
        return $this->documents()->count() >= 2;
    }

    public function isSpecializationCompleted(): bool
    {
        return !empty($this->specialization);
    }

    public function getRegistrationProgress(): array
    {
        $totalSteps = 4;
        $completedSteps = 0;

        if ($this->isPersonalDataCompleted()) $completedSteps++;
        if ($this->isReportGradeCompleted()) $completedSteps++;
        if ($this->isDocumentsCompleted()) $completedSteps++;
        if ($this->isSpecializationCompleted()) $completedSteps++;

        return [
            'percentage' => ($completedSteps / $totalSteps) * 100,
            'completed' => $completedSteps,
            'total' => $totalSteps,
        ];
    }

    public function getPersonalDataDetails(): array
    {
        $fields = [
            'Nama Lengkap' => $this->full_name,
            'NISN' => $this->nisn,
            'Tanggal Lahir' => $this->date_of_birth,
            'Jenis Kelamin' => $this->gender,
            'No. Telepon' => $this->phone_number,
            'Alamat' => $this->address,
        ];

        $completed = collect($fields)->filter()->count();
        $total = count($fields);

        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => ($completed / $total) * 100,
        ];
    }

    public function getGradesDetails(): array
    {
        $reportGrade = $this->reportGrade;

        if (!$reportGrade) {
            return [
                'completed' => 0,
                'total' => 3,
                'percentage' => 0,
                'average' => null,
            ];
        }

        $fields = [
            'PAI' => $reportGrade->pai_grade,
            'B. Indonesia' => $reportGrade->indonesian_grade,
            'B. Inggris' => $reportGrade->english_grade,
        ];

        $completed = collect($fields)->filter(fn($val) => $val > 0)->count();
        $total = count($fields);

        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => ($completed / $total) * 100,
            'average' => $reportGrade->average_grade,
        ];
    }

    public function getDocumentsDetails(): array
    {
        $documents = $this->documents;

        return [
            'completed' => $documents->count(),
            'total' => 2,
            'percentage' => min(($documents->count() / 2) * 100, 100),
            'files' => $documents->pluck('document_type')->toArray(),
        ];
    }

    public function getSpecializationDetails(): array
    {
        return [
            'selected' => $this->specialization,
            'completed' => !empty($this->specialization),
        ];
    }

    public function getTestScoresStatus(): array
    {
        $testScore = $this->testScore;

        if (!$testScore) {
            return [
                'completed' => false,
                'quran_achievement' => null,
                'quran_reading' => null,
                'interview' => null,
                'public_speaking' => null,
                'dialogue' => null,
                'average' => null,
            ];
        }

        return [
            'completed' => true,
            'quran_achievement' => $testScore->quran_achievement,
            'quran_reading' => $testScore->quran_reading,
            'interview' => $testScore->interview,
            'public_speaking' => $testScore->public_speaking,
            'dialogue' => $testScore->dialogue,
            'average' => $testScore->average_score,
        ];
    }

    public function getFinalResult(): array
    {
        $finalScore = $this->finalScore;

        if (!$finalScore) {
            return [
                'calculated' => false,
                'academic_score' => null,
                'test_score' => null,
                'total_score' => null,
                'ranking' => null,
                'class_type' => null,
                'status' => null,
            ];
        }

        return [
            'calculated' => true,
            'academic_score' => $finalScore->academic_score,
            'test_score' => $finalScore->test_score,
            'total_score' => $finalScore->total_score,
            'ranking' => $this->ranking ?? null,
            'class_type' => $this->final_class_type ?? null,
            'status' => $this->final_status ?? null,
        ];
    }

    public function getValidationStatus(): array
    {
        return [
            'status' => $this->validation_status ?? 'pending',
            'notes' => $this->validation_notes ?? null,
            'validated_at' => $this->validated_at ?? null,
        ];
    }

    /* =======================
     | ACCESSORS
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
        $date = now()->format('ymd');
        $lastStudent = self::whereRaw("student_id LIKE '{$date}%'")
                          ->orderBy('student_id', 'desc')
                          ->first();

        $newNumber = $lastStudent
            ? str_pad((int) substr($lastStudent->student_id, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        return "{$date}{$newNumber}";
    }
}