<?php

namespace App\Http\Controllers;

use App\Models\Flag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'flaggable_type' => 'required|string|in:App\\Models\\Question,App\\Models\\Answer',
            'flaggable_id' => 'required|integer',
            'reason' => 'required|string|max:1000',
        ]);

        $flaggable = $data['flaggable_type']::findOrFail($data['flaggable_id']);

        // Prevent duplicate pending flags from same user on same item
        $existing = Flag::where([
            'user_id' => auth()->id(),
            'flaggable_id' => $data['flaggable_id'],
            'flaggable_type' => $data['flaggable_type'],
            'status' => 'pending',
        ])->first();

        if ($existing) {
            return response()->json(['message' => 'You have already flagged this content.'], 409);
        }

        Flag::create([
            'user_id' => auth()->id(),
            'flaggable_id' => $data['flaggable_id'],
            'flaggable_type' => $data['flaggable_type'],
            'reason' => $data['reason'],
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Content flagged. A moderator will review it.'], 201);
    }
}
