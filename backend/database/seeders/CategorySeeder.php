<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Income Categories
        $incomeCategories = [
            'Rent',
            'Late Fees',
            'Application Fees',
            'Pet Fees',
            'Other Income'
        ];

        foreach ($incomeCategories as $category) {
            Category::create([
                'type' => 'income',
                'name' => $category
            ]);
        }

        // Expense Categories
        $expenseCategories = [
            'Electricity',
            'Water',
            'Gas',
            'Maintenance',
            'Repairs',
            'Property Tax',
            'Insurance',
            'Property Management',
            'Cleaning',
            'Landscaping',
            'Security',
            'Other Expenses'
        ];

        foreach ($expenseCategories as $category) {
            Category::create([
                'type' => 'expense',
                'name' => $category
            ]);
        }
    }
}
