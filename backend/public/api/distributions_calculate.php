<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Apply optimizations
    $pdo->exec('PRAGMA cache_size = 10000');
    $pdo->exec('PRAGMA temp_store = MEMORY');
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Calculate distributions based on owner share ratios
        $stmt = $pdo->query('SELECT 
            o.id as owner_id,
            o.name,
            o.share_ratio,
            SUM(CASE WHEN u.status = "occupied" THEN u.monthly_rent ELSE 0 END) as total_income
            FROM owners o
            LEFT JOIN units u ON 1=1
            GROUP BY o.id, o.name, o.share_ratio');
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = [
                'owner_id' => $row['owner_id'],
                'name' => $row['name'],
                'amount' => ($row['total_income'] * $row['share_ratio']) / 100
            ];
        }
        echo json_encode($result);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
