<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return Unit::with('tenants')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_number' => 'required|unique:units,unit_number',
            'size' => 'required|numeric',
            'monthly_rent' => 'required|numeric',
            'status' => 'required|in:available,occupied,maintenance'
        ]);
        return Unit::create($data);
    }

    public function show(Unit $unit)
    {
        return $unit->load('tenants');
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'unit_number' => 'sometimes|unique:units,unit_number,'.$unit->id,
            'size' => 'sometimes|numeric',
            'monthly_rent' => 'sometimes|numeric',
            'status' => 'sometimes|in:available,occupied,maintenance'
        ]);
        $unit->update($data);
        return $unit;
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->noContent();
    }
}
