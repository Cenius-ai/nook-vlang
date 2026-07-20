<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::withCount('questions')
            ->orderBy('questions_count', 'desc')
            ->get();

        return view('tags.index', compact('tags'));
    }

    public function show(Tag $tag, Request $request): View
    {
        $questions = Question::with(['user', 'tags'])
            ->withCount('answers')
            ->withTag($tag->slug)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tags.show', compact('tag', 'questions'));
    }

    public function apiIndex(): JsonResponse
    {
        $tags = Tag::withCount('questions')
            ->orderBy('questions_count', 'desc')
            ->get();

        return response()->json($tags);
    }

    public function apiShow(Tag $tag): JsonResponse
    {
        return response()->json($tag->loadCount('questions'));
    }
}
