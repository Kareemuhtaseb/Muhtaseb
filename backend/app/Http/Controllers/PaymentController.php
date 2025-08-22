<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::with('tenant')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric',
            'method' => 'required|in:cash,bank_transfer,credit_card',
            'notes' => 'nullable'
        ]);
        return Payment::create($data);
    }

    public function show(Payment $payment)
    {
        return $payment->load('tenant');
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'tenant_id' => 'sometimes|exists:tenants,id',
            'payment_date' => 'sometimes|date',
            'amount' => 'sometimes|numeric',
            'method' => 'sometimes|in:cash,bank_transfer,credit_card',
            'notes' => 'nullable'
        ]);
        $payment->update($data);
        return $payment;
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }
}
