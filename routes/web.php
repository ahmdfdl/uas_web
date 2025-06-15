<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsApprovalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// =======================
// Public Routes
// =======================
Route::get('/', function () {
    return view('welcome');
});

// =======================
// Authentication Routes
// =======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// =======================
// Authenticated Routes
// =======================
Route::middleware(['auth'])->group(function () {

    // Dashboard Redirector
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Role-based Dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('can:manage-users')->name('admin.dashboard');

    Route::get('/editor/dashboard', [DashboardController::class, 'editor'])
        ->middleware('can:approve-news')->name('editor.dashboard');

    Route::get('/wartawan/dashboard', [DashboardController::class, 'wartawan'])
        ->middleware('can:create-news')->name('wartawan.dashboard');

    Route::get('/user/dashboard', [DashboardController::class, 'user'])
        ->name('user.dashboard'); // optional: ->middleware('can:view-user-dashboard')

    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('users.updateProfile');
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('users.changePassword');

    // News CRUD (all roles)
    Route::resource('news', NewsController::class);

    // Submit draft ke editor (wartawan)
    Route::post('news/{news}/submit', [NewsController::class, 'submit'])
        ->middleware('can:create-news')
        ->name('news.submit');

    // News Approval (editor only)
    Route::prefix('news/approvals')->middleware('can:approve-news')->group(function () {
        Route::get('/', [NewsApprovalController::class, 'index'])->name('news.approvals.index');
        Route::match(['get', 'post'], '/{news}/approve', [NewsApprovalController::class, 'approve'])
            ->name('news.approvals.approve');
        Route::match(['get', 'post'], '/{news}/reject', [NewsApprovalController::class, 'reject'])
            ->name('news.approvals.reject');
    });

    // Admin-only: Manage Users
    Route::prefix('admin')->middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class);
    });
});