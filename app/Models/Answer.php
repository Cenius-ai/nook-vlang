<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'body',
        'votes_count',
        'is_accepted',
    ];

    protected function casts(): array
    {
        return [
            'is_accepted' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function flags()
    {
        return $this->morphMany(Flag::class, 'flaggable');
    }
}
