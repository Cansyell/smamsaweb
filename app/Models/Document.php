<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'student_id',
        'document_type',
        'file_name',
        'file_path',
        'validation_status',
        'notes',
    ];

    /* =======================
     | RELATIONSHIP
     ======================= */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /* =======================
     | QUERY SCOPE
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

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeCertificates($query)
    {
        return $query->where('document_type', 'certificate');
    }

    public function scopeReports($query)
    {
        return $query->where('document_type', 'report');
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */
    public static function createDocument(array $data): self
    {
        return self::create([
            'student_id' => $data['student_id'],
            'document_type' => $data['document_type'],
            'file_name' => $data['file_name'],
            'file_path' => $data['file_path'],
            'validation_status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function updateDocument(array $data): bool
    {
        return $this->update($data);
    }

    public function validateDocument(string $status, ?string $notes = null): bool
    {
        if (!in_array($status, ['valid', 'invalid'])) {
            return false;
        }

        return $this->update([
            'validation_status' => $status,
            'notes' => $notes
        ]);
    }

    public function deleteDocument(): bool
    {
        // Hapus file dari storage
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }

        // Hapus record dari database
        return $this->delete();
    }

    /* =======================
     | ACCESSOR
     ======================= */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->validation_status) {
            'pending' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'valid' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Valid</span>',
            'invalid' => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Invalid</span>',
            default => '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'certificate' => 'Ijazah',
            'report' => 'Rapor',
            default => 'Unknown',
        };
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeAttribute(): string
    {
        if (Storage::exists($this->file_path)) {
            $bytes = Storage::size($this->file_path);
            return $this->formatBytes($bytes);
        }
        return 'N/A';
    }

    /* =======================
     | VALIDATION HELPER
     ======================= */
    public function isPending(): bool
    {
        return $this->validation_status === 'pending';
    }

    public function isValid(): bool
    {
        return $this->validation_status === 'valid';
    }

    public function isInvalid(): bool
    {
        return $this->validation_status === 'invalid';
    }

    public function isCertificate(): bool
    {
        return $this->document_type === 'certificate';
    }

    public function isReport(): bool
    {
        return $this->document_type === 'report';
    }

    /* =======================
     | HELPER
     ======================= */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}