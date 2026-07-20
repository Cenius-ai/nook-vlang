<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'slug',
        'view_count',
        'votes_count',
        'accepted_answer_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Question $question) {
            if (empty($question->slug)) {
                $question->slug = static::uniqueSlug($question->title);
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    // ── Relationships ──────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('is_accepted', 'desc')->orderBy('votes_count', 'desc')->orderBy('created_at', 'asc');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'question_tag');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class, 'flaggable');
    }

    public function acceptedAnswer()
    {
        return $this->belongsTo(Answer::class, 'accepted_answer_id');
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeWithTag($query, string $tagSlug)
    {
        return $query->whereHas('tags', fn ($q) => $q->where('slug', $tagSlug));
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', '%' . $term . '%')
              ->orWhere('body', 'like', '%' . $term . '%');
        });
    }

    // ── Accessors ──────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getAnswersCountAttribute(): int
    {
        return $this->answers()->count();
    }
}
