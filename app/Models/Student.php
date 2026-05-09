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
        'preference_reason',
        'quran_memorization',
        'language_interest',
        'validation_status',
        'validation_notes',
        'validated_at',
        'ranking',
        'final_class_type',
        'final_status',
        'resubmission_count',
        'resubmitted_at',
        'resubmission_notes',
        'has_pending_resubmission',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'graduation_year' => 'integer',
        'validated_at' => 'datetime',
        'resubmitted_at'            => 'datetime',
        'has_pending_resubmission'  => 'boolean',
        'resubmission_count'        => 'integer',
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

    // MODIFIED: Ubah dari hasOne menjadi hasMany karena siswa bisa punya multiple SAW results
    public function sawResults()
    {
        return $this->hasMany(SawResult::class);
    }

    // ADDED: Untuk backward compatibility, ambil SAW result sesuai specialization siswa
    public function sawResult()
    {
        return $this->hasOne(SawResult::class)->where('specialization', $this->specialization);
    }

    public function criterionValues()
    {
        return $this->hasMany(StudentCriterionValue::class);
    }

    public function validationLogs()
    {
        return $this->hasMany(\App\Models\ValidationLog::class)->latest();
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

    public function scopeHasSpecialization($query)
    {
        return $query->whereNotNull('specialization');
    }

    public function scopeWithoutSpecialization($query)
    {
        return $query->whereNull('specialization');
    }

    public function scopeResubmitted($query)
    {
        return $query->where('has_pending_resubmission', true);
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

    public function setSpecialization(string $specialization): bool
    {
        if (!in_array($specialization, ['tahfiz', 'language', 'regular'])) {
            return false;
        }

        return $this->update(['specialization' => $specialization]);
    }

    public function canChangeSpecialization(): bool
    {
        // Tidak bisa ubah jika sudah ada test score
        return !$this->testScore()->exists();
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

    public function isTestScoreCompleted(): bool
    {
        return $this->testScore()->exists();
    }

    public function isRegistrationCompleted(): bool
    {
        return $this->isPersonalDataCompleted()
            && $this->isReportGradeCompleted()
            && $this->isDocumentsCompleted()
            && $this->isSpecializationCompleted();
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
            'can_change' => $this->canChangeSpecialization(),
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

    // MODIFIED: Sesuaikan dengan logika SAW dan Regular
    public function getFinalResult(): array
    {
        // Untuk siswa REGULAR: gunakan final_status dari students table
        if ($this->specialization === 'regular') {
            return [
                'calculated' => !empty($this->final_status),
                'academic_score' => null,
                'test_score' => null,
                'total_score' => null,
                'ranking' => $this->ranking ?? null,
                'class_type' => $this->final_class_type ?? 'regular',
                'status' => $this->final_status ?? 'pending',
            ];
        }

        // Untuk siswa TAHFIZ/LANGUAGE: gunakan SAW Result
        $sawResult = $this->sawResults()
            ->where('academic_year_id', $this->academic_year_id)
            ->where('specialization', $this->specialization)
            ->first();

        if (!$sawResult) {
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

        // Tentukan status berdasarkan ranking dan quota
        $status = $this->determineAcceptanceStatus($sawResult);

        return [
            'calculated' => true,
            'academic_score' => null, // SAW tidak memisahkan academic/test score
            'test_score' => null,
            'total_score' => $sawResult->final_score,
            'ranking' => $sawResult->rank,
            'class_type' => $this->specialization,
            'status' => $status,
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
     | RANKING METHODS (MODIFIED)
     ======================= */

    // MODIFIED: Cek apakah punya SAW result untuk specialization yang dipilih
    public function hasRanking(): bool
    {
        if ($this->specialization === 'regular') {
            return false; // Regular tidak menggunakan ranking
        }

        return $this->sawResults()
            ->where('academic_year_id', $this->academic_year_id)
            ->where('specialization', $this->specialization)
            ->exists();
    }

    // MODIFIED: Ambil SAW result sesuai specialization siswa
    public function getRankingInfo(): ?array
    {
        if ($this->specialization === 'regular') {
            return null; // Regular tidak ada ranking
        }

        $sawResult = $this->sawResults()
            ->where('academic_year_id', $this->academic_year_id)
            ->where('specialization', $this->specialization)
            ->first();

        if (!$sawResult) {
            return null;
        }

        return [
            'rank' => $sawResult->rank,
            'final_score' => $sawResult->final_score,
            'specialization' => $sawResult->specialization,
            'calculated_at' => $sawResult->calculated_at,
            'detail_calculation' => $sawResult->detail_calculation,
        ];
    }

    // ADDED: Get all SAW results (untuk Tahfiz dan Language)
    public function getAllSawResults(): array
    {
        $results = $this->sawResults()
            ->where('academic_year_id', $this->academic_year_id)
            ->get()
            ->keyBy('specialization');

        return [
            'tahfiz' => $results->get('tahfiz'),
            'language' => $results->get('language'),
        ];
    }

    // MODIFIED: Tentukan acceptance status berdasarkan SAW result
    public function isAccepted(): ?bool
    {
        // Regular menggunakan FCFS, bukan SAW
        if ($this->specialization === 'regular') {
            return $this->final_status === 'accepted';
        }

        $sawResult = $this->sawResults()
            ->where('academic_year_id', $this->academic_year_id)
            ->where('specialization', $this->specialization)
            ->first();

        if (!$sawResult || !$this->specialization) {
            return null;
        }

        // Ambil quota dari SpecializationQuota
        $quota = SpecializationQuota::where('academic_year_id', $this->academic_year_id)
            ->where('is_active', true)
            ->first();

        if (!$quota) {
            return null;
        }

        $specializationQuota = match($this->specialization) {
            'tahfiz' => $quota->tahfiz_quota,
            'language' => $quota->language_quota,
            default => null,
        };

        if (!$specializationQuota) {
            return null;
        }

        return $sawResult->rank <= $specializationQuota;
    }

    // MODIFIED: Tentukan status acceptance (accepted/waiting_list/rejected)
    public function getAcceptanceStatus(): string
    {
        // Untuk regular, ambil dari final_status
        if ($this->specialization === 'regular') {
            return $this->final_status ?? 'pending';
        }

        // Untuk tahfiz/language, hitung dari SAW result
        $isAccepted = $this->isAccepted();

        if ($isAccepted === null) {
            return 'pending';
        }

        return $isAccepted ? 'accepted' : 'rejected';
    }

    // ADDED: Helper untuk menentukan status berdasarkan SAW result dan quota
    private function determineAcceptanceStatus($sawResult): string
    {
        $quota = SpecializationQuota::where('academic_year_id', $this->academic_year_id)
            ->where('is_active', true)
            ->first();

        if (!$quota) {
            return 'pending';
        }

        $mainQuota = match($this->specialization) {
            'tahfiz' => $quota->tahfiz_quota,
            'language' => $quota->language_quota,
            default => 0,
        };

        $waitingListQuota = match($this->specialization) {
            'tahfiz' => $quota->tahfiz_waiting_list ?? 0,
            'language' => $quota->language_waiting_list ?? 0,
            default => 0,
        };

        if ($sawResult->rank <= $mainQuota) {
            return 'accepted';
        } elseif ($sawResult->rank <= ($mainQuota + $waitingListQuota)) {
            return 'waiting_list';
        } else {
            return 'rejected';
        }
    }

    public function canResubmit(): bool
    {
        return $this->validation_status === 'invalid'
            && !$this->has_pending_resubmission;
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
        // Show "Diperbaiki" badge on top of pending when resubmission is pending
        if ($this->validation_status === 'pending' && $this->has_pending_resubmission) {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Diperbaiki</span>';
        }

        return match($this->validation_status) {
            'pending' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'valid'   => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Valid</span>',
            'invalid' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Invalid</span>',
            default   => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
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
            'regular' => 'Reguler',
            default => null,
        };
    }

    public function getHasKipAttribute(): bool
    {
        return !empty($this->kip_number);
    }

    public function getAcceptanceStatusBadgeAttribute(): string
    {
        $status = $this->getAcceptanceStatus();

        return match($status) {
            'accepted' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Diterima</span>',
            'waiting_list' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Daftar Tunggu</span>',
            'rejected' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Diterima</span>',
            default => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Menunggu</span>',
        };
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

    /* =======================
     | STATIC QUERIES (MODIFIED)
     ======================= */

    public static function getTotalBySpecialization(int $academicYearId, string $specialization): int
    {
        return self::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->where('validation_status', 'valid')
            ->count();
    }

    // MODIFIED: Sesuaikan dengan SAW results
    public static function getAcceptedCount(int $academicYearId, string $specialization, int $quota): int
    {
        // Untuk regular, hitung dari final_status
        if ($specialization === 'regular') {
            return self::where('academic_year_id', $academicYearId)
                ->where('specialization', 'regular')
                ->where('final_status', 'accepted')
                ->count();
        }

        // Untuk tahfiz/language, hitung dari SAW results
        return SawResult::where('academic_year_id', $academicYearId)
            ->where('specialization', $specialization)
            ->where('rank', '<=', $quota)
            ->count();
    }


    /**
     * Check if SAW calculation has been done for this student
     * 
     * @return bool
     */
    public function hasSawCalculation(): bool
    {
        return $this->sawResults()->exists();
    }

    /**
     * Check if student can edit their data (biodata, grades, specialization, documents)
     * Student cannot edit if SAW calculation has been done
     * 
     * @return array
     */
    public function canEditData(): array
    {
        if ($this->hasSawCalculation()) {
            return [
                'can_edit' => false,
                'reason' => 'Data tidak dapat diubah karena perhitungan nilai SAW sudah dilakukan oleh panitia.',
            ];
        }

        return [
            'can_edit' => true,
            'reason' => null,
        ];
    }
}
