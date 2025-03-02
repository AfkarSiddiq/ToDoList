<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodolistController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/index', [TodolistController::class, 'index'])->name('todolist.index');
Route::post('/store', [TodolistController::class, 'store'])->name('todolist.create');
Route::put('/edit/{id}', [TodolistController::class, 'update']);
Route::delete('/delete/{id}', [TodolistController::class, 'destroy'])->name('todolist.destroy');
Route::put('/update/{id}', [TodolistController::class, 'ubahTodo'])->name('todolist.ubah');
require __DIR__.'/auth.php';
