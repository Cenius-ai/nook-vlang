<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// ── Auth routes ────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.submit');
    Route::get('/register', fn () => view('auth.register'))->name('register');
    Route::post('/register', [AuthController::class, 'webRegister'])->name('register.submit');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// ── Public routes ───────────────────────────────
Route::get('/', [QuestionController::class, 'index'])->name('questions.index');
Route::get('/questions/{question:slug}', [QuestionController::class, 'show'])->name('questions.show');
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/users/{user:username}', [UserProfileController::class, 'show'])->name('users.profile');

// ── Authenticated routes ────────────────────────
Route::middleware('auth')->group(function () {
    // Questions
    Route::get('/ask', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

    // Answers
    Route::post('/questions/{question:slug}/answers', [AnswerController::class, 'store'])->name('answers.store');

    // Profile
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Admin / Moderator
    Route::middleware('role')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/flags', [\App\Http\Controllers\Admin\FlagController::class, 'index'])->name('flags.index');
        Route::put('/flags/{flag}/resolve', [\App\Http\Controllers\Admin\FlagController::class, 'resolve'])->name('admin.flags.resolve');
    });
});
