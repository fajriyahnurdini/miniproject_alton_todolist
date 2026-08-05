<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TodolistController;

// Route bawaan dari Laravel UI (Login, Register, Logout)
Auth::routes();

// Semua fitur To-Do List dilindungi middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/', [TodolistController::class, 'index'])->name('todos.index');
    Route::post('/todos', [TodolistController::class, 'store'])->name('todos.store');
    Route::patch('/todos/{id}/toggle', [TodolistController::class, 'toggleComplete'])->name('todos.toggle');
    Route::put('/todos/{id}', [TodolistController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{id}', [TodolistController::class, 'destroy'])->name('todos.destroy');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');