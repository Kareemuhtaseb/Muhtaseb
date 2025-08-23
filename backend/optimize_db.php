<?php

echo "Applying SQLite database optimizations...\n";

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Enable WAL mode for better concurrency
    $pdo->exec('PRAGMA journal_mode = WAL');
    echo "✓ WAL mode enabled\n";
    
    // Optimize synchronous mode for better performance
    $pdo->exec('PRAGMA synchronous = NORMAL');
    echo "✓ Synchronous mode optimized\n";
    
    // Increase cache size for better performance
    $pdo->exec('PRAGMA cache_size = 10000');
    echo "✓ Cache size increased to 10MB\n";
    
    // Use memory for temporary storage
    $pdo->exec('PRAGMA temp_store = MEMORY');
    echo "✓ Temporary storage set to memory\n";
    
    // Enable memory-mapped I/O
    $pdo->exec('PRAGMA mmap_size = 268435456');
    echo "✓ Memory-mapped I/O enabled (256MB)\n";
    
    // Optimize page size
    $pdo->exec('PRAGMA page_size = 4096');
    echo "✓ Page size optimized\n";
    
    // Enable foreign key constraints
    $pdo->exec('PRAGMA foreign_keys = ON');
    echo "✓ Foreign key constraints enabled\n";
    
    // Analyze tables for better query planning
    $pdo->exec('ANALYZE');
    echo "✓ Tables analyzed for query optimization\n";
    
    // Create indexes for better query performance
    $indexes = [
        'CREATE INDEX IF NOT EXISTS idx_units_unit_number ON units(unit_number)',
        'CREATE INDEX IF NOT EXISTS idx_units_status ON units(status)',
        'CREATE INDEX IF NOT EXISTS idx_tenants_email ON tenants(email)',
        'CREATE INDEX IF NOT EXISTS idx_tenants_unit_id ON tenants(unit_id)',
        'CREATE INDEX IF NOT EXISTS idx_payments_tenant_id ON payments(tenant_id)',
        'CREATE INDEX IF NOT EXISTS idx_payments_payment_date ON payments(payment_date)',
        'CREATE INDEX IF NOT EXISTS idx_expenses_date ON expenses(date)',
        'CREATE INDEX IF NOT EXISTS idx_units_status_rent ON units(status, monthly_rent)',
        'CREATE INDEX IF NOT EXISTS idx_tenants_unit_start ON tenants(unit_id, start_date)'
    ];
    
    foreach ($indexes as $index) {
        $pdo->exec($index);
    }
    echo "✓ Database indexes created\n";
    
    // Vacuum database to optimize storage
    $pdo->exec('VACUUM');
    echo "✓ Database vacuumed for optimal storage\n";
    
    echo "\n🎉 Database optimization completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
