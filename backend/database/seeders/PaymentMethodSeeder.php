<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash',
                'description' => 'Physical cash payment',
                'is_active' => true,
            ],
            [
                'name' => 'Check',
                'description' => 'Paper check payment',
                'is_active' => true,
            ],
            [
                'name' => 'Bank Transfer',
                'description' => 'Electronic bank transfer',
                'is_active' => true,
            ],
            [
                'name' => 'Credit Card',
                'description' => 'Credit card payment',
                'is_active' => true,
            ],
            [
                'name' => 'Debit Card',
                'description' => 'Debit card payment',
                'is_active' => true,
            ],
            [
                'name' => 'Online Payment',
                'description' => 'Online payment gateway',
                'is_active' => true,
            ],
            [
                'name' => 'Money Order',
                'description' => 'Money order payment',
                'is_active' => true,
            ],
            [
                'name' => 'Cashier\'s Check',
                'description' => 'Cashier\'s check payment',
                'is_active' => true,
            ],
            [
                'name' => 'Wire Transfer',
                'description' => 'Wire transfer payment',
                'is_active' => true,
            ],
            [
                'name' => 'Digital Wallet',
                'description' => 'Digital wallet payment (PayPal, Venmo, etc.)',
                'is_active' => true,
            ],
        ];

        foreach ($paymentMethods as $paymentMethod) {
            PaymentMethod::updateOrCreate(
                ['name' => $paymentMethod['name']],
                $paymentMethod
            );
        }
    }
}
