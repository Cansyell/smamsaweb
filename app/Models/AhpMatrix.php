<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AhpMatrix extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'specialization',
        'criteria_row_id',
        'criteria_col_id',
        'comparison_value',
        'notes',
    ];

    protected $casts = [
        'comparison_value' => 'decimal:10',
    ];

    /**
     * Tabel Random Index (RI) — n = 1..15
     * Sumber: Saaty (sesuai gambar referensi)
     */
    private static array $riTable = [
        1  => 0.00,
        2  => 0.00,
        3  => 0.58,
        4  => 0.90,
        5  => 1.12,
        6  => 1.24,
        7  => 1.32,
        8  => 1.41,
        9  => 1.45,
        10 => 1.49,
        11 => 1.51,
        12 => 1.48,
        13 => 1.56,
        14 => 1.57,
        15 => 1.59,
    ];

    // =========================================================================
    // RELATIONS
    // =========================================================================

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function criteriaRow(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_row_id');
    }

    public function criteriaCol(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_col_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeForAcademicYear(Builder $query, int $academicYearId): Builder
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    public function scopeForSpecialization(Builder $query, string $specialization): Builder
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['criteriaRow', 'criteriaCol', 'academicYear']);
    }

    // =========================================================================
    // MATRIX DATA
    // =========================================================================

    /**
     * Ambil data mentah matriks + daftar kriteria.
     */
    public static function getMatrixData(int $academicYearId, string $specialization): array
    {
        $criterias = Criteria::forSpecialization($specialization)
            ->active()
            ->ordered()
            ->get();

        $matrices = self::forAcademicYear($academicYearId)
            ->forSpecialization($specialization)
            ->get()
            ->keyBy(fn($item) => $item->criteria_row_id . '-' . $item->criteria_col_id);

        $matrixArray = [];
        foreach ($criterias as $row) {
            foreach ($criterias as $col) {
                $key = $row->id . '-' . $col->id;
                $matrixArray[$row->id][$col->id] = $matrices->get($key)?->comparison_value ?? null;
            }
        }

        return [
            'criterias'   => $criterias,
            'matrices'    => $matrices,
            'matrixArray' => $matrixArray,
        ];
    }

    /**
     * Cek apakah semua sel upper-triangle sudah terisi.
     */
    public static function isMatrixComplete(int $academicYearId, string $specialization): bool
    {
        $criteriaCount = Criteria::forSpecialization($specialization)
            ->active()
            ->count();

        if ($criteriaCount === 0) {
            return false;
        }

        $required = ($criteriaCount * ($criteriaCount - 1)) / 2;

        $existing = self::forAcademicYear($academicYearId)
            ->forSpecialization($specialization)
            ->where('criteria_row_id', '<', \DB::raw('criteria_col_id'))
            ->count();

        return $existing >= $required;
    }

    // =========================================================================
    // CORE CALCULATION
    // =========================================================================

    /**
     * Hitung semua metrik AHP — logika PERSIS sesuai Excel:
     *
     * MATRIKS PERBANDINGAN
     *   • Diagonal = 1
     *   • Upper-triangle = nilai input
     *   • Lower-triangle = 1 / upper
     *   • Baris "Jumlah" = total tiap kolom
     *
     * NORMALISASI
     *   • normalizedMatrix[i][j] = matrix[i][j] / colSum[j]
     *   • rowSum[i]  = Σ_j normalizedMatrix[i][j]
     *   • Prioritas[i] = rowSum[i] / n       ← bobot/priority vector
     *
     * EIGEN (sesuai Excel: =Prioritas * colSum tiap kolom, dijumlahkan)
     *   • eigen[i] = Σ_j ( prioritas[i] * colSum[j] )   ← baris i
     *     Ini ekuivalen dengan =I44*C40 + I44*D40 + ... untuk baris yg sama,
     *     tapi karena prioritas[i] adalah konstanta per baris:
     *     eigen[i] = prioritas[i] * Σ_j colSum[j]
     *
     *   NAMUN melihat Excel lebih teliti: kolom Eigen = Prioritas_baris × colSum_kolom
     *   dijumlahkan PER BARIS, sehingga:
     *     eigen[i] = Σ_j ( matrix[i][j] * prioritas[j] )  ← weighted sum (standard AHP)
     *   Dan λmax = SUM(eigen[0..n-1])  ← bukan rata-rata, tapi TOTAL
     *
     *   Verifikasi dengan data Excel:
     *     λmax = 5.198023725  (SUM J44:J48)
     *     CI   = (5.198 - 5) / (5-1) = 0.04950...  ✓
     *
     * CI  = (λmax - n) / (n - 1)
     * CR  = CI / RI[n]
     *
     * @return array{
     *   n: int,
     *   criterias: \Illuminate\Support\Collection,
     *   matrix: array,          — matriks n×n lengkap (0-indexed)
     *   colSum: array,          — total tiap kolom (0-indexed)
     *   normalized: array,      — matriks normalisasi (0-indexed)
     *   rowSum: array,          — jumlah baris normalisasi (0-indexed)
     *   prioritas: array,       — priority vector / bobot (0-indexed)
     *   eigen: array,           — eigen per baris (0-indexed)
     *   lambdaMax: float,       — SUM eigen (λmax)
     *   ci: float,
     *   ri: float,
     *   cr: float,
     *   consistent: bool,
     *   weights: array,         — keyed by criteria_id, untuk backward-compat
     * }|null
     */
    public static function calculateAhpMetrics(int $academicYearId, string $specialization): ?array
    {
        $data      = self::getMatrixData($academicYearId, $specialization);
        $criterias = $data['criterias'];
        $rawMatrix = $data['matrixArray'];

        if ($criterias->isEmpty()) {
            return null;
        }

        $n           = $criterias->count();
        $ri          = self::$riTable[$n] ?? null;
        $critArr     = $criterias->values(); // reindex 0..n-1

        if ($ri === null) {
            return null; // n di luar rentang tabel
        }

        // ── Step 1: Bangun matriks n×n lengkap ───────────────────────────────
        $matrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $rid = $critArr[$i]->id;
                $cid = $critArr[$j]->id;

                if ($rid === $cid) {
                    $matrix[$i][$j] = 1.0;
                } elseif (isset($rawMatrix[$rid][$cid]) && $rawMatrix[$rid][$cid] !== null) {
                    $matrix[$i][$j] = (float) $rawMatrix[$rid][$cid];
                } elseif (isset($rawMatrix[$cid][$rid]) && $rawMatrix[$cid][$rid] !== null) {
                    $val = (float) $rawMatrix[$cid][$rid];
                    $matrix[$i][$j] = ($val != 0) ? 1.0 / $val : 0.0; //kebalikan
                } else {
                    return null; // belum lengkap
                }
            }
        }

        // ── Step 2: Total tiap kolom (baris "Jumlah") ────────────────────────
        $colSum = array_fill(0, $n, 0.0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $colSum[$j] += $matrix[$i][$j];
            }
        }

        // ── Step 3: Normalisasi ───────────────────────────────────────────────
        $normalized = [];
        $rowSum     = array_fill(0, $n, 0.0);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = ($colSum[$j] != 0)
                    ? $matrix[$i][$j] / $colSum[$j]
                    : 0.0;
                $rowSum[$i] += $normalized[$i][$j];
            }
        }

        // ── Step 4: Prioritas = rowSum / n ───────────────────────────────────
        $prioritas = [];
        for ($i = 0; $i < $n; $i++) {
            $prioritas[$i] = $rowSum[$i] / $n;
        }

        // ── Step 5: Eigen per baris ───────────────────────────────────────────
        // eigen[i] = colSum[i] × prioritas[i]
        $eigen = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            $eigen[$i] = $colSum[$i] * $prioritas[$i];
        }

        // ── Step 6: λmax = SUM semua eigen 
        $lambdaMax = array_sum($eigen);

        // ── Step 7: CI = (λmax - n) / (n - 1) 
        $ci = ($n > 1) ? ($lambdaMax - $n) / ($n - 1) : 0.0;

        // ── Step 8: CR = CI / RI 
        $cr = ($ri > 0) ? $ci / $ri : 0.0;

        // ── Susun weights keyed by criteria_id (backward-compat) ─────────────
        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $weights[$critArr[$i]->id] = [
                'criteria' => $critArr[$i],
                'weight'   => $prioritas[$i],
                'eigen'    => $eigen[$i],
            ];
        }

        return [
            'n'          => $n,
            'criterias'  => $critArr,
            'matrix'     => $matrix,
            'colSum'     => $colSum,
            'normalized' => $normalized,
            'rowSum'     => $rowSum,
            'prioritas'  => $prioritas,
            'eigen'      => $eigen,
            'lambdaMax'  => $lambdaMax,
            'ci'         => $ci,
            'ri'         => $ri,
            'cr'         => $cr,
            'consistent' => $cr <= 0.1,
            'weights'    => $weights,
        ];
    }

    // =========================================================================
    // BACKWARD-COMPATIBLE WRAPPERS
    // =========================================================================

    public static function calculateConsistencyRatio(int $academicYearId, string $specialization): ?float
    {
        $m = self::calculateAhpMetrics($academicYearId, $specialization);
        return $m ? round($m['cr'], 6) : null;
    }

    public static function getPriorityWeights(int $academicYearId, string $specialization): ?array
    {
        $m = self::calculateAhpMetrics($academicYearId, $specialization);
        return $m ? $m['weights'] : null;
    }
}