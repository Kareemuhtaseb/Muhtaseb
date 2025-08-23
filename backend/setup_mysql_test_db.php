<?php

/**
 * MySQL Test Database Setup Script for Muhtaseb
 * 
 * This script creates a MySQL test database and populates it with sample data
 * for testing the property management system.
 */

echo "🚀 Setting up Muhtaseb MySQL Test Database...\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from the backend directory\n";
    exit(1);
}

// Database configuration
$dbConfig = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'muhtaseb_test',
    'username' => 'root',
    'password' => ''
];

echo "📊 Database Configuration:\n";
echo "   Host: {$dbConfig['host']}\n";
echo "   Port: {$dbConfig['port']}\n";
echo "   Database: {$dbConfig['database']}\n";
echo "   Username: {$dbConfig['username']}\n\n";

// Test MySQL connection
try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']}",
        $dbConfig['username'],
        $dbConfig['password']
    );
    echo "✅ MySQL connection successful\n";
} catch (PDOException $e) {
    echo "❌ Error connecting to MySQL: " . $e->getMessage() . "\n";
    echo "   Please ensure MySQL is running and credentials are correct\n";
    exit(1);
}

// Create database if it doesn't exist
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '{$dbConfig['database']}' created/verified\n";
} catch (PDOException $e) {
    echo "❌ Error creating database: " . $e->getMessage() . "\n";
    exit(1);
}

// Set up environment variables for Laravel
putenv('DB_CONNECTION=mysql');
putenv('DB_HOST=' . $dbConfig['host']);
putenv('DB_PORT=' . $dbConfig['port']);
putenv('DB_DATABASE=' . $dbConfig['database']);
putenv('DB_USERNAME=' . $dbConfig['username']);
putenv('DB_PASSWORD=' . $dbConfig['password']);

// Generate application key if not exists
if (!file_exists('.env')) {
    echo "📝 Creating .env file...\n";
    $envContent = "APP_NAME=Muhtaseb\n";
    $envContent .= "APP_ENV=local\n";
    $envContent .= "APP_KEY=base64:" . base64_encode(random_bytes(32)) . "\n";
    $envContent .= "APP_DEBUG=true\n";
    $envContent .= "APP_URL=http://localhost\n\n";
    $envContent .= "DB_CONNECTION=mysql\n";
    $envContent .= "DB_HOST={$dbConfig['host']}\n";
    $envContent .= "DB_PORT={$dbConfig['port']}\n";
    $envContent .= "DB_DATABASE={$dbConfig['database']}\n";
    $envContent .= "DB_USERNAME={$dbConfig['username']}\n";
    $envContent .= "DB_PASSWORD={$dbConfig['password']}\n";
    
    file_put_contents('.env', $envContent);
    echo "✅ .env file created\n";
}

echo "🔄 Running database migrations...\n";
$output = shell_exec('php artisan migrate:fresh --force 2>&1');
echo $output;

if (strpos($output, 'Migration table created successfully') === false && strpos($output, 'Nothing to migrate') === false) {
    echo "❌ Error: Database migration failed\n";
    exit(1);
}

echo "🌱 Seeding database with test data...\n";
$output = shell_exec('php artisan db:seed --force 2>&1');
echo $output;

if (strpos($output, 'Database seeded successfully') === false) {
    echo "❌ Error: Database seeding failed\n";
    exit(1);
}

echo "\n✅ Test database setup completed successfully!\n\n";

echo "📊 Test Data Summary:\n";
echo "   • 3 Users (admin, manager, viewer)\n";
echo "   • 3 Properties (apartments, office, retail)\n";
echo "   • 44 Units across all properties\n";
echo "   • 4 Tenants with active leases\n";
echo "   • 6 months of income and expense data\n";
echo "   • Sample invoices, payments, and maintenance requests\n";
echo "   • Realistic notifications and audit logs\n\n";

echo "🔑 Login Credentials:\n";
echo "   Admin: admin@muhtaseb.com / password\n";
echo "   Manager: manager@muhtaseb.com / password\n";
echo "   Viewer: viewer@muhtaseb.com / password\n\n";

echo "🗄️ Database Information:\n";
echo "   Host: {$dbConfig['host']}:{$dbConfig['port']}\n";
echo "   Database: {$dbConfig['database']}\n";
echo "   Username: {$dbConfig['username']}\n\n";

echo "🌐 Start the application with: php artisan serve\n\n";

echo "🎉 Happy testing!\n";
