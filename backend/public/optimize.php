<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Apply optimizations
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA cache_size = 10000');
    $pdo->exec('PRAGMA temp_store = MEMORY');
    $pdo->exec('PRAGMA mmap_size = 268435456');
    $pdo->exec('PRAGMA synchronous = NORMAL');
    $pdo->exec('PRAGMA foreign_keys = ON');
    
    // Create indexes
    $indexes = [
        'CREATE INDEX IF NOT EXISTS idx_units_unit_number ON units(unit_number)',
        'CREATE INDEX IF NOT EXISTS idx_units_status ON units(status)',
        'CREATE INDEX IF NOT EXISTS idx_tenants_email ON tenants(email)',
        'CREATE INDEX IF NOT EXISTS idx_tenants_unit_id ON tenants(unit_id)',
        'CREATE INDEX IF NOT EXISTS idx_payments_tenant_id ON payments(tenant_id)',
        'CREATE INDEX IF NOT EXISTS idx_payments_payment_date ON payments(payment_date)',
        'CREATE INDEX IF NOT EXISTS idx_expenses_date ON expenses(date)'
    ];
    
    foreach ($indexes as $index) {
        $pdo->exec($index);
    }
    
    echo json_encode(['status' => 'success', 'message' => 'Database optimized successfully']);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
