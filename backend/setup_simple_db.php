<?php

/**
 * Simple Database Setup Script for Muhtaseb
 * 
 * This script creates a SQLite test database and populates it with sample data
 * for testing the property management system.
 */

echo "🚀 Setting up Muhtaseb Test Database...\n\n";

// Create database directory if it doesn't exist
$dbDir = __DIR__ . '/database';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
    echo "📁 Created database directory\n";
}

// Create SQLite database file
$dbPath = $dbDir . '/database.sqlite';

if (file_exists($dbPath)) {
    echo "📁 Removing existing database file...\n";
    unlink($dbPath);
}

echo "📁 Creating SQLite database file...\n";
touch($dbPath);

try {
    // Create PDO connection
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔗 Database connection established\n";
    
    // Create tables
    echo "📋 Creating tables...\n";
    
    // Users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Categories table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            type TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Properties table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS properties (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            address TEXT,
            property_type TEXT,
            total_units INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Units table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS units (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            property_id INTEGER,
            unit_number TEXT NOT NULL,
            monthly_rent_expected DECIMAL(10,2) DEFAULT 0,
            status TEXT DEFAULT 'available',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (property_id) REFERENCES properties(id)
        )
    ");
    
    // Owners table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS owners (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT,
            phone TEXT,
            share_percentage DECIMAL(5,2) DEFAULT 100,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Income table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS income (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            unit_id INTEGER,
            category_id INTEGER,
            amount DECIMAL(10,2) NOT NULL,
            description TEXT,
            date DATE NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (unit_id) REFERENCES units(id),
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )
    ");
    
    // Expenses table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS expenses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            property_id INTEGER,
            category_id INTEGER,
            amount DECIMAL(10,2) NOT NULL,
            description TEXT,
            date DATE NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (property_id) REFERENCES properties(id),
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )
    ");
    
    // Owner distributions table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS owner_distributions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            owner_id INTEGER,
            amount DECIMAL(10,2) NOT NULL,
            date DATE NOT NULL,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (owner_id) REFERENCES owners(id)
        )
    ");
    
    echo "✅ Tables created successfully\n";
    
    // Insert sample data
    echo "🌱 Inserting sample data...\n";
    
    // Insert categories
    $categories = [
        ['Rental Income', 'income'],
        ['Service Fees', 'income'],
        ['Maintenance', 'expense'],
        ['Utilities', 'expense'],
        ['Insurance', 'expense'],
        ['Property Tax', 'expense']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO categories (name, type) VALUES (?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    
    // Insert properties
    $properties = [
        ['Downtown Apartments', '123 Main St, Downtown', 'apartment', 20],
        ['Office Complex', '456 Business Ave', 'office', 15],
        ['Retail Plaza', '789 Shopping Blvd', 'retail', 10]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO properties (name, address, property_type, total_units) VALUES (?, ?, ?, ?)");
    foreach ($properties as $property) {
        $stmt->execute($property);
    }
    
    // Insert units
    $units = [];
    for ($i = 1; $i <= 20; $i++) {
        $units[] = [1, "Apt $i", 1500 + ($i * 50), 'occupied'];
    }
    for ($i = 1; $i <= 15; $i++) {
        $units[] = [2, "Office $i", 2000 + ($i * 100), 'occupied'];
    }
    for ($i = 1; $i <= 10; $i++) {
        $units[] = [3, "Shop $i", 3000 + ($i * 200), 'occupied'];
    }
    
    $stmt = $pdo->prepare("INSERT INTO units (property_id, unit_number, monthly_rent_expected, status) VALUES (?, ?, ?, ?)");
    foreach ($units as $unit) {
        $stmt->execute($unit);
    }
    
    // Insert owners
    $owners = [
        ['John Doe', 'john@example.com', '+1234567890', 60],
        ['Jane Smith', 'jane@example.com', '+1234567891', 40]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO owners (name, email, phone, share_percentage) VALUES (?, ?, ?, ?)");
    foreach ($owners as $owner) {
        $stmt->execute($owner);
    }
    
    // Insert sample income data (last 6 months)
    $incomeData = [];
    $currentDate = new DateTime();
    for ($month = 5; $month >= 0; $month--) {
        $date = clone $currentDate;
        $date->modify("-$month months");
        $monthStr = $date->format('Y-m');
        
        // Rental income for each unit
        for ($unitId = 1; $unitId <= 45; $unitId++) {
            $rentAmount = 1500 + ($unitId * 50);
            $incomeData[] = [$unitId, 1, $rentAmount, "Rent for " . $monthStr, $date->format('Y-m-d')];
        }
        
        // Service fees
        $incomeData[] = [1, 2, 500, "Service fees for " . $monthStr, $date->format('Y-m-d')];
        $incomeData[] = [2, 2, 300, "Service fees for " . $monthStr, $date->format('Y-m-d')];
        $incomeData[] = [3, 2, 400, "Service fees for " . $monthStr, $date->format('Y-m-d')];
    }
    
    $stmt = $pdo->prepare("INSERT INTO income (unit_id, category_id, amount, description, date) VALUES (?, ?, ?, ?, ?)");
    foreach ($incomeData as $income) {
        $stmt->execute($income);
    }
    
    // Insert sample expense data (last 6 months)
    $expenseData = [];
    for ($month = 5; $month >= 0; $month--) {
        $date = clone $currentDate;
        $date->modify("-$month months");
        $monthStr = $date->format('Y-m');
        
        // Maintenance expenses
        $expenseData[] = [1, 3, 2000, "Maintenance for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [2, 3, 1500, "Maintenance for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [3, 3, 1800, "Maintenance for " . $monthStr, $date->format('Y-m-d')];
        
        // Utilities
        $expenseData[] = [1, 4, 800, "Utilities for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [2, 4, 600, "Utilities for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [3, 4, 700, "Utilities for " . $monthStr, $date->format('Y-m-d')];
        
        // Insurance
        $expenseData[] = [1, 5, 500, "Insurance for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [2, 5, 400, "Insurance for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [3, 5, 450, "Insurance for " . $monthStr, $date->format('Y-m-d')];
        
        // Property Tax
        $expenseData[] = [1, 6, 1200, "Property tax for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [2, 6, 1000, "Property tax for " . $monthStr, $date->format('Y-m-d')];
        $expenseData[] = [3, 6, 1100, "Property tax for " . $monthStr, $date->format('Y-m-d')];
    }
    
    $stmt = $pdo->prepare("INSERT INTO expenses (property_id, category_id, amount, description, date) VALUES (?, ?, ?, ?, ?)");
    foreach ($expenseData as $expense) {
        $stmt->execute($expense);
    }
    
    echo "✅ Sample data inserted successfully\n";
    
    echo "\n✅ Test database setup completed successfully!\n\n";
    
    echo "📊 Test Data Summary:\n";
    echo "   • 3 Properties (apartments, office, retail)\n";
    echo "   • 45 Units across all properties\n";
    echo "   • 2 Owners with different share percentages\n";
    echo "   • 6 months of income and expense data\n";
    echo "   • 6 Categories (income and expense types)\n\n";
    
    echo "📁 Database file location: " . $dbPath . "\n";
    echo "🌐 Start the application with: php -S localhost:8000 -t public\n\n";
    
    echo "🎉 Happy testing!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
