<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TenantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Property Financial Management Routes (auth enabled)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Properties
    Route::apiResource('properties', PropertyController::class);
    Route::get('properties/{property}/financial-report', [PropertyController::class, 'financialReport']);
    Route::get('properties/{property}/units', [PropertyController::class, 'units']);
    Route::get('properties/{property}/income', [PropertyController::class, 'income']);
    Route::get('properties/{property}/expenses', [PropertyController::class, 'expenses']);
    Route::get('properties/{property}/contracts', [PropertyController::class, 'contracts']);
    Route::get('properties/{property}/statistics', [PropertyController::class, 'statistics']);
    Route::get('properties/financial-summary', [PropertyController::class, 'financialSummary']);
    
    // Units
    Route::apiResource('units', UnitController::class);
    Route::get('units/{unit}/financial-report', [UnitController::class, 'financialReport']);
    Route::get('units/{unit}/contracts', [UnitController::class, 'contracts']);
    Route::get('units/{unit}/income', [UnitController::class, 'income']);
    Route::get('units/{unit}/expenses', [UnitController::class, 'expenses']);
    Route::get('units/{unit}/statistics', [UnitController::class, 'statistics']);
    
    // Contracts
    Route::apiResource('contracts', ContractController::class);
    Route::get('contracts/variance-analysis', [ContractController::class, 'varianceAnalysis']);
    Route::get('contracts/expiring-soon', [ContractController::class, 'expiringSoon']);
    Route::get('contracts/statistics', [ContractController::class, 'statistics']);
    Route::post('contracts/{contract}/renew', [ContractController::class, 'renew']);
    Route::post('contracts/{contract}/cancel', [ContractController::class, 'cancel']);
    
    // Owners
    Route::apiResource('owners', OwnerController::class);
    Route::get('owners/{owner}/distributions', [OwnerController::class, 'distributions']);
    
    // Income
    Route::apiResource('income', IncomeController::class);
    Route::get('income/by-month/{year}/{month}', [IncomeController::class, 'byMonth']);
    
    // Expenses
    Route::apiResource('expenses', ExpenseController::class);
    Route::get('expenses/by-category', [ExpenseController::class, 'byCategory']);
    
    // Payments
    Route::apiResource('payments', PaymentController::class);
    Route::get('payments/by-date-range', [PaymentController::class, 'byDateRange']);
    
    // Invoices
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send']);
    
    // Reports
    Route::get('reports/monthly/{year}/{month}', [ReportController::class, 'monthly']);
    Route::get('reports/yearly/{year}', [ReportController::class, 'yearly']);
    Route::get('reports/owner/{owner}', [ReportController::class, 'owner']);
    
    // Distributions
    Route::apiResource('distributions', DistributionController::class);
    Route::post('distributions/calculate', [DistributionController::class, 'calculate']);
    
    // Maintenance
    Route::apiResource('maintenance', MaintenanceController::class);
    Route::post('maintenance/{maintenance}/assign', [MaintenanceController::class, 'assign']);
    
    // Notifications
    Route::apiResource('notifications', NotificationController::class);
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    
    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Tenants
    Route::apiResource('tenants', TenantController::class);
});

// Auth routes
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');
