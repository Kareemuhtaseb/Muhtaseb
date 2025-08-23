<?php

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create sample owners
    $owners = [
        ['name' => 'Ahmed Al-Rashid', 'share_percentage' => 35.5, 'email' => 'ahmed@example.com', 'phone' => '+966501234567'],
        ['name' => 'Fatima Al-Zahra', 'share_percentage' => 28.0, 'email' => 'fatima@example.com', 'phone' => '+966507654321'],
        ['name' => 'Omar Al-Mansour', 'share_percentage' => 22.5, 'email' => 'omar@example.com', 'phone' => '+966509876543'],
        ['name' => 'Aisha Al-Qahtani', 'share_percentage' => 14.0, 'email' => 'aisha@example.com', 'phone' => '+966505555555']
    ];
    
    // Insert owners
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO owners (name, share_percentage, email, phone) VALUES (?, ?, ?, ?)');
    foreach ($owners as $owner) {
        $stmt->execute([$owner['name'], $owner['share_percentage'], $owner['email'], $owner['phone']]);
    }
    
    // Get owner IDs for distributions
    $stmt = $pdo->query('SELECT id FROM owners ORDER BY id');
    $ownerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Create sample distributions
    $distributions = [
        ['owner_id' => $ownerIds[0], 'date' => '2024-12-01', 'amount' => 15000.00, 'notes' => 'Q4 2024 distribution'],
        ['owner_id' => $ownerIds[1], 'date' => '2024-12-01', 'amount' => 12000.00, 'notes' => 'Q4 2024 distribution'],
        ['owner_id' => $ownerIds[2], 'date' => '2024-12-01', 'amount' => 9500.00, 'notes' => 'Q4 2024 distribution'],
        ['owner_id' => $ownerIds[3], 'date' => '2024-12-01', 'amount' => 6000.00, 'notes' => 'Q4 2024 distribution'],
        ['owner_id' => $ownerIds[0], 'date' => '2024-11-01', 'amount' => 14000.00, 'notes' => 'Q3 2024 distribution'],
        ['owner_id' => $ownerIds[1], 'date' => '2024-11-01', 'amount' => 11000.00, 'notes' => 'Q3 2024 distribution']
    ];
    
    // Insert distributions
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO distributions (owner_id, date, amount, notes) VALUES (?, ?, ?, ?)');
    foreach ($distributions as $dist) {
        $stmt->execute([$dist['owner_id'], $dist['date'], $dist['amount'], $dist['notes']]);
    }
    
    // Create sample income
    $income = [
        ['date' => '2024-12-01', 'amount' => 50000.00, 'description' => 'Rental Income - December 2024', 'category' => 'Rentals'],
        ['date' => '2024-11-01', 'amount' => 48000.00, 'description' => 'Rental Income - November 2024', 'category' => 'Rentals'],
        ['date' => '2024-10-01', 'amount' => 52000.00, 'description' => 'Rental Income - October 2024', 'category' => 'Rentals']
    ];
    
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO income (date, amount, description, category) VALUES (?, ?, ?, ?)');
    foreach ($income as $inc) {
        $stmt->execute([$inc['date'], $inc['amount'], $inc['description'], $inc['category']]);
    }
    
    // Create sample expenses
    $expenses = [
        ['date' => '2024-12-01', 'amount' => 15000.00, 'description' => 'Maintenance - December 2024', 'category' => 'Maintenance'],
        ['date' => '2024-11-01', 'amount' => 12000.00, 'description' => 'Utilities - November 2024', 'category' => 'Utilities'],
        ['date' => '2024-10-01', 'amount' => 18000.00, 'description' => 'Insurance - October 2024', 'category' => 'Insurance']
    ];
    
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO expenses (date, amount, description, category) VALUES (?, ?, ?, ?)');
    foreach ($expenses as $exp) {
        $stmt->execute([$exp['date'], $exp['amount'], $exp['description'], $exp['category']]);
    }
    
    echo "Sample data created successfully!\n";
    echo "Added " . count($owners) . " owners\n";
    echo "Added " . count($distributions) . " distributions\n";
    echo "Added " . count($income) . " income records\n";
    echo "Added " . count($expenses) . " expense records\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
