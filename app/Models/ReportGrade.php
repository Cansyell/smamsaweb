<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportGrade extends Model
{
    use HasFactory;

    protected $table = 'report_grades';

    protected $fillable = [
        'student_id',
        'islamic_studies',
        'indonesian_language',
        'english_language',
        'average_grade',
    ];

    protected $casts = [
        'islamic_studies' => 'decimal:2',
        'indonesian_language' => 'decimal:2',
        'english_language' => 'decimal:2',
        'average_grade' => 'decimal:2',
    ];

    /* =======================
     | RELATIONSHIP
     ======================= */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */
    public static function createGrade(array $data): self
    {
        $averageGrade = ($data['islamic_studies'] + $data['indonesian_language'] + $data['english_language']) / 3;
        
        return self::create([
            'student_id' => $data['student_id'],
            'islamic_studies' => $data['islamic_studies'],
            'indonesian_language' => $data['indonesian_language'],
            'english_language' => $data['english_language'],
            'average_grade' => round($averageGrade, 2),
        ]);
    }

    public function updateGrade(array $data): bool
    {
        $averageGrade = ($data['islamic_studies'] + $data['indonesian_language'] + $data['english_language']) / 3;
        
        $data['average_grade'] = round($averageGrade, 2);
        
        return $this->update($data);
    }

    /* =======================
     | ACCESSOR
     ======================= */
    public function getIsCompleteAttribute(): bool
    {
        return $this->islamic_studies !== null 
            && $this->indonesian_language !== null 
            && $this->english_language !== null;
    }

    public function getGradeStatusAttribute(): string
    {
        if (!$this->average_grade) {
            return 'Belum Lengkap';
        }

        if ($this->average_grade >= 85) {
            return 'Sangat Baik';
        } elseif ($this->average_grade >= 75) {
            return 'Baik';
        } elseif ($this->average_grade >= 65) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }

    public function getGradeBadgeAttribute(): string
    {
        if (!$this->average_grade) {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum Lengkap</span>';
        }

        if ($this->average_grade >= 85) {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sangat Baik</span>';
        } elseif ($this->average_grade >= 75) {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Baik</span>';
        } elseif ($this->average_grade >= 65) {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Cukup</span>';
        } else {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Kurang</span>';
        }
    }
}