<?php

/**
 * Simple File-Based Database Setup for Muhtaseb
 * 
 * This script creates a simple JSON-based database for testing
 * when PDO drivers are not available
 */

echo "🚀 Setting up Muhtaseb Simple File Database...\n\n";

// Create data directory
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
    echo "📁 Created data directory\n";
}

// Create simple JSON database files
$tables = [
    'users' => [
        ['id' => 1, 'name' => 'Admin User', 'email' => 'admin@muhtaseb.com', 'role' => 'admin'],
        ['id' => 2, 'name' => 'Manager User', 'email' => 'manager@muhtaseb.com', 'role' => 'manager'],
        ['id' => 3, 'name' => 'Viewer User', 'email' => 'viewer@muhtaseb.com', 'role' => 'viewer']
    ],
    'properties' => [
        ['id' => 1, 'name' => 'Sunset Apartments', 'address' => '123 Main St', 'property_type' => 'residential', 'total_units' => 20],
        ['id' => 2, 'name' => 'Downtown Office', 'address' => '456 Business Ave', 'property_type' => 'commercial', 'total_units' => 15],
        ['id' => 3, 'name' => 'Mall Plaza', 'address' => '789 Shopping Blvd', 'property_type' => 'retail', 'total_units' => 25]
    ],
    'units' => [
        ['id' => 1, 'property_id' => 1, 'unit_number' => 'A101', 'monthly_rent_expected' => 1200, 'status' => 'occupied'],
        ['id' => 2, 'property_id' => 1, 'unit_number' => 'A102', 'monthly_rent_expected' => 1200, 'status' => 'available'],
        ['id' => 3, 'property_id' => 2, 'unit_number' => 'B201', 'monthly_rent_expected' => 2500, 'status' => 'occupied'],
        ['id' => 4, 'property_id' => 3, 'unit_number' => 'C301', 'monthly_rent_expected' => 3000, 'status' => 'occupied']
    ],
    'owners' => [
        ['id' => 1, 'name' => 'John Smith', 'email' => 'john@example.com', 'phone' => '+1234567890', 'share_percentage' => 60],
        ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com', 'phone' => '+0987654321', 'share_percentage' => 40]
    ],
    'income' => [
        ['id' => 1, 'property_id' => 1, 'unit_id' => 1, 'amount' => 1200, 'date' => '2024-01-15', 'description' => 'Rent payment'],
        ['id' => 2, 'property_id' => 2, 'unit_id' => 3, 'amount' => 2500, 'date' => '2024-01-15', 'description' => 'Rent payment'],
        ['id' => 3, 'property_id' => 3, 'unit_id' => 4, 'amount' => 3000, 'date' => '2024-01-15', 'description' => 'Rent payment']
    ],
    'expenses' => [
        ['id' => 1, 'property_id' => 1, 'amount' => 500, 'date' => '2024-01-10', 'description' => 'Maintenance'],
        ['id' => 2, 'property_id' => 2, 'amount' => 800, 'date' => '2024-01-12', 'description' => 'Utilities'],
        ['id' => 3, 'property_id' => 3, 'amount' => 1200, 'date' => '2024-01-14', 'description' => 'Insurance']
    ],
    'contracts' => [
        ['id' => 1, 'property_id' => 1, 'unit_id' => 1, 'tenant_name' => 'Alice Johnson', 'start_date' => '2024-01-01', 'end_date' => '2024-12-31', 'monthly_rent' => 1200],
        ['id' => 2, 'property_id' => 2, 'unit_id' => 3, 'tenant_name' => 'Bob Wilson', 'start_date' => '2024-01-01', 'end_date' => '2024-12-31', 'monthly_rent' => 2500],
        ['id' => 3, 'property_id' => 3, 'unit_id' => 4, 'tenant_name' => 'Carol Brown', 'start_date' => '2024-01-01', 'end_date' => '2024-12-31', 'monthly_rent' => 3000]
    ]
];

echo "📊 Creating database tables...\n";

foreach ($tables as $tableName => $data) {
    $filePath = $dataDir . '/' . $tableName . '.json';
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    echo "   ✅ Created $tableName.json with " . count($data) . " records\n";
}

// Create a simple database helper class
$helperClass = '<?php

class SimpleDB {
    private $dataDir;
    
    public function __construct() {
        $this->dataDir = __DIR__ . "/data";
    }
    
    public function getAll($table) {
        $file = $this->dataDir . "/$table.json";
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }
    
    public function getById($table, $id) {
        $data = $this->getAll($table);
        foreach ($data as $record) {
            if ($record["id"] == $id) {
                return $record;
            }
        }
        return null;
    }
    
    public function create($table, $data) {
        $records = $this->getAll($table);
        $data["id"] = count($records) + 1;
        $records[] = $data;
        file_put_contents($this->dataDir . "/$table.json", json_encode($records, JSON_PRETTY_PRINT));
        return $data;
    }
    
    public function update($table, $id, $data) {
        $records = $this->getAll($table);
        foreach ($records as &$record) {
            if ($record["id"] == $id) {
                $record = array_merge($record, $data);
                break;
            }
        }
        file_put_contents($this->dataDir . "/$table.json", json_encode($records, JSON_PRETTY_PRINT));
        return true;
    }
    
    public function delete($table, $id) {
        $records = $this->getAll($table);
        $records = array_filter($records, function($record) use ($id) {
            return $record["id"] != $id;
        });
        file_put_contents($this->dataDir . "/$table.json", json_encode(array_values($records), JSON_PRETTY_PRINT));
        return true;
    }
    
    public function getStats() {
        $stats = [];
        $tables = ["users", "properties", "units", "owners", "income", "expenses", "contracts"];
        
        foreach ($tables as $table) {
            $stats[$table] = count($this->getAll($table));
        }
        
        return $stats;
    }
}
';

file_put_contents($dataDir . '/SimpleDB.php', $helperClass);
echo "   ✅ Created SimpleDB.php helper class\n";

echo "\n✅ Simple file database setup completed!\n\n";

echo "📊 Database Summary:\n";
foreach ($tables as $tableName => $data) {
    echo "   • $tableName: " . count($data) . " records\n";
}

echo "\n🔑 Test Credentials:\n";
echo "   Admin: admin@muhtaseb.com\n";
echo "   Manager: manager@muhtaseb.com\n";
echo "   Viewer: viewer@muhtaseb.com\n\n";

echo "📁 Database files created in: $dataDir\n";
echo "🌐 You can now start the application!\n\n";

echo "🎉 Setup complete!\n";
