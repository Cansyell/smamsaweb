<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'actor_id',
        'action',
        'previous_status',
        'new_status',
        'notes',
        'resubmission_count',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'approved'      => 'Divalidasi',
            'rejected'      => 'Ditolak',
            'resubmitted'   => 'Data Diperbaiki',
            'doc_validated' => 'Dokumen Divalidasi',
            'doc_rejected'  => 'Dokumen Ditolak',
            default         => ucfirst($this->action),
        };
    }

    public function getActionBadgeAttribute(): string
    {
        return match($this->action) {
            'approved'      => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">Divalidasi</span>',
            'rejected'      => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-semibold">Ditolak</span>',
            'resubmitted'   => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-semibold">Data Diperbaiki</span>',
            'doc_validated' => '<span class="px-2 py-1 text-xs rounded-full bg-teal-100 text-teal-800 font-semibold">Dok. Valid</span>',
            'doc_rejected'  => '<span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800 font-semibold">Dok. Ditolak</span>',
            default         => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 font-semibold">'.ucfirst($this->action).'</span>',
        };
    }
}