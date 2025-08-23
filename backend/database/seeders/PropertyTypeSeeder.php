<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyTypes = [
            [
                'name' => 'Residential',
                'description' => 'Single-family homes, apartments, condominiums',
                'is_active' => true,
            ],
            [
                'name' => 'Commercial',
                'description' => 'Office buildings, retail spaces, warehouses',
                'is_active' => true,
            ],
            [
                'name' => 'Industrial',
                'description' => 'Manufacturing facilities, industrial parks',
                'is_active' => true,
            ],
            [
                'name' => 'Mixed-Use',
                'description' => 'Combination of residential and commercial spaces',
                'is_active' => true,
            ],
            [
                'name' => 'Retail',
                'description' => 'Shopping centers, malls, storefronts',
                'is_active' => true,
            ],
            [
                'name' => 'Office',
                'description' => 'Office buildings, business centers',
                'is_active' => true,
            ],
            [
                'name' => 'Warehouse',
                'description' => 'Storage facilities, distribution centers',
                'is_active' => true,
            ],
            [
                'name' => 'Land',
                'description' => 'Vacant land, development sites',
                'is_active' => true,
            ],
            [
                'name' => 'Hospitality',
                'description' => 'Hotels, motels, resorts',
                'is_active' => true,
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Medical offices, clinics, healthcare facilities',
                'is_active' => true,
            ],
        ];

        foreach ($propertyTypes as $propertyType) {
            PropertyType::updateOrCreate(
                ['name' => $propertyType['name']],
                $propertyType
            );
        }
    }
}
