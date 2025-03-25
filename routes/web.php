<?php

use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlShortenerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::delete('/links/{id}', [LinkController::class, 'destroy'])->name('links.destroy');
});

Route::get('/shorten', [UrlShortenerController::class, 'showForm'])->name('shorten.form');
Route::post('/shorten', [UrlShortenerController::class, 'handleForm'])->name('shorten.handle');

require __DIR__.'/auth.php';
