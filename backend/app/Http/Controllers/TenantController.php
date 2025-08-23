<?php
namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Rental API", version="1.0")
 */
class TenantController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/tenants",
     *   @OA\Response(response=200, description="List tenants")
     * )
     */
    public function index()
    {
        return Tenant::with(['unit' => function($query) {
            $query->select('id', 'unit_number');
        }])->select('id', 'name', 'phone', 'email', 'unit_id', 'start_date', 'end_date')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:tenants,email',
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date'
        ]);
        return Tenant::create($data);
    }

    public function show(Tenant $tenant)
    {
        return $tenant->load('unit','payments');
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'name' => 'sometimes',
            'phone' => 'sometimes',
            'email' => 'sometimes|email|unique:tenants,email,'.$tenant->id,
            'unit_id' => 'sometimes|exists:units,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date'
        ]);
        $tenant->update($data);
        return $tenant;
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return response()->noContent();
    }
}
