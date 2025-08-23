<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitType;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitTypes = [
            [
                'name' => 'Studio',
                'description' => 'Single room apartment with combined living/sleeping area',
                'is_active' => true,
            ],
            [
                'name' => '1 Bedroom',
                'description' => 'Apartment with one separate bedroom',
                'is_active' => true,
            ],
            [
                'name' => '2 Bedroom',
                'description' => 'Apartment with two separate bedrooms',
                'is_active' => true,
            ],
            [
                'name' => '3 Bedroom',
                'description' => 'Apartment with three separate bedrooms',
                'is_active' => true,
            ],
            [
                'name' => 'Office Space',
                'description' => 'Commercial office unit',
                'is_active' => true,
            ],
            [
                'name' => 'Retail Space',
                'description' => 'Commercial retail unit',
                'is_active' => true,
            ],
            [
                'name' => 'Warehouse Space',
                'description' => 'Industrial storage or warehouse unit',
                'is_active' => true,
            ],
            [
                'name' => 'Shop',
                'description' => 'Small retail shop unit',
                'is_active' => true,
            ],
            [
                'name' => 'Suite',
                'description' => 'Luxury or executive suite',
                'is_active' => true,
            ],
            [
                'name' => 'Penthouse',
                'description' => 'Top-floor luxury apartment',
                'is_active' => true,
            ],
            [
                'name' => 'Loft',
                'description' => 'Open-concept living space',
                'is_active' => true,
            ],
            [
                'name' => 'Townhouse',
                'description' => 'Multi-level residential unit',
                'is_active' => true,
            ],
            [
                'name' => 'Medical Office',
                'description' => 'Healthcare or medical office space',
                'is_active' => true,
            ],
            [
                'name' => 'Restaurant Space',
                'description' => 'Commercial restaurant unit',
                'is_active' => true,
            ],
            [
                'name' => 'Storage Unit',
                'description' => 'Self-storage unit',
                'is_active' => true,
            ],
        ];

        foreach ($unitTypes as $unitType) {
            UnitType::updateOrCreate(
                ['name' => $unitType['name']],
                $unitType
            );
        }
    }
}
