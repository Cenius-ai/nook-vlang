<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Notifications\NewAnswerNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request, Question $question)
    {
        $data = $request->validate([
            'body' => 'required|string|max|50000',
        ]);

        $answer = Answer::create([
            'question_id' => $question->id,
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        // Notify question owner (if different from answerer)
        if ($question->user_id !== auth()->id()) {
            $question->user->notify(new NewAnswerNotification(
                $question->title,
                $question->slug,
                auth()->user()->name,
            ));
        }

        return redirect()->route('questions.show', $question)
            ->with('success', 'Answer posted!');
    }

    public function apiStore(Request $request, Question $question): JsonResponse
    {
        $data = $request->validate([
            'body' => 'required|string|max:50000',
        ]);

        $answer = Answer::create([
            'question_id' => $question->id,
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        if ($question->user_id !== auth()->id()) {
            $question->user->notify(new NewAnswerNotification(
                $question->title,
                $question->slug,
                auth()->user()->name,
            ));
        }

        return response()->json($answer->load('user'), 201);
    }

    public function apiUpdate(Request $request, Answer $answer): JsonResponse
    {
        if ($answer->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $data = $request->validate([
            'body' => 'required|string|max:50000',
        ]);

        $answer->update($data);
        return response()->json($answer->load('user'));
    }

    public function apiDestroy(Request $request, Answer $answer): JsonResponse
    {
        if ($answer->user_id !== auth()->id() && !auth()->user()->isModerator()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $answer->delete();
        return response()->json(['message' => 'Answer deleted.']);
    }
}
