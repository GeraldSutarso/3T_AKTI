<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

Route::post('/login/send-code', [AuthController::class, 'sendVerificationCode'])->name('login.sendCode');

Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('verify.form');
Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verify.code');

route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route (Protected)
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('admin.home');

// API Route for Dashboard Data (For Vue)
Route::middleware('auth')->get('/dashboard-data', [DashboardController::class, 'dashboardData']);

// Default Homepage
Route::middleware('auth')->get('/', [DashboardController::class, 'index'])->name('home');
