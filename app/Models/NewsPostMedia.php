<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class NewsPostMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_post_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'media_type',
        'size',
        'sort_order',
    ];

    protected $appends = [
        'url',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(NewsPost::class, 'news_post_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getSizeLabelAttribute(): string
    {
        $bytes = max(0, (int) $this->size);

        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / (1024 * 1024), 1) . ' MB';
        }

        return number_format($bytes / 1024, 1) . ' KB';
    }
}
