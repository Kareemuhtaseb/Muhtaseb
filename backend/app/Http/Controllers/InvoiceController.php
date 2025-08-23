<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $invoices = Invoice::with(['owner', 'category'])->latest()->paginate(15);
        return response()->json($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,cancelled'
        ]);

        $invoice = Invoice::create($validated);
        return response()->json($invoice->load(['owner', 'category']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json($invoice->load(['owner', 'category']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice): JsonResponse
    {
        $validated = $request->validate([
            'owner_id' => 'sometimes|exists:owners,id',
            'category_id' => 'sometimes|exists:categories,id',
            'amount' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string|max:255',
            'due_date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,paid,overdue,cancelled'
        ]);

        $invoice->update($validated);
        return response()->json($invoice->load(['owner', 'category']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->delete();
        return response()->json(null, 204);
    }

    /**
     * Send invoice to owner
     */
    public function send(Invoice $invoice): JsonResponse
    {
        // Here you would implement the logic to send the invoice
        // For now, we'll just update the status
        $invoice->update(['status' => 'sent']);
        
        return response()->json([
            'message' => 'Invoice sent successfully',
            'invoice' => $invoice->load(['owner', 'category'])
        ]);
    }
}
