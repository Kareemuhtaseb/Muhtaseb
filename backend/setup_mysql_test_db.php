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
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'muhtaseb_test';

echo "🔌 Connecting to MySQL...\n";

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to MySQL successfully\n";
} catch (PDOException $e) {
    echo "❌ Error connecting to MySQL: " . $e->getMessage() . "\n";
    exit(1);
}

// Drop database if exists
echo "🗑️ Dropping existing test database...\n";
try {
    $pdo->exec("DROP DATABASE IF EXISTS `$database`");
    echo "✅ Dropped existing database\n";
} catch (PDOException $e) {
    echo "⚠️ Warning: Could not drop database: " . $e->getMessage() . "\n";
}

// Create database
echo "📁 Creating test database...\n";
try {
    $pdo->exec("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Created database: $database\n";
} catch (PDOException $e) {
    echo "❌ Error creating database: " . $e->getMessage() . "\n";
    exit(1);
}

// Set up environment for MySQL
putenv('DB_CONNECTION=mysql');
putenv('DB_HOST=' . $host);
putenv('DB_DATABASE=' . $database);
putenv('DB_USERNAME=' . $username);
putenv('DB_PASSWORD=' . $password);

echo "🔄 Running database migrations...\n";
$output = shell_exec('php artisan migrate --force 2>&1');
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

echo "\n✅ MySQL Test database setup completed successfully!\n\n";

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

echo "📁 Database: $database on $host\n";
echo "🌐 Start the application with: php artisan serve\n\n";

echo "🎉 Happy testing!\n";
