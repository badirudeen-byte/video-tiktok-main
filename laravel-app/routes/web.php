<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/test-upload', [VideoController::class, 'upload'])->name('test.upload');

Route::get('/', [VideoController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::get('/upload/videos', [VideoController::class, 'create_index'])->name('videos.store');
    Route::post('/upload/videos', [VideoController::class, 'store']);
    Route::post('/upload/videos/{video}/like', [VideoController::class, 'like'])->name('videos.like');
    Route::post('/upload/videos/{video}/comment', [VideoController::class, 'comment'])->name('videos.comment');
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
});

require __DIR__.'/auth.php';
