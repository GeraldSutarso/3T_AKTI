<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\PeriodController;

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
Route::middleware('auth')->prefix('/api/charts')->group(function () {
    Route::get('/kpi', [ChartController::class, 'getKpiCharts']);
    Route::get('/body-data', [ChartController::class, 'getBodyDataCharts']);
    Route::get('/physical', [ChartController::class, 'getPhysicalCharts']);
});

// Show the KPI form page (Blade + Vue)
Route::get('/form/kpi', function () {
    return view('forms.kpi'); // Make sure this view exists
});

// // API to get users for the current period
// Route::get('/api/current-period-users', [PeriodController::class, 'getCurrentPeriodUsers']);

// // API to save KPI data
// Route::post('/api/kpis', [KpiController::class, 'store']);

// Route::middleware('auth')->group(function () {
//     Route::get('/periods/current', [KpiController::class, 'getCurrentPeriod']);
//     Route::get('/users/kpi-data', [KpiController::class, 'getKpiData']);
//     Route::post('/kpi/update-field', [KpiController::class, 'updateKpiField']);
// });
// Route::get('/periods/thresholds', [KpiController::class, 'getPeriodThresholds']);

Route::get('/kpi/users', [KpiController::class, 'getCurrentGenUsers']);
Route::get('/kpi/period', [KpiController::class, 'getCurrentPeriod']);
Route::get('/kpi/minimum-values', [KpiController::class, 'getMinimumValues']);
Route::post('/kpi/store', [KpiController::class, 'store']);
Route::get('/kpi/generation/{gen_id}', [KpiController::class, 'getGeneration']);
