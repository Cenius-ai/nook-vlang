<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (Tag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // ── Relationships ──────────────────────────────

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_tag');
    }

    // ── Accessors ──────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getQuestionsCountAttribute(): int
    {
        return $this->questions()->count();
    }
}
