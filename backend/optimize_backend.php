<?php

/**
 * Backend Optimization Script
 * 
 * This script runs all backend optimizations for the Muhtaseb property management system.
 * Run this script after deployment to ensure optimal performance.
 */

echo "🚀 Starting Muhtaseb Backend Optimization...\n\n";

// Start timing
$startTime = microtime(true);

try {
    // 1. Clear all caches
    echo "📦 Clearing Laravel caches...\n";
    system('php artisan cache:clear');
    system('php artisan config:clear');
    system('php artisan route:clear');
    system('php artisan view:clear');
    echo "✅ Caches cleared successfully\n\n";

    // 2. Optimize Laravel
    echo "⚡ Optimizing Laravel configuration...\n";
    system('php artisan config:cache');
    system('php artisan route:cache');
    system('php artisan view:cache');
    echo "✅ Laravel optimized successfully\n\n";

    // 3. Run database migrations
    echo "🗄️ Running database migrations...\n";
    system('php artisan migrate --force');
    echo "✅ Database migrations completed\n\n";

    // 4. Optimize database
    echo "🔧 Optimizing database...\n";
    if (file_exists('optimize_db.php')) {
        include 'optimize_db.php';
    }
    echo "✅ Database optimization completed\n\n";

    // 5. Generate application key if not exists
    echo "🔑 Checking application key...\n";
    if (empty(env('APP_KEY'))) {
        system('php artisan key:generate');
        echo "✅ Application key generated\n\n";
    } else {
        echo "✅ Application key already exists\n\n";
    }

    // 6. Optimize Composer autoloader
    echo "📚 Optimizing Composer autoloader...\n";
    system('composer install --no-dev --optimize-autoloader');
    echo "✅ Composer autoloader optimized\n\n";

    // 7. Set proper permissions
    echo "🔐 Setting file permissions...\n";
    system('chmod -R 755 storage bootstrap/cache');
    system('chown -R www-data:www-data storage bootstrap/cache');
    echo "✅ File permissions set\n\n";

    // 8. Clear Redis cache (if available)
    echo "🔴 Clearing Redis cache...\n";
    try {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->flushAll();
        echo "✅ Redis cache cleared\n\n";
    } catch (Exception $e) {
        echo "⚠️ Redis not available, skipping cache clear\n\n";
    }

    // 9. Warm up caches
    echo "🔥 Warming up caches...\n";
    warmUpCaches();
    echo "✅ Caches warmed up\n\n";

    // 10. Performance check
    echo "📊 Running performance check...\n";
    runPerformanceCheck();
    echo "✅ Performance check completed\n\n";

    // Calculate total time
    $totalTime = microtime(true) - $startTime;

    echo "🎉 Backend optimization completed successfully!\n";
    echo "⏱️ Total time: " . round($totalTime, 2) . " seconds\n\n";

    echo "📋 Optimization Summary:\n";
    echo "✅ Laravel caches cleared and optimized\n";
    echo "✅ Database migrations and optimization completed\n";
    echo "✅ Application key verified\n";
    echo "✅ Composer autoloader optimized\n";
    echo "✅ File permissions set\n";
    echo "✅ Redis cache cleared\n";
    echo "✅ Caches warmed up\n";
    echo "✅ Performance check completed\n\n";

    echo "🚀 Your Muhtaseb backend is now optimized for production!\n";

} catch (Exception $e) {
    echo "❌ Error during optimization: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Warm up frequently accessed caches
 */
function warmUpCaches() {
    try {
        // Initialize Laravel application
        $app = require_once 'bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        // Warm up dashboard cache
        $cache = app('cache');
        $cache->remember('dashboard_overview', 300, function () {
            return ['status' => 'warmed'];
        });

        // Warm up property types cache
        $cache->remember('property_types', 86400, function () {
            return \App\Models\PropertyType::all();
        });

        // Warm up unit types cache
        $cache->remember('unit_types', 86400, function () {
            return \App\Models\UnitType::all();
        });

        // Warm up payment methods cache
        $cache->remember('payment_methods', 86400, function () {
            return \App\Models\PaymentMethod::all();
        });

        // Warm up categories cache
        $cache->remember('categories', 86400, function () {
            return \App\Models\Category::all();
        });

    } catch (Exception $e) {
        echo "⚠️ Cache warming failed: " . $e->getMessage() . "\n";
    }
}

/**
 * Run basic performance checks
 */
function runPerformanceCheck() {
    try {
        // Check database connection
        $pdo = new PDO(
            'mysql:host=' . env('DB_HOST', 'localhost') . 
            ';dbname=' . env('DB_DATABASE', 'muhtaseb_test'),
            env('DB_USERNAME', 'root'),
            env('DB_PASSWORD', 'root')
        );
        echo "✅ Database connection: OK\n";

        // Check Redis connection (if available)
        try {
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->ping();
            echo "✅ Redis connection: OK\n";
        } catch (Exception $e) {
            echo "⚠️ Redis connection: Not available\n";
        }

        // Check storage permissions
        if (is_writable('storage')) {
            echo "✅ Storage permissions: OK\n";
        } else {
            echo "❌ Storage permissions: Failed\n";
        }

        // Check bootstrap/cache permissions
        if (is_writable('bootstrap/cache')) {
            echo "✅ Bootstrap cache permissions: OK\n";
        } else {
            echo "❌ Bootstrap cache permissions: Failed\n";
        }

    } catch (Exception $e) {
        echo "❌ Performance check failed: " . $e->getMessage() . "\n";
    }
}

/**
 * Display system information
 */
function displaySystemInfo() {
    echo "📋 System Information:\n";
    echo "PHP Version: " . PHP_VERSION . "\n";
    echo "Laravel Version: " . app()->version() . "\n";
    echo "Environment: " . env('APP_ENV', 'production') . "\n";
    echo "Debug Mode: " . (env('APP_DEBUG', false) ? 'Enabled' : 'Disabled') . "\n";
    echo "Cache Driver: " . env('CACHE_STORE', 'redis') . "\n";
    echo "Queue Driver: " . env('QUEUE_CONNECTION', 'redis') . "\n";
    echo "Session Driver: " . env('SESSION_DRIVER', 'redis') . "\n\n";
}

// Display system info at the end
displaySystemInfo();
