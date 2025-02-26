<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Tasks Routes
Route::view('tasks', 'tasks.index')
    ->middleware(['auth'])
    ->name('tasks.index');

Route::view('tasks/add', 'tasks.add')
    ->middleware(['auth'])
    ->name('tasks.add');

// Pets Routes
Route::view('pets', 'pets.add')
    ->middleware(['auth'])
    ->name('pets.add');

require __DIR__.'/auth.php';
