<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcceptAnswerController extends Controller
{
    public function store(Request $request, Question $question): JsonResponse
    {
        if ($question->user_id !== auth()->id()) {
            return response()->json(['message' => 'Only the question asker can accept an answer.'], 403);
        }

        $data = $request->validate([
            'answer_id' => 'required|integer|exists:answers,id',
        ]);

        $answer = $question->answers()->findOrFail($data['answer_id']);

        // Un-accept previous answer if any
        $question->answers()->where('is_accepted', true)->update(['is_accepted' => false]);

        $answer->update(['is_accepted' => true]);
        $question->update(['accepted_answer_id' => $answer->id]);

        return response()->json([
            'message' => 'Answer accepted.',
            'answer' => $answer,
        ]);
    }
}
