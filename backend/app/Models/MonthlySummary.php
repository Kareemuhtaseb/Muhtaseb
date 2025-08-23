<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'month_year',
        'total_income',
        'total_expenses',
        'net_income',
        'total_units',
        'occupied_units',
        'occupancy_rate'
    ];

    protected $casts = [
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_income' => 'decimal:2',
        'occupancy_rate' => 'decimal:2'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public static function generateForMonth($propertyId, $month, $year)
    {
        $monthYear = sprintf('%04d-%02d', $year, $month);
        
        $property = Property::find($propertyId);
        if (!$property) return null;

        $totalIncome = Income::where('property_id', $propertyId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $totalExpenses = Expense::where('property_id', $propertyId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $totalUnits = $property->units()->count();
        $occupiedUnits = $property->units()->where('status', 'occupied')->count();
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0;

        return self::updateOrCreate(
            ['property_id' => $propertyId, 'month_year' => $monthYear],
            [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_income' => $totalIncome - $totalExpenses,
                'total_units' => $totalUnits,
                'occupied_units' => $occupiedUnits,
                'occupancy_rate' => $occupancyRate
            ]
        );
    }
}
