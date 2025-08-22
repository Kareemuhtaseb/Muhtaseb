<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    TenantController,
    UnitController,
    PaymentController,
    ExpenseController,
    OwnerController,
    DistributionController,
    ReportController
};

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('tenants', TenantController::class);
Route::apiResource('units', UnitController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('expenses', ExpenseController::class);
Route::apiResource('owners', OwnerController::class);
Route::apiResource('distributions', DistributionController::class);

Route::get('distributions/calculate', [DistributionController::class, 'calculate']);
Route::get('net-income', [ReportController::class, 'netIncome']);
Route::get('reports/monthly', [ReportController::class, 'monthly']);
