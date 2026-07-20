<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->get('q', '');

        $questions = collect();
        if (mb_strlen(trim($q)) > 0) {
            $questions = Question::with(['user', 'tags'])
                ->withCount('answers')
                ->search(trim($q))
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->appends(['q' => $q]);
        }

        return view('search.results', compact('questions', 'q'));
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $q = $request->get('q', '');

        if (mb_strlen(trim($q)) === 0) {
            return response()->json(['data' => []]);
        }

        $questions = Question::with(['user', 'tags'])
            ->withCount('answers')
            ->search(trim($q))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($questions);
    }
}
