<?php

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create monthly_reports table
    $sql = "
    CREATE TABLE IF NOT EXISTS monthly_reports (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        owner_id INTEGER NOT NULL,
        month INTEGER NOT NULL,
        year INTEGER NOT NULL,
        total_amount DECIMAL(15,2) DEFAULT 0,
        status TEXT DEFAULT 'pending' CHECK(status IN ('generated', 'sent', 'pending')),
        report_data TEXT,
        sent_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(owner_id, month, year),
        FOREIGN KEY (owner_id) REFERENCES owners(id) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql);
    
    // Create indexes for performance
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_monthly_reports_owner_status ON monthly_reports(owner_id, status)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_monthly_reports_month_year ON monthly_reports(month, year)');
    
    echo "Monthly reports table created successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
