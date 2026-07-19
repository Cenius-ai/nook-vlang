<?php

use App\Http\Controllers\AcceptAnswerController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\FlagController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

// ── Auth ────────────────────────────────────────
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

// ── Public ──────────────────────────────────────
Route::get('/questions', [QuestionController::class, 'apiIndex']);
Route::get('/questions/{question:slug}', [QuestionController::class, 'apiShow']);
Route::get('/tags', [TagController::class, 'apiIndex']);
Route::get('/tags/{tag:slug}', [TagController::class, 'apiShow']);
Route::get('/search', [SearchController::class, 'apiIndex']);
Route::get('/users/{user:username}', [UserProfileController::class, 'apiShow']);

// ── Authenticated ───────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/profile', [UserProfileController::class, 'apiEdit']);
    Route::put('/profile', [UserProfileController::class, 'apiUpdate']);

    // Questions
    Route::post('/questions', [QuestionController::class, 'apiStore']);
    Route::put('/questions/{question:slug}', [QuestionController::class, 'apiUpdate']);
    Route::delete('/questions/{question:slug}', [QuestionController::class, 'apiDestroy']);

    // Answers
    Route::post('/questions/{question:slug}/answers', [AnswerController::class, 'apiStore']);
    Route::put('/answers/{answer}', [AnswerController::class, 'apiUpdate']);
    Route::delete('/answers/{answer}', [AnswerController::class, 'apiDestroy']);

    // Accept answer
    Route::put('/questions/{question:slug}/accept-answer', [AcceptAnswerController::class, 'store']);

    // Voting
    Route::post('/votes', [VoteController::class, 'store']);

    // Flags
    Route::post('/flags', [FlagController::class, 'store']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'apiIndex']);
    Route::put('/notifications/{id}/mark-read', [NotificationController::class, 'apiMarkRead']);
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'apiMarkAllRead']);

    // Admin
    Route::middleware('role')->prefix('admin')->group(function () {
        Route::get('/flags', [\App\Http\Controllers\Admin\FlagController::class, 'apiIndex']);
        Route::put('/flags/{flag}/resolve', [\App\Http\Controllers\Admin\FlagController::class, 'apiResolve']);
    });
});
