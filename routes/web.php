<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Posts
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts.index');
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/{post:slug}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/{post:slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post:slug}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy');

        // Comments
        Route::prefix('{post:slug}/comments')->group(function () {
            Route::post('/', [CommentController::class, 'store'])->name('posts.comments.store');
            Route::put('/{comment}', [CommentController::class, 'update'])->name('posts.comments.update');
            Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');
        });
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
