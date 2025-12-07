<?php

use App\Http\Controllers\Admin\Dashboard\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::controller(AdminTicketController::class)
            ->prefix('tickets')
            ->name('tickets.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{ticket}', 'show')->name('show');
                Route::post('approve/{ticket}', 'approve')->name('approve');
                Route::post('reject/{ticket}', 'reject')->name('reject');
            });

        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    });

// User routes
Route::middleware('auth')
    ->group(function () {
        Route::controller(TicketController::class)
            ->prefix('tickets')
            ->name('tickets.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{ticket}', 'show')->name('show');
            });

        Route::get('/dashboard', DashboardController::class)->name('dashboard');
    });


require __DIR__ . '/auth.php';
