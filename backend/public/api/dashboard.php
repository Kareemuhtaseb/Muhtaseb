<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Mock data for testing the enhanced UI
        $currentMonth = date('Y-m');
        $previousMonth = date('Y-m', strtotime('-1 month'));
        
        // Generate realistic mock data
        $totalIncome = 125000;
        $totalExpenses = 45000;
        $netIncome = $totalIncome - $totalExpenses;
        
        $currentMonthIncome = 15000;
        $currentMonthExpenses = 5000;
        $currentMonthNet = $currentMonthIncome - $currentMonthExpenses;
        
        $previousMonthIncome = 13500;
        $previousMonthExpenses = 4800;
        $previousMonthNet = $previousMonthIncome - $previousMonthExpenses;
        
        // Properties and units
        $properties = 3;
        $units = 45;
        $occupiedUnits = 42;
        $occupancyRate = round(($occupiedUnits / $units) * 100, 2);
        
        // Owner distributions
        $ownerDistributions = [
            [
                'owner_id' => 1,
                'name' => 'John Doe',
                'share_percentage' => 60,
                'expected_income' => 9000,
                'actual_distributed' => 8500,
                'balance_owed' => 500
            ],
            [
                'owner_id' => 2,
                'name' => 'Jane Smith',
                'share_percentage' => 40,
                'expected_income' => 6000,
                'actual_distributed' => 5800,
                'balance_owed' => 200
            ]
        ];
        
        // Monthly chart data (last 12 months)
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $baseIncome = 12000 + ($i * 500);
            $baseExpenses = 4000 + ($i * 200);
            
            $monthlyData[] = [
                'month' => $month,
                'income' => $baseIncome + rand(-500, 500),
                'expenses' => $baseExpenses + rand(-200, 200),
                'net' => ($baseIncome + rand(-500, 500)) - ($baseExpenses + rand(-200, 200))
            ];
        }
        
        // Category breakdown
        $incomeCategories = [
            ['name' => 'Rental Income', 'total' => 12000],
            ['name' => 'Service Fees', 'total' => 3000]
        ];
        
        $expenseCategories = [
            ['name' => 'Maintenance', 'total' => 3000],
            ['name' => 'Utilities', 'total' => 2000]
        ];
        
        $dashboard = [
            'overview' => [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_income' => $netIncome,
                'properties' => $properties,
                'units' => $units,
                'occupied_units' => $occupiedUnits,
                'occupancy_rate' => $occupancyRate
            ],
            'current_month' => [
                'income' => $currentMonthIncome,
                'expenses' => $currentMonthExpenses,
                'net' => $currentMonthNet
            ],
            'comparisons' => [
                'vs_previous_month' => [
                    'income_change' => $currentMonthIncome - $previousMonthIncome,
                    'expenses_change' => $currentMonthExpenses - $previousMonthExpenses,
                    'net_change' => $currentMonthNet - $previousMonthNet
                ],
                'vs_last_year' => [
                    'income_change' => $currentMonthIncome - 12000,
                    'expenses_change' => $currentMonthExpenses - 4500,
                    'net_change' => $currentMonthNet - 7500
                ]
            ],
            'owner_distributions' => $ownerDistributions,
            'monthly_chart_data' => $monthlyData,
            'category_breakdown' => [
                'income' => $incomeCategories,
                'expenses' => $expenseCategories
            ]
        ];
        
        echo json_encode($dashboard);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
