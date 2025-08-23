<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    public function index(): JsonResponse
    {
        $tenants = Tenant::orderBy('full_name')->paginate(50);
        return response()->json($tenants);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'lease_start' => 'nullable|date',
            'lease_end' => 'nullable|date',
            'deposit' => 'nullable|numeric|min:0'
        ]);

        $tenant = Tenant::create($validated);
        return response()->json($tenant, 201);
    }

    public function show(Tenant $tenant): JsonResponse
    {
        return response()->json($tenant);
    }

    public function update(Request $request, Tenant $tenant): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'contact' => 'sometimes|string|max:255',
            'lease_start' => 'sometimes|date',
            'lease_end' => 'sometimes|date',
            'deposit' => 'sometimes|numeric|min:0'
        ]);

        $tenant->update($validated);
        return response()->json($tenant);
    }

    public function destroy(Tenant $tenant): JsonResponse
    {
        $tenant->delete();
        return response()->json(null, 204);
    }
}


