<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodolistController;

// Papan penunjuk arah URL
Route::get('/', [TodolistController::class, 'index'])->name('todos.index');
Route::post('/todos', [TodolistController::class, 'store'])->name('todos.store');
Route::patch('/todos/{id}/toggle', [TodolistController::class, 'toggleComplete'])->name('todos.toggle');
Route::delete('/todos/{id}', [TodolistController::class, 'destroy'])->name('todos.destroy');
Route::put('/todos/{id}', [TodolistController::class, 'update'])->name('todos.update');