<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User,Unit,Tenant,Payment,Expense,Owner,Distribution};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);

        $unit1 = Unit::create([
            'unit_number' => 'A1',
            'size' => 50,
            'monthly_rent' => 1000,
            'status' => 'available'
        ]);
        $unit2 = Unit::create([
            'unit_number' => 'B1',
            'size' => 75,
            'monthly_rent' => 1500,
            'status' => 'occupied'
        ]);

        $tenant = Tenant::create([
            'name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'unit_id' => $unit2->id,
            'start_date' => now()->subYear()->toDateString(),
            'end_date' => null
        ]);

        Payment::create([
            'tenant_id' => $tenant->id,
            'payment_date' => now()->toDateString(),
            'amount' => 1500,
            'method' => 'cash'
        ]);

        Expense::create([
            'category' => 'Maintenance',
            'date' => now()->toDateString(),
            'amount' => 200,
            'notes' => 'Fixing leaks'
        ]);

        $owner1 = Owner::create([
            'name' => 'Alice',
            'share_ratio' => 60,
            'email' => 'alice@example.com'
        ]);
        $owner2 = Owner::create([
            'name' => 'Bob',
            'share_ratio' => 40,
            'email' => 'bob@example.com'
        ]);

        Distribution::create([
            'owner_id' => $owner1->id,
            'date' => now()->toDateString(),
            'amount' => 900
        ]);
        Distribution::create([
            'owner_id' => $owner2->id,
            'date' => now()->toDateString(),
            'amount' => 600
        ]);
    }
}
