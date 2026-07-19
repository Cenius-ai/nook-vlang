<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    // ── Web views ─────────────────────────────────

    public function index(Request $request): View
    {
        $query = Question::with(['user', 'tags'])
            ->withCount('answers')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tag')) {
            $query->withTag($request->tag);
        }

        $questions = $query->paginate(15);
        $tags = Tag::withCount('questions')->orderBy('questions_count', 'desc')->limit(20)->get();

        return view('questions.index', compact('questions', 'tags'));
    }

    public function show(Question $question): View
    {
        $question->increment('view_count');
        $question->load(['user', 'tags', 'answers.user', 'answers.votes', 'acceptedAnswer']);

        return view('questions.show', compact('question'));
    }

    public function create(): View
    {
        $tags = Tag::orderBy('name')->get();
        return view('questions.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:300',
            'body' => 'required|string|max:50000',
            'tags' => 'nullable|string|max:500',
        ]);

        $question = Question::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        $this->syncTags($question, $data['tags'] ?? '');

        return redirect()->route('questions.show', $question)
            ->with('success', 'Question posted!');
    }

    // ── API endpoints ─────────────────────────────

    public function apiIndex(Request $request): JsonResponse
    {
        $query = Question::with(['user', 'tags'])
            ->withCount('answers')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tag')) {
            $query->withTag($request->tag);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'votes' => $query->orderBy('votes_count', 'desc'),
            'active' => $query->orderBy('updated_at', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return response()->json($query->paginate(15));
    }

    public function apiShow(Question $question): JsonResponse
    {
        $question->load(['user', 'tags', 'answers.user', 'answers.votes', 'acceptedAnswer']);
        return response()->json($question);
    }

    public function apiStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:300',
            'body' => 'required|string|max:50000',
            'tags' => 'nullable|string|max:500',
        ]);

        $question = Question::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        $this->syncTags($question, $data['tags'] ?? '');

        return response()->json($question->load(['user', 'tags']), 201);
    }

    public function apiUpdate(Request $request, Question $question): JsonResponse
    {
        if ($question->user_id !== auth()->id() && !auth()->user()->isModerator()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|string|max:300',
            'body' => 'sometimes|string|max:50000',
            'tags' => 'nullable|string|max:500',
        ]);

        $question->update($data);

        if ($request->has('tags')) {
            $this->syncTags($question, $data['tags'] ?? '');
        }

        return response()->json($question->load(['user', 'tags']));
    }

    public function apiDestroy(Request $request, Question $question): JsonResponse
    {
        if ($question->user_id !== auth()->id() && !auth()->user()->isModerator()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $question->delete();
        return response()->json(['message' => 'Question deleted.']);
    }

    // ── Helpers ───────────────────────────────────

    private function syncTags(Question $question, string $tagString): void
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagString)));
        $tagIds = [];

        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(
                ['name' => $name],
                ['slug' => \Illuminate\Support\Str::slug($name)]
            );
            $tagIds[] = $tag->id;
        }

        $question->tags()->sync($tagIds);
    }
}
