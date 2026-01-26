<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'year',
        'name',
        'start_date',
        'end_date',
        'is_active',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /* =======================
     | RELATIONSHIP
     ======================= */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    // Tambahkan di bagian RELATIONSHIP
    public function specializationQuotas()
    {
        return $this->hasMany(SpecializationQuota::class);
    }

    public function activeSpecializationQuota()
    {
        return $this->hasOne(SpecializationQuota::class)->where('is_active', true);
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

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                     ->where('end_date', '>=', $now);
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */
    public static function createAcademicYear(array $data): self
    {
        return self::create([
            'year'        => $data['year'],
            'name'        => $data['name'] ?? null,
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'is_active'   => $data['is_active'] ?? false,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function updateAcademicYear(array $data): bool
    {
        return $this->update($data);
    }

    public function activate(): bool
    {
        // Nonaktifkan tahun ajaran lain sebelum mengaktifkan yang baru
        self::where('is_active', true)->update(['is_active' => false]);
        
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
            ? '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>'
            : '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>';
    }

    public function getFullYearAttribute(): string
    {
        return $this->name ?? $this->year;
    }

    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }

    public function getDurationInDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /* =======================
     | VALIDATION HELPER
     ======================= */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function isCurrent(): bool
    {
        $now = Carbon::now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function isUpcoming(): bool
    {
        return Carbon::now()->lt($this->start_date);
    }

    public function isPast(): bool
    {
        return Carbon::now()->gt($this->end_date);
    }

    /* =======================
     | HELPER
     ======================= */
    public static function getActiveYear(): ?self
    {
        return self::where('is_active', true)->first();
    }

    public static function getCurrentYear(): ?self
    {
        $now = Carbon::now();
        return self::where('start_date', '<=', $now)
                   ->where('end_date', '>=', $now)
                   ->first();
    }

    public static function generateYearFormat(int $startYear): string
    {
        return $startYear . '/' . ($startYear + 1);
    }
}