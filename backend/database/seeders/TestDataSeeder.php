<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Owner;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\Notification;
use App\Models\Category;
use App\Models\PropertyType;
use App\Models\UnitType;
use App\Models\PaymentMethod;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create Property Types
        $propertyTypes = [
            'Apartment Complex',
            'Office Building',
            'Retail Space',
            'Warehouse',
            'Residential House'
        ];

        foreach ($propertyTypes as $type) {
            PropertyType::firstOrCreate(['name' => $type]);
        }

        // Create Unit Types
        $unitTypes = [
            'Studio',
            '1 Bedroom',
            '2 Bedroom',
            '3 Bedroom',
            'Office Suite',
            'Retail Unit',
            'Warehouse Space'
        ];

        foreach ($unitTypes as $type) {
            UnitType::firstOrCreate(['name' => $type]);
        }

        // Create Payment Methods
        $paymentMethods = [
            'Cash',
            'Bank Transfer',
            'Credit Card',
            'Check',
            'Online Payment'
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(['name' => $method]);
        }

        // Create Owners
        $owners = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+1-555-0101',
                'address' => '123 Main St, City, State 12345',
                'tax_id' => 'TAX123456789',
                'bank_account' => '1234567890',
                'ownership_percentage' => 100,
                'is_active' => true
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '+1-555-0102',
                'address' => '456 Oak Ave, City, State 12345',
                'tax_id' => 'TAX987654321',
                'bank_account' => '0987654321',
                'ownership_percentage' => 100,
                'is_active' => true
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@email.com',
                'phone' => '+1-555-0103',
                'address' => '789 Pine Rd, City, State 12345',
                'tax_id' => 'TAX456789123',
                'bank_account' => '1122334455',
                'ownership_percentage' => 100,
                'is_active' => true
            ]
        ];

        foreach ($owners as $ownerData) {
            Owner::create($ownerData);
        }

        // Create Properties
        $properties = [
            [
                'name' => 'Sunset Apartments',
                'address' => '100 Sunset Blvd, Los Angeles, CA 90210',
                'property_type_id' => PropertyType::where('name', 'Apartment Complex')->first()->id,
                'total_units' => 24,
                'occupied_units' => 20,
                'total_area' => 24000,
                'year_built' => 2015,
                'purchase_price' => 2500000,
                'current_value' => 3200000,
                'monthly_rent_income' => 48000,
                'monthly_expenses' => 12000,
                'property_tax_rate' => 1.2,
                'insurance_rate' => 0.5,
                'management_fee_rate' => 8.0,
                'is_active' => true,
                'description' => 'Modern apartment complex with amenities'
            ],
            [
                'name' => 'Downtown Office Plaza',
                'address' => '500 Business Ave, New York, NY 10001',
                'property_type_id' => PropertyType::where('name', 'Office Building')->first()->id,
                'total_units' => 12,
                'occupied_units' => 10,
                'total_area' => 50000,
                'year_built' => 2010,
                'purchase_price' => 5000000,
                'current_value' => 6500000,
                'monthly_rent_income' => 125000,
                'monthly_expenses' => 25000,
                'property_tax_rate' => 1.5,
                'insurance_rate' => 0.8,
                'management_fee_rate' => 10.0,
                'is_active' => true,
                'description' => 'Premium office space in downtown'
            ],
            [
                'name' => 'Riverside Retail Center',
                'address' => '300 Commerce St, Chicago, IL 60601',
                'property_type_id' => PropertyType::where('name', 'Retail Space')->first()->id,
                'total_units' => 8,
                'occupied_units' => 7,
                'total_area' => 15000,
                'year_built' => 2018,
                'purchase_price' => 1800000,
                'current_value' => 2200000,
                'monthly_rent_income' => 35000,
                'monthly_expenses' => 8000,
                'property_tax_rate' => 1.1,
                'insurance_rate' => 0.6,
                'management_fee_rate' => 8.5,
                'is_active' => true,
                'description' => 'Shopping center with multiple retail units'
            ]
        ];

        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }

        // Create Units for each property
        $properties = Property::all();
        foreach ($properties as $property) {
            $unitTypes = UnitType::all();
            $totalUnits = $property->total_units;
            
            for ($i = 1; $i <= $totalUnits; $i++) {
                $unitType = $unitTypes->random();
                $baseRent = $this->getBaseRent($unitType->name);
                
                Unit::create([
                    'property_id' => $property->id,
                    'unit_number' => $property->name . ' - Unit ' . $i,
                    'unit_type_id' => $unitType->id,
                    'floor_number' => rand(1, 5),
                    'area' => rand(500, 2000),
                    'bedrooms' => $this->getBedrooms($unitType->name),
                    'bathrooms' => $this->getBathrooms($unitType->name),
                    'monthly_rent' => $baseRent + rand(-200, 200),
                    'security_deposit' => $baseRent,
                    'is_occupied' => $i <= $property->occupied_units,
                    'is_active' => true,
                    'description' => $unitType->name . ' unit with modern amenities'
                ]);
            }
        }

        // Create Tenants
        $tenants = [
            [
                'name' => 'Alice Wilson',
                'email' => 'alice.wilson@email.com',
                'phone' => '+1-555-0201',
                'address' => '100 Sunset Blvd, Unit 1A, Los Angeles, CA 90210',
                'emergency_contact' => 'Bob Wilson',
                'emergency_phone' => '+1-555-0202',
                'employment_status' => 'Employed',
                'monthly_income' => 4500,
                'credit_score' => 750,
                'is_active' => true
            ],
            [
                'name' => 'David Chen',
                'email' => 'david.chen@email.com',
                'phone' => '+1-555-0203',
                'address' => '100 Sunset Blvd, Unit 2B, Los Angeles, CA 90210',
                'emergency_contact' => 'Lisa Chen',
                'emergency_phone' => '+1-555-0204',
                'employment_status' => 'Self-Employed',
                'monthly_income' => 6000,
                'credit_score' => 780,
                'is_active' => true
            ],
            [
                'name' => 'TechStart Inc.',
                'email' => 'office@techstart.com',
                'phone' => '+1-555-0205',
                'address' => '500 Business Ave, Suite 301, New York, NY 10001',
                'emergency_contact' => 'CEO Office',
                'emergency_phone' => '+1-555-0206',
                'employment_status' => 'Business',
                'monthly_income' => 50000,
                'credit_score' => 800,
                'is_active' => true
            ],
            [
                'name' => 'Fashion Boutique LLC',
                'email' => 'contact@fashionboutique.com',
                'phone' => '+1-555-0207',
                'address' => '300 Commerce St, Unit 5, Chicago, IL 60601',
                'emergency_contact' => 'Store Manager',
                'emergency_phone' => '+1-555-0208',
                'employment_status' => 'Business',
                'monthly_income' => 25000,
                'credit_score' => 720,
                'is_active' => true
            ]
        ];

        foreach ($tenants as $tenantData) {
            Tenant::create($tenantData);
        }

        // Create Leases
        $units = Unit::where('is_occupied', true)->get();
        $tenants = Tenant::all();
        
        foreach ($units as $index => $unit) {
            $tenant = $tenants[$index % count($tenants)];
            $startDate = Carbon::now()->subMonths(rand(1, 12));
            $endDate = $startDate->copy()->addMonths(rand(12, 24));
            
            Lease::create([
                'unit_id' => $unit->id,
                'tenant_id' => $tenant->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'monthly_rent' => $unit->monthly_rent,
                'security_deposit' => $unit->security_deposit,
                'late_fee' => 50,
                'grace_period_days' => 5,
                'is_active' => true,
                'terms' => 'Standard lease agreement with standard terms and conditions.'
            ]);
        }

        // Create Income records
        $incomeCategories = Category::where('type', 'income')->get();
        $leases = Lease::where('is_active', true)->get();
        
        foreach ($leases as $lease) {
            // Create rent income for the last 6 months
            for ($i = 0; $i < 6; $i++) {
                $date = Carbon::now()->subMonths($i);
                $amount = $lease->monthly_rent;
                
                // Add some variation (late fees, etc.)
                if (rand(1, 10) == 1) {
                    $amount += $lease->late_fee;
                }
                
                Income::create([
                    'property_id' => $lease->unit->property_id,
                    'unit_id' => $lease->unit_id,
                    'tenant_id' => $lease->tenant_id,
                    'category_id' => $incomeCategories->where('name', 'Rent')->first()->id,
                    'amount' => $amount,
                    'date' => $date,
                    'description' => 'Monthly rent payment',
                    'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id,
                    'is_recurring' => true
                ]);
            }
        }

        // Create Expense records
        $expenseCategories = Category::where('type', 'expense')->get();
        $properties = Property::all();
        
        foreach ($properties as $property) {
            // Create expenses for the last 6 months
            for ($i = 0; $i < 6; $i++) {
                $date = Carbon::now()->subMonths($i);
                
                // Create multiple expenses per month
                $numExpenses = rand(3, 8);
                for ($j = 0; $j < $numExpenses; $j++) {
                    $category = $expenseCategories->random();
                    $amount = $this->getExpenseAmount($category->name, $property);
                    
                    Expense::create([
                        'property_id' => $property->id,
                        'category_id' => $category->id,
                        'amount' => $amount,
                        'date' => $date,
                        'description' => $this->getExpenseDescription($category->name),
                        'vendor' => $this->getVendor($category->name),
                        'is_recurring' => in_array($category->name, ['Electricity', 'Water', 'Gas', 'Property Tax', 'Insurance']),
                        'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id
                    ]);
                }
            }
        }

        // Create Invoices
        $leases = Lease::where('is_active', true)->get();
        
        foreach ($leases as $lease) {
            // Create invoices for the last 3 months
            for ($i = 0; $i < 3; $i++) {
                $date = Carbon::now()->subMonths($i);
                
                Invoice::create([
                    'tenant_id' => $lease->tenant_id,
                    'unit_id' => $lease->unit_id,
                    'lease_id' => $lease->id,
                    'invoice_number' => 'INV-' . str_pad($lease->id, 4, '0', STR_PAD_LEFT) . '-' . $date->format('mY'),
                    'amount' => $lease->monthly_rent,
                    'due_date' => $date->copy()->addDays(5),
                    'status' => $i == 0 ? 'pending' : 'paid',
                    'description' => 'Monthly rent for ' . $date->format('F Y'),
                    'late_fee' => $lease->late_fee,
                    'grace_period_days' => $lease->grace_period_days
                ]);
            }
        }

        // Create Payments
        $invoices = Invoice::where('status', 'paid')->get();
        
        foreach ($invoices as $invoice) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'tenant_id' => $invoice->tenant_id,
                'amount' => $invoice->amount,
                'payment_date' => $invoice->due_date->copy()->subDays(rand(0, 5)),
                'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id,
                'reference_number' => 'PAY-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'notes' => 'Payment received on time'
            ]);
        }

        // Create Maintenance Requests
        $units = Unit::where('is_occupied', true)->get();
        
        foreach ($units as $unit) {
            $numRequests = rand(0, 3);
            for ($i = 0; $i < $numRequests; $i++) {
                $date = Carbon::now()->subDays(rand(1, 90));
                
                MaintenanceRequest::create([
                    'unit_id' => $unit->id,
                    'tenant_id' => $unit->leases()->where('is_active', true)->first()->tenant_id,
                    'title' => $this->getMaintenanceTitle(),
                    'description' => $this->getMaintenanceDescription(),
                    'priority' => ['low', 'medium', 'high'][rand(0, 2)],
                    'status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
                    'requested_date' => $date,
                    'completed_date' => $date->copy()->addDays(rand(1, 30)),
                    'estimated_cost' => rand(50, 500),
                    'actual_cost' => rand(50, 500)
                ]);
            }
        }

        // Create Notifications
        $users = \App\Models\User::all();
        $properties = Property::all();
        
        foreach ($users as $user) {
            $numNotifications = rand(2, 8);
            for ($i = 0; $i < $numNotifications; $i++) {
                $date = Carbon::now()->subDays(rand(1, 30));
                
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $this->getNotificationTitle(),
                    'message' => $this->getNotificationMessage(),
                    'type' => ['info', 'warning', 'success', 'error'][rand(0, 3)],
                    'is_read' => rand(0, 1),
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }
    }

    private function getBaseRent($unitType)
    {
        $rents = [
            'Studio' => 1200,
            '1 Bedroom' => 1500,
            '2 Bedroom' => 2000,
            '3 Bedroom' => 2800,
            'Office Suite' => 3000,
            'Retail Unit' => 2500,
            'Warehouse Space' => 1800
        ];
        
        return $rents[$unitType] ?? 1500;
    }

    private function getBedrooms($unitType)
    {
        $bedrooms = [
            'Studio' => 0,
            '1 Bedroom' => 1,
            '2 Bedroom' => 2,
            '3 Bedroom' => 3,
            'Office Suite' => 0,
            'Retail Unit' => 0,
            'Warehouse Space' => 0
        ];
        
        return $bedrooms[$unitType] ?? 0;
    }

    private function getBathrooms($unitType)
    {
        $bathrooms = [
            'Studio' => 1,
            '1 Bedroom' => 1,
            '2 Bedroom' => 2,
            '3 Bedroom' => 2,
            'Office Suite' => 1,
            'Retail Unit' => 1,
            'Warehouse Space' => 1
        ];
        
        return $bathrooms[$unitType] ?? 1;
    }

    private function getExpenseAmount($category, $property)
    {
        $baseAmounts = [
            'Electricity' => 800,
            'Water' => 300,
            'Gas' => 200,
            'Maintenance' => 500,
            'Repairs' => 800,
            'Property Tax' => 2000,
            'Insurance' => 1500,
            'Property Management' => 1200,
            'Cleaning' => 400,
            'Landscaping' => 300,
            'Security' => 600,
            'Other Expenses' => 200
        ];
        
        $baseAmount = $baseAmounts[$category] ?? 300;
        $propertyMultiplier = $property->total_area / 10000; // Scale by property size
        
        return round($baseAmount * $propertyMultiplier * (0.8 + rand(0, 40) / 100));
    }

    private function getExpenseDescription($category)
    {
        $descriptions = [
            'Electricity' => 'Monthly electricity bill for common areas and units',
            'Water' => 'Water and sewer charges for the property',
            'Gas' => 'Natural gas utility charges',
            'Maintenance' => 'Regular maintenance and upkeep services',
            'Repairs' => 'Emergency repairs and fixes',
            'Property Tax' => 'Annual property tax payment',
            'Insurance' => 'Property insurance premium',
            'Property Management' => 'Property management fees',
            'Cleaning' => 'Cleaning services for common areas',
            'Landscaping' => 'Landscaping and grounds maintenance',
            'Security' => 'Security system maintenance and monitoring',
            'Other Expenses' => 'Miscellaneous property expenses'
        ];
        
        return $descriptions[$category] ?? 'Property expense';
    }

    private function getVendor($category)
    {
        $vendors = [
            'Electricity' => 'City Power Company',
            'Water' => 'Municipal Water Department',
            'Gas' => 'Gas Utility Co.',
            'Maintenance' => 'ABC Maintenance Services',
            'Repairs' => 'Quick Fix Repairs LLC',
            'Property Tax' => 'City Tax Department',
            'Insurance' => 'Secure Insurance Group',
            'Property Management' => 'Professional Property Management',
            'Cleaning' => 'Clean Pro Services',
            'Landscaping' => 'Green Thumb Landscaping',
            'Security' => 'SafeGuard Security Systems',
            'Other Expenses' => 'Various Vendors'
        ];
        
        return $vendors[$category] ?? 'Vendor';
    }

    private function getMaintenanceTitle()
    {
        $titles = [
            'Leaky Faucet',
            'Broken Window',
            'HVAC Issue',
            'Electrical Problem',
            'Plumbing Repair',
            'Appliance Malfunction',
            'Door Lock Issue',
            'Lighting Problem',
            'Flooring Damage',
            'Paint Touch-up'
        ];
        
        return $titles[array_rand($titles)];
    }

    private function getMaintenanceDescription()
    {
        $descriptions = [
            'Tenant reported issue that needs immediate attention.',
            'Regular maintenance check revealed this problem.',
            'Emergency repair required for tenant safety.',
            'Routine maintenance and inspection.',
            'Tenant requested this repair service.'
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    private function getNotificationTitle()
    {
        $titles = [
            'New Tenant Application',
            'Maintenance Request',
            'Payment Received',
            'Lease Expiring Soon',
            'Property Inspection Due',
            'Insurance Renewal',
            'Tax Payment Due',
            'New Invoice Generated'
        ];
        
        return $titles[array_rand($titles)];
    }

    private function getNotificationMessage()
    {
        $messages = [
            'A new tenant application has been submitted for review.',
            'A maintenance request has been created and requires attention.',
            'Payment has been received for the latest invoice.',
            'A lease is expiring soon and needs renewal or termination.',
            'Property inspection is scheduled for this month.',
            'Insurance policy needs to be renewed.',
            'Property tax payment is due this month.',
            'New invoice has been generated for a tenant.'
        ];
        
        return $messages[array_rand($messages)];
    }
}
