<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth:sanctum', 'verified'])->get('/admin', function () {
    return Inertia::render('AdminDashboard');
})->name('admin.dashboard');
