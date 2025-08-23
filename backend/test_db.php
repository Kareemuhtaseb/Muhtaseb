<?php

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT name FROM sqlite_master WHERE type="table" AND name="monthly_reports"');
    $result = $stmt->fetch();
    
    if ($result) {
        echo "Monthly reports table exists!\n";
        
        // Check table structure
        $stmt = $pdo->query('PRAGMA table_info(monthly_reports)');
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Table structure:\n";
        foreach ($columns as $column) {
            echo "- {$column['name']} ({$column['type']})\n";
        }
    } else {
        echo "Monthly reports table not found. Creating it...\n";
        
        // Create the table
        $sql = "
        CREATE TABLE monthly_reports (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            owner_id INTEGER NOT NULL,
            month INTEGER NOT NULL,
            year INTEGER NOT NULL,
            total_amount DECIMAL(15,2) DEFAULT 0,
            status TEXT DEFAULT 'pending',
            report_data TEXT,
            sent_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(owner_id, month, year)
        )";
        
        $pdo->exec($sql);
        echo "Table created successfully!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
