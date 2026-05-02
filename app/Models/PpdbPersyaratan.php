<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbPersyaratan extends Model
{
    protected $table = 'ppdb_persyaratan';

    protected $fillable = [
        'tahun_ajaran',
        'dokumen',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    public function scopeAktif($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}