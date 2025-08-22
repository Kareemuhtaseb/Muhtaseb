<?php
namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        return Owner::with('distributions')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'share_ratio' => 'required|numeric|min:0|max:100',
            'email' => 'required|email'
        ]);
        return Owner::create($data);
    }

    public function show(Owner $owner)
    {
        return $owner->load('distributions');
    }

    public function update(Request $request, Owner $owner)
    {
        $data = $request->validate([
            'name' => 'sometimes',
            'share_ratio' => 'sometimes|numeric|min:0|max:100',
            'email' => 'sometimes|email'
        ]);
        $owner->update($data);
        return $owner;
    }

    public function destroy(Owner $owner)
    {
        $owner->delete();
        return response()->noContent();
    }
}
