<?php

/**
 * Database Issues Fix Script for Muhtaseb
 * 
 * This script diagnoses and provides solutions for database connectivity issues
 */

echo "🔍 Muhtaseb Database Issues Diagnostic\n";
echo "=====================================\n\n";

// Check PHP version
echo "📋 PHP Information:\n";
echo "   Version: " . PHP_VERSION . "\n";
echo "   OS: " . PHP_OS . "\n";
echo "   Architecture: " . (PHP_INT_SIZE * 8) . "-bit\n\n";

// Check PDO availability
echo "🔌 PDO Status:\n";
if (extension_loaded('pdo')) {
    echo "   ✅ PDO extension is loaded\n";
} else {
    echo "   ❌ PDO extension is NOT loaded\n";
    echo "   💡 Solution: Enable PDO in php.ini\n";
}

// Check available PDO drivers
echo "\n🚗 Available PDO Drivers:\n";
$drivers = PDO::getAvailableDrivers();
if (empty($drivers)) {
    echo "   ❌ No PDO drivers available\n";
    echo "   💡 This is the main issue!\n\n";
    
    echo "🔧 Solutions:\n";
    echo "   1. For XAMPP/WAMP:\n";
    echo "      - Open php.ini\n";
    echo "      - Uncomment these lines:\n";
    echo "        extension=pdo_sqlite\n";
    echo "        extension=pdo_mysql\n";
    echo "        extension=sqlite3\n";
    echo "      - Restart web server\n\n";
    
    echo "   2. For standalone PHP:\n";
    echo "      - Install PDO extensions\n";
    echo "      - Or use package manager\n\n";
    
    echo "   3. Alternative: Use file-based SQLite\n";
    echo "      - Create database file manually\n";
    echo "      - Use simple setup script\n\n";
} else {
    echo "   ✅ Available drivers: " . implode(', ', $drivers) . "\n";
}

// Check for .env file
echo "\n📁 Environment Configuration:\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "   ✅ .env file exists\n";
} else {
    echo "   ❌ .env file missing\n";
    echo "   💡 Creating .env file...\n";
    
    $envContent = "APP_NAME=Muhtaseb
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=muhtaseb
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME=\"\${APP_NAME}\"
VITE_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"
VITE_PUSHER_HOST=\"\${PUSHER_HOST}\"
VITE_PUSHER_PORT=\"\${PUSHER_PORT}\"
VITE_PUSHER_SCHEME=\"\${PUSHER_SCHEME}\"
VITE_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"
";
    
    file_put_contents($envFile, $envContent);
    echo "   ✅ .env file created\n";
}

// Check database directory
echo "\n📂 Database Directory:\n";
$dbDir = __DIR__ . '/database';
if (is_dir($dbDir)) {
    echo "   ✅ Database directory exists\n";
} else {
    echo "   ❌ Database directory missing\n";
    echo "   💡 Creating database directory...\n";
    mkdir($dbDir, 0755, true);
    echo "   ✅ Database directory created\n";
}

// Check if we can create a simple SQLite database
echo "\n🧪 Testing Database Creation:\n";
$testDbPath = $dbDir . '/test.sqlite';

try {
    // Try to create a test SQLite database
    $pdo = new PDO('sqlite:' . $testDbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY)');
    $pdo->exec('DROP TABLE test');
    unlink($testDbPath);
    echo "   ✅ SQLite database creation test passed\n";
} catch (Exception $e) {
    echo "   ❌ SQLite database creation failed: " . $e->getMessage() . "\n";
    echo "   💡 PDO SQLite driver is not available\n";
}

// Check MySQL connection
echo "\n🐬 Testing MySQL Connection:\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    echo "   ✅ MySQL connection successful\n";
} catch (Exception $e) {
    echo "   ❌ MySQL connection failed: " . $e->getMessage() . "\n";
    echo "   💡 MySQL server may not be running or PDO MySQL driver missing\n";
}

echo "\n🎯 Recommended Actions:\n";
echo "   1. Install PDO drivers (pdo_sqlite, pdo_mysql)\n";
echo "   2. Start MySQL server if using MySQL\n";
echo "   3. Run: php artisan key:generate\n";
echo "   4. Run: php artisan migrate\n";
echo "   5. Run: php artisan db:seed\n";
echo "   6. Start server: php artisan serve\n\n";

echo "📚 Additional Resources:\n";
echo "   - Laravel Documentation: https://laravel.com/docs\n";
echo "   - PHP PDO Documentation: https://www.php.net/manual/en/book.pdo.php\n";
echo "   - XAMPP: https://www.apachefriends.org/\n";
echo "   - WAMP: https://www.wampserver.com/\n\n";

echo "✅ Diagnostic complete!\n";
