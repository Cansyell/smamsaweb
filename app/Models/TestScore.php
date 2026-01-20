<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestScore extends Model
{
    use HasFactory;

    protected $table = 'test_scores';

    protected $fillable = [
        'student_id',
        'quran_achievement',
        'quran_reading',
        'interview',
        'public_speaking',
        'dialogue',
        'average_score',
        'input_by',
    ];

    protected $casts = [
        'quran_achievement' => 'decimal:2',
        'quran_reading' => 'decimal:2',
        'interview' => 'decimal:2',
        'public_speaking' => 'decimal:2',
        'dialogue' => 'decimal:2',
        'average_score' => 'decimal:2',
    ];

    /* =======================
     | RELATIONSHIP
     ======================= */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */
    public static function createTestScore(array $data): self
    {
        $testScore = self::create([
            'student_id' => $data['student_id'],
            'quran_achievement' => $data['quran_achievement'] ?? 0,
            'quran_reading' => $data['quran_reading'] ?? 0,
            'interview' => $data['interview'] ?? 0,
            'public_speaking' => $data['public_speaking'] ?? 0,
            'dialogue' => $data['dialogue'] ?? 0,
            'input_by' => $data['input_by'] ?? null,
        ]);

        // Hitung rata-rata otomatis
        $testScore->calculateAverage();

        return $testScore;
    }

    public function updateTestScore(array $data): bool
    {
        $updated = $this->update($data);

        if ($updated) {
            $this->calculateAverage();
        }

        return $updated;
    }

    public function calculateAverage(): bool
    {
        $scores = [
            $this->quran_achievement,
            $this->quran_reading,
            $this->interview,
            $this->public_speaking,
            $this->dialogue,
        ];

        // Filter hanya nilai yang > 0
        $validScores = array_filter($scores, fn($score) => $score > 0);

        if (count($validScores) > 0) {
            $average = array_sum($validScores) / count($validScores);
            return $this->update(['average_score' => round($average, 2)]);
        }

        return false;
    }

    /* =======================
     | ACCESSOR
     ======================= */
    public function getFormattedAverageAttribute(): string
    {
        return number_format($this->average_score ?? 0, 2);
    }

    public function getGradeAttribute(): string
    {
        $avg = $this->average_score ?? 0;

        return match(true) {
            $avg >= 90 => 'A',
            $avg >= 80 => 'B',
            $avg >= 70 => 'C',
            $avg >= 60 => 'D',
            default => 'E',
        };
    }

    public function getGradeBadgeAttribute(): string
    {
        $grade = $this->grade;

        return match($grade) {
            'A' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">A</span>',
            'B' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">B</span>',
            'C' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">C</span>',
            'D' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">D</span>',
            default => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">E</span>',
        };
    }

    /* =======================
     | VALIDATION HELPER
     ======================= */
    public function isComplete(): bool
    {
        return $this->quran_achievement > 0 &&
               $this->quran_reading > 0 &&
               $this->interview > 0 &&
               $this->public_speaking > 0 &&
               $this->dialogue > 0;
    }

    public function isPassing(float $passingGrade = 70): bool
    {
        return ($this->average_score ?? 0) >= $passingGrade;
    }
}