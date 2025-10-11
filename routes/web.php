<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsersController;
use Inertia\Inertia;

// Default redirect based on role
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('register.thanks');
    }

    return redirect()->route('register');
})->name('home');


Route::get('thanks', function () {
    return Inertia::render('auth/Thanks');
})->name('register.thanks');

// Admin dashboard routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
    Route::get('/users/export', [UsersController::class, 'export'])->name('admin.users.export');
});
// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified', 'admin'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
