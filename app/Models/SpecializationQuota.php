<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecializationQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'tahfiz_quota',
        'language_quota',
        'regular_quota',
        'is_active',
    ];

    protected $casts = [
        'tahfiz_quota' => 'integer',
        'language_quota' => 'integer',
        'regular_quota' => 'integer',
        'is_active' => 'boolean',
    ];

    /* =======================
     | RELATIONSHIP
     ======================= */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /* =======================
     | QUERY SCOPE
     ======================= */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByAcademicYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */
    public static function createQuota(array $data): self
    {
        return self::create([
            'academic_year_id' => $data['academic_year_id'],
            'tahfiz_quota' => $data['tahfiz_quota'],
            'language_quota' => $data['language_quota'],
            'regular_quota' => $data['regular_quota'],
            'is_active' => $data['is_active'] ?? false,
        ]);
    }

    public function updateQuota(array $data): bool
    {
        return $this->update($data);
    }

    public function activate(): bool
    {
        // Nonaktifkan quota lain di tahun ajaran yang sama
        self::where('academic_year_id', $this->academic_year_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);
        
        return $this->update(['is_active' => true]);
    }

    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /* =======================
     | ACCESSOR
     ======================= */
    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active 
            ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>'
            : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>';
    }

    public function getTotalQuotaAttribute(): int
    {
        return $this->tahfiz_quota + $this->language_quota + $this->regular_quota;
    }

    public function getTahfizPercentageAttribute(): float
    {
        if ($this->total_quota === 0) {
            return 0;
        }
        return round(($this->tahfiz_quota / $this->total_quota) * 100, 2);
    }

    public function getLanguagePercentageAttribute(): float
    {
        if ($this->total_quota === 0) {
            return 0;
        }
        return round(($this->language_quota / $this->total_quota) * 100, 2);
    }

    public function getRegularPercentageAttribute(): float
    {
        if ($this->total_quota === 0) {
            return 0;
        }
        return round(($this->regular_quota / $this->total_quota) * 100, 2);
    }

    /**
     * Get quota for specific specialization
     */
    public function getQuotaBySpecialization(string $specialization): int
    {
        return match($specialization) {
            'tahfiz' => $this->tahfiz_quota,
            'language' => $this->language_quota,
            'regular' => $this->regular_quota,
            default => 0,
        };
    }

    /**
     * Get all quotas as array
     */
    public function getQuotasArray(): array
    {
        return [
            'tahfiz' => $this->tahfiz_quota,
            'language' => $this->language_quota,
            'regular' => $this->regular_quota,
        ];
    }

    /* =======================
     | VALIDATION HELPER
     ======================= */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function hasAvailableTahfizQuota(int $required = 1): bool
    {
        return $this->tahfiz_quota >= $required;
    }

    public function hasAvailableLanguageQuota(int $required = 1): bool
    {
        return $this->language_quota >= $required;
    }

    public function hasAvailableRegularQuota(int $required = 1): bool
    {
        return $this->regular_quota >= $required;
    }

    public function hasAvailableQuota(string $specialization, int $required = 1): bool
    {
        return match($specialization) {
            'tahfiz' => $this->hasAvailableTahfizQuota($required),
            'language' => $this->hasAvailableLanguageQuota($required),
            'regular' => $this->hasAvailableRegularQuota($required),
            default => false,
        };
    }

    /* =======================
     | HELPER
     ======================= */
    public static function getActiveByAcademicYear(int $academicYearId): ?self
    {
        return self::where('academic_year_id', $academicYearId)
                   ->where('is_active', true)
                   ->first();
    }

    public static function checkQuotaExists(int $academicYearId): bool
    {
        return self::where('academic_year_id', $academicYearId)->exists();
    }

    /**
     * Get quota information for all specializations
     */
    public static function getQuotaInformation(int $academicYearId): array
    {
        $quotaModel = self::getActiveByAcademicYear($academicYearId);

        if (!$quotaModel) {
            // Return default values if no quota is set
            return [
                'tahfiz' => ['quota' => 0, 'registered' => 0, 'accepted' => 0, 'available' => 0, 'percentage' => 0],
                'language' => ['quota' => 0, 'registered' => 0, 'accepted' => 0, 'available' => 0, 'percentage' => 0],
                'regular' => ['quota' => 0, 'registered' => 0, 'accepted' => 0, 'available' => 0, 'percentage' => 0],
            ];
        }

        $result = [];
        $quotas = $quotaModel->getQuotasArray();

        foreach ($quotas as $specialization => $quota) {
            // Total yang mendaftar
            $registered = \App\Models\Student::where('academic_year_id', $academicYearId)
                ->where('specialization', $specialization)
                ->where('validation_status', 'valid')
                ->count();

            // Yang diterima = berdasarkan ranking <= kuota
            $accepted = \App\Models\SawResult::where('academic_year_id', $academicYearId)
                ->where('specialization', $specialization)
                ->where('rank', '<=', $quota)
                ->count();

            $result[$specialization] = [
                'quota' => $quota,
                'registered' => $registered,
                'accepted' => $accepted,
                'available' => max(0, $quota - $accepted),
                'percentage' => $quota > 0 ? round(($accepted / $quota) * 100, 1) : 0,
            ];
        }

        return $result;
    }
}