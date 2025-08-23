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
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommercialComplexController;

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

// Commercial Complex Routes (New Structure)
Route::middleware(['auth:sanctum'])->group(function () {
    // Commercial Complex Management
    Route::apiResource('commercial-complexes', CommercialComplexController::class);
    Route::post('commercial-complexes/{id}/import-excel', [CommercialComplexController::class, 'importExcel']);
    Route::get('commercial-complexes/{id}/dashboard', [CommercialComplexController::class, 'dashboard']);
    
    // Shops
    Route::apiResource('shops', \App\Http\Controllers\ShopController::class);
    Route::get('shops/{id}/contracts', [\App\Http\Controllers\ShopController::class, 'contracts']);
    
    // Companies
    Route::apiResource('companies', \App\Http\Controllers\CompanyController::class);
    Route::get('companies/{id}/contracts', [\App\Http\Controllers\CompanyController::class, 'contracts']);
    
    // Contracts
    Route::apiResource('contracts', \App\Http\Controllers\ContractController::class);
    Route::post('contracts/{id}/renew', [\App\Http\Controllers\ContractController::class, 'renew']);
    Route::post('contracts/{id}/terminate', [\App\Http\Controllers\ContractController::class, 'terminate']);
    
    // Monthly Incomes
    Route::apiResource('monthly-incomes', \App\Http\Controllers\MonthlyIncomeController::class);
    Route::get('monthly-incomes/by-month/{year}/{month}', [\App\Http\Controllers\MonthlyIncomeController::class, 'byMonth']);
    
    // Income Transactions
    Route::apiResource('income-transactions', \App\Http\Controllers\IncomeTransactionController::class);
    Route::get('income-transactions/by-date-range', [\App\Http\Controllers\IncomeTransactionController::class, 'byDateRange']);
    
    // Complex Expenses
    Route::apiResource('complex-expenses', \App\Http\Controllers\ComplexExpenseController::class);
    Route::get('complex-expenses/by-category', [\App\Http\Controllers\ComplexExpenseController::class, 'byCategory']);
});

// Legacy Routes (keeping for backward compatibility)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Properties
    Route::apiResource('properties', PropertyController::class);
    Route::get('properties/{property}/units', [PropertyController::class, 'units']);
    Route::get('properties/{property}/income', [PropertyController::class, 'income']);
    Route::get('properties/{property}/expenses', [PropertyController::class, 'expenses']);
    
    // Units
    Route::apiResource('units', UnitController::class);
    Route::get('units/{unit}/leases', [UnitController::class, 'leases']);
    Route::get('units/{unit}/payments', [UnitController::class, 'payments']);
    
    // Tenants
    Route::apiResource('tenants', TenantController::class);
    Route::get('tenants/{tenant}/leases', [TenantController::class, 'leases']);
    Route::get('tenants/{tenant}/payments', [TenantController::class, 'payments']);
    
    // Owners
    Route::apiResource('owners', OwnerController::class);
    Route::get('owners/{owner}/properties', [OwnerController::class, 'properties']);
    Route::get('owners/{owner}/distributions', [OwnerController::class, 'distributions']);
    
    // Leases
    Route::apiResource('leases', LeaseController::class);
    Route::post('leases/{lease}/renew', [LeaseController::class, 'renew']);
    Route::post('leases/{lease}/terminate', [LeaseController::class, 'terminate']);
    
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
    Route::post('notifications/mark-read', [NotificationController::class, 'markRead']);
    
    // Categories
    Route::apiResource('categories', CategoryController::class);
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
