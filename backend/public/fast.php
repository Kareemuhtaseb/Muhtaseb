<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$start = microtime(true);

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT * FROM units');
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $end = microtime(true);
    $queryTime = round(($end - $start) * 1000, 2);
    
    echo json_encode([
        'query_time_ms' => $queryTime,
        'count' => count($units),
        'data' => $units
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
