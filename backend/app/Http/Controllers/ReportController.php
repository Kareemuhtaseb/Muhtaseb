<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function netIncome()
    {
        $net = Payment::sum('amount') - Expense::sum('amount');
        return ['net_income' => $net];
    }

    public function monthly()
    {
        $payments = Payment::selectRaw('to_char(payment_date, "YYYY-MM") as month, SUM(amount) as total')
            ->groupBy('month')->get();
        $expenses = Expense::selectRaw('to_char(date, "YYYY-MM") as month, SUM(amount) as total')
            ->groupBy('month')->get();
        return ['payments' => $payments, 'expenses' => $expenses];
    }
}
