<?php
namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Owner;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index()
    {
        return Distribution::with('owner')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'date' => 'required|date',
            'amount' => 'required|numeric'
        ]);
        return Distribution::create($data);
    }

    public function show(Distribution $distribution)
    {
        return $distribution->load('owner');
    }

    public function update(Request $request, Distribution $distribution)
    {
        $data = $request->validate([
            'owner_id' => 'sometimes|exists:owners,id',
            'date' => 'sometimes|date',
            'amount' => 'sometimes|numeric'
        ]);
        $distribution->update($data);
        return $distribution;
    }

    public function destroy(Distribution $distribution)
    {
        $distribution->delete();
        return response()->noContent();
    }

    public function calculate()
    {
        $net = Payment::sum('amount') - Expense::sum('amount');
        return Owner::all()->map(function ($owner) use ($net) {
            return [
                'owner_id' => $owner->id,
                'name' => $owner->name,
                'amount' => round($net * ($owner->share_ratio/100),2)
            ];
        });
    }
}
