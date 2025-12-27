<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'body',
        'image',
        'video_url',
        'is_active',
        'views'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get excerpt from summary or body
     */
    public function getExcerptAttribute(): string
    {
        return $this->summary ?? Str::limit(strip_tags($this->body), 150);
    }

    /**
     * Increment views count
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Scope for active news only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for latest news
     */
    public function scopeLatestNews($query, int $limit = 10)
    {
        return $query->active()->latest()->limit($limit);
    }
}