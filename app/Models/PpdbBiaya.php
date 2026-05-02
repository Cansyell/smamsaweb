<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbBiaya extends Model
{
    protected $table = 'ppdb_biaya';

    protected $fillable = [
        'tahun_ajaran',
        'nama_biaya',
        'nominal',
        'keterangan',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'nominal'   => 'integer',
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    /**
     * Format nominal ke Rupiah.
     */
    public function getNominalRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}