<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'file_path',
        'is_active',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* =======================
     | RELATIONSHIPS
     ======================= */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* =======================
     | SCOPES
     ======================= */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    /* =======================
     | BUSINESS LOGIC
     ======================= */

    public static function createAnnouncement(array $data): self
    {
        return self::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image_path' => $data['image_path'] ?? null,
            'file_path' => $data['file_path'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'published_at' => $data['published_at'] ?? now(),
            'created_by' => auth()->id(),
        ]);
    }

    public function updateAnnouncement(array $data): bool
    {
        return $this->update($data);
    }

    public function toggleStatus(): bool
    {
        return $this->update(['is_active' => !$this->is_active]);
    }

    public function publish(): bool
    {
        return $this->update([
            'is_active' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish(): bool
    {
        return $this->update([
            'is_active' => false,
        ]);
    }

    /* =======================
     | FILE HANDLING
     ======================= */

    public function deleteImage(): bool
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            Storage::disk('public')->delete($this->image_path);
        }
        return $this->update(['image_path' => null]);
    }

    public function deleteFile(): bool
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
        return $this->update(['file_path' => null]);
    }

    public function deleteAllAttachments(): void
    {
        $this->deleteImage();
        $this->deleteFile();
    }

    /* =======================
     | ACCESSORS
     ======================= */

    public function getStatusBadgeAttribute(): string
    {
        if (!$this->is_active) {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>';
        }

        if ($this->published_at && $this->published_at->isFuture()) {
            return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Terjadwal</span>';
        }

        return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>';
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path 
            ? Storage::disk('public')->url($this->image_path) 
            : null;
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path 
            ? Storage::disk('public')->url($this->file_path) 
            : null;
    }

    public function getFileNameAttribute(): ?string
    {
        return $this->file_path 
            ? basename($this->file_path) 
            : null;
    }

    public function getFileSizeAttribute(): ?string
    {
        if (!$this->file_path || !Storage::disk('public')->exists($this->file_path)) {
            return null;
        }

        $bytes = Storage::disk('public')->size($this->file_path);
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' B';
    }

    public function getExcerptAttribute(): string
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->content), 150);
    }

    public function getPublishedDateAttribute(): string
    {
        if (!$this->published_at) {
            return 'Belum dipublikasi';
        }

        if ($this->published_at->isToday()) {
            return 'Hari ini, ' . $this->published_at->format('H:i');
        }

        if ($this->published_at->isYesterday()) {
            return 'Kemarin, ' . $this->published_at->format('H:i');
        }

        return $this->published_at->format('d M Y, H:i');
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->is_active 
            && $this->published_at 
            && $this->published_at->isPast();
    }

    public function getHasImageAttribute(): bool
    {
        return !empty($this->image_path) 
            && Storage::disk('public')->exists($this->image_path);
    }

    public function getHasFileAttribute(): bool
    {
        return !empty($this->file_path) 
            && Storage::disk('public')->exists($this->file_path);
    }

    /* =======================
     | HELPER METHODS
     ======================= */

    public static function getActiveCount(): int
    {
        return self::active()->published()->count();
    }

    public static function getRecentAnnouncements(int $limit = 5)
    {
        return self::active()
                  ->published()
                  ->latestFirst()
                  ->limit($limit)
                  ->get();
    }

    public static function getLatestForDashboard(int $limit = 3)
    {
        return self::active()
                  ->published()
                  ->with('creator')
                  ->latestFirst()
                  ->limit($limit)
                  ->get();
    }

    /* =======================
     | BOOT METHOD
     ======================= */

    protected static function boot()
    {
        parent::boot();

        // Auto delete files when announcement is deleted
        static::deleting(function ($announcement) {
            $announcement->deleteAllAttachments();
        });
    }
}