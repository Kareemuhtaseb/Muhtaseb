<?php

/**
 * Muhtaseb Integration Test Script
 * 
 * This script tests the complete integration between database, backend API, and frontend
 */

echo "🧪 Muhtaseb Integration Test Suite\n";
echo "==================================\n\n";

// Test 1: Database Connection
echo "1️⃣ Testing Database Connection...\n";
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ SQLite connection successful\n";
} catch (Exception $e) {
    echo "   ❌ SQLite connection failed: " . $e->getMessage() . "\n";
    echo "   💡 Try: php setup_simple_db.php\n\n";
}

// Test 2: Laravel Environment
echo "\n2️⃣ Testing Laravel Environment...\n";
if (file_exists(__DIR__ . '/.env')) {
    echo "   ✅ .env file exists\n";
} else {
    echo "   ❌ .env file missing\n";
    echo "   💡 Run: php fix_database_issues.php\n";
}

// Test 3: Application Key
echo "\n3️⃣ Testing Application Key...\n";
$envContent = file_get_contents(__DIR__ . '/.env');
if (strpos($envContent, 'APP_KEY=base64:') !== false) {
    echo "   ✅ Application key is set\n";
} else {
    echo "   ❌ Application key not set\n";
    echo "   💡 Run: php artisan key:generate\n";
}

// Test 4: Database Tables
echo "\n4️⃣ Testing Database Tables...\n";
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    
    $expectedTables = [
        'users', 'properties', 'units', 'contracts', 'income', 
        'expenses', 'owners', 'payments', 'invoices', 'maintenance_requests'
    ];
    
    $missingTables = array_diff($expectedTables, $tables);
    
    if (empty($missingTables)) {
        echo "   ✅ All expected tables exist\n";
    } else {
        echo "   ❌ Missing tables: " . implode(', ', $missingTables) . "\n";
        echo "   💡 Run: php artisan migrate\n";
    }
} catch (Exception $e) {
    echo "   ❌ Cannot check tables: " . $e->getMessage() . "\n";
}

// Test 5: Laravel Artisan Commands
echo "\n5️⃣ Testing Laravel Commands...\n";
$output = shell_exec('php artisan --version 2>&1');
if (strpos($output, 'Laravel Framework') !== false) {
    echo "   ✅ Laravel is working\n";
    echo "   📋 " . trim($output) . "\n";
} else {
    echo "   ❌ Laravel not working: " . $output . "\n";
}

// Test 6: API Routes
echo "\n6️⃣ Testing API Routes...\n";
$routes = [
    '/api/dashboard' => 'Dashboard API',
    '/api/properties' => 'Properties API',
    '/api/units' => 'Units API',
    '/api/contracts' => 'Contracts API'
];

foreach ($routes as $route => $name) {
    echo "   Testing $name... ";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000' . $route);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "✅\n";
    } elseif ($httpCode == 0) {
        echo "❌ (Server not running)\n";
    } else {
        echo "⚠️ (HTTP $httpCode)\n";
    }
}

// Test 7: Frontend Files
echo "\n7️⃣ Testing Frontend Files...\n";
$frontendFiles = [
    '../frontend/package.json' => 'Package.json',
    '../frontend/src/main.js' => 'Main.js',
    '../frontend/src/App.vue' => 'App.vue',
    '../frontend/src/router/index.js' => 'Router'
];

foreach ($frontendFiles as $file => $name) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "   ✅ $name exists\n";
    } else {
        echo "   ❌ $name missing\n";
    }
}

// Test 8: Frontend Dependencies
echo "\n8️⃣ Testing Frontend Dependencies...\n";
$packageJson = json_decode(file_get_contents(__DIR__ . '/../frontend/package.json'), true);
$requiredDeps = ['vue', 'axios', 'vue-router', 'chart.js'];

foreach ($requiredDeps as $dep) {
    if (isset($packageJson['dependencies'][$dep])) {
        echo "   ✅ $dep: " . $packageJson['dependencies'][$dep] . "\n";
    } else {
        echo "   ❌ $dep missing\n";
    }
}

// Test 9: CORS Configuration
echo "\n9️⃣ Testing CORS Configuration...\n";
$corsConfig = file_get_contents(__DIR__ . '/config/cors.php');
if (strpos($corsConfig, 'localhost:5173') !== false) {
    echo "   ✅ CORS configured for frontend\n";
} else {
    echo "   ❌ CORS not configured for frontend\n";
}

// Test 10: Database Seeding
echo "\n🔟 Testing Database Seeding...\n";
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $propertyCount = $pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
    
    if ($userCount > 0) {
        echo "   ✅ Users seeded: $userCount\n";
    } else {
        echo "   ❌ No users found\n";
        echo "   💡 Run: php artisan db:seed\n";
    }
    
    if ($propertyCount > 0) {
        echo "   ✅ Properties seeded: $propertyCount\n";
    } else {
        echo "   ❌ No properties found\n";
        echo "   💡 Run: php artisan db:seed\n";
    }
} catch (Exception $e) {
    echo "   ❌ Cannot check seeding: " . $e->getMessage() . "\n";
}

// Summary
echo "\n📊 Integration Test Summary\n";
echo "==========================\n";

$issues = [];
if (!extension_loaded('pdo_sqlite')) $issues[] = "PDO SQLite driver missing";
if (!file_exists(__DIR__ . '/.env')) $issues[] = ".env file missing";
if (strpos($envContent, 'APP_KEY=base64:') === false) $issues[] = "Application key not set";

if (empty($issues)) {
    echo "✅ All critical components are ready!\n";
    echo "\n🚀 Next Steps:\n";
    echo "   1. Start backend: php artisan serve\n";
    echo "   2. Start frontend: cd ../frontend && npm run dev\n";
    echo "   3. Open browser: http://localhost:5173\n";
} else {
    echo "❌ Issues found:\n";
    foreach ($issues as $issue) {
        echo "   • $issue\n";
    }
    echo "\n🔧 Fix these issues first, then run the test again.\n";
}

echo "\n✅ Integration test complete!\n";
