<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Notifications\VoteReceivedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'votable_type' => 'required|string|in:App\\Models\\Question,App\\Models\\Answer',
            'votable_id' => 'required|integer',
            'value' => 'required|integer|in:1,-1',
        ]);

        $votable = $data['votable_type']::findOrFail($data['votable_id']);

        $existing = Vote::where([
            'user_id' => auth()->id(),
            'votable_id' => $data['votable_id'],
            'votable_type' => $data['votable_type'],
        ])->first();

        if ($existing) {
            if ($existing->value === $data['value']) {
                // Toggle off
                $existing->delete();
                $votable->decrement('votes_count', $data['value']);
                return response()->json(['message' => 'Vote removed.', 'votes_count' => $votable->votes_count]);
            } else {
                // Change vote direction
                $oldValue = $existing->value;
                $existing->update(['value' => $data['value']]);
                $votable->increment('votes_count', $data['value'] - $oldValue);
            }
        } else {
            Vote::create([
                'user_id' => auth()->id(),
                'votable_id' => $data['votable_id'],
                'votable_type' => $data['votable_type'],
                'value' => $data['value'],
            ]);
            $votable->increment('votes_count', $data['value']);
        }

        // Notify content owner
        $owner = $votable->user;
        if ($owner && $owner->id !== auth()->id()) {
            $votableTitle = $votable instanceof \App\Models\Question
                ? $votable->title
                : \Illuminate\Support\Str::limit($votable->body, 80);
            $votableSlug = $votable instanceof \App\Models\Question
                ? $votable->slug
                : $votable->question->slug;

            $owner->notify(new VoteReceivedNotification(
                $data['votable_type'],
                $votableTitle,
                $votableSlug,
                $data['value'],
            ));
        }

        return response()->json([
            'message' => 'Vote recorded.',
            'votes_count' => $votable->fresh()->votes_count,
        ]);
    }
}
