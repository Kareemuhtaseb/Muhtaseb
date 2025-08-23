<?php

/**
 * Test Database Setup Script for Muhtaseb
 * 
 * This script creates a SQLite test database and populates it with sample data
 * for testing the property management system.
 */

echo "🚀 Setting up Muhtaseb Test Database...\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from the backend directory\n";
    exit(1);
}

// Create SQLite database file
$dbPath = database_path('database.sqlite');
$dbDir = dirname($dbPath);

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

if (file_exists($dbPath)) {
    echo "📁 Removing existing database file...\n";
    unlink($dbPath);
}

echo "📁 Creating SQLite database file...\n";
touch($dbPath);

// Set up environment for SQLite
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $dbPath);

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

echo "📁 Database file location: " . $dbPath . "\n";
echo "🌐 Start the application with: php artisan serve\n\n";

echo "🎉 Happy testing!\n";
