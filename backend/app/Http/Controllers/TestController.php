<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        $start = microtime(true);
        
        // Simple query
        $units = Unit::all();
        
        $end = microtime(true);
        $queryTime = round(($end - $start) * 1000, 2);
        
        return response()->json([
            'query_time_ms' => $queryTime,
            'count' => $units->count(),
            'data' => $units
        ]);
    }

    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()
        ]);
    }
}
