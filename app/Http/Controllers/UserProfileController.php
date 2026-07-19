<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    // ── Web views ─────────────────────────────────

    public function show(User $user): View
    {
        $questions = $user->questions()
            ->with(['tags'])
            ->withCount('answers')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $answers = $user->answers()
            ->with('question')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $stats = [
            'questions_count' => $user->questions()->count(),
            'answers_count' => $user->answers()->count(),
            'total_votes' => $user->questions()->sum('votes_count') + $user->answers()->sum('votes_count'),
        ];

        return view('users.profile', compact('user', 'questions', 'answers', 'stats'));
    }

    public function edit(): View
    {
        $user = auth()->user();
        $questions = $user->questions()
            ->with(['tags'])
            ->withCount('answers')
            ->orderBy('created_at', 'desc')
            ->get();

        $answers = $user->answers()
            ->with('question')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'questions_count' => $user->questions()->count(),
            'answers_count' => $user->answers()->count(),
            'total_votes' => $user->questions()->sum('votes_count') + $user->answers()->sum('votes_count'),
        ];

        return view('users.profile', compact('user', 'questions', 'answers', 'stats'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($data);

        return redirect()->route('profile')->with('success', 'Profile updated.');
    }

    // ── API ───────────────────────────────────────

    public function apiShow(User $user): JsonResponse
    {
        return response()->json([
            'user' => $user->only('id', 'name', 'username', 'bio', 'role', 'created_at'),
            'stats' => [
                'questions_count' => $user->questions()->count(),
                'answers_count' => $user->answers()->count(),
                'total_votes' => $user->questions()->sum('votes_count') + $user->answers()->sum('votes_count'),
            ],
        ]);
    }

    public function apiEdit(): JsonResponse
    {
        return response()->json(auth()->user()->only('id', 'name', 'username', 'email', 'bio', 'role'));
    }

    public function apiUpdate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($data);
        return response()->json(auth()->user()->only('id', 'name', 'username', 'email', 'bio', 'role'));
    }
}
