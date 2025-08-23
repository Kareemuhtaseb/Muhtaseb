<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$start = microtime(true);

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Apply optimizations on each request
    $pdo->exec('PRAGMA cache_size = 10000');
    $pdo->exec('PRAGMA temp_store = MEMORY');
    
    $path = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Remove /fast_api.php from path
    $path = str_replace('/fast_api.php', '', $path);
    
    $dbStart = microtime(true);
    
    switch ($path) {
        case '/units':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM units ORDER BY unit_number');
                $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $units;
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $pdo->prepare('INSERT INTO units (unit_number, size, monthly_rent, status) VALUES (?, ?, ?, ?)');
                $stmt->execute([$data['unit_number'], $data['size'], $data['monthly_rent'], $data['status']]);
                $result = ['id' => $pdo->lastInsertId()];
            }
            break;
            
        case '/tenants':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM tenants ORDER BY name');
                $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $tenants;
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $pdo->prepare('INSERT INTO tenants (name, phone, email, unit_id, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$data['name'], $data['phone'], $data['email'], $data['unit_id'], $data['start_date'], $data['end_date']]);
                $result = ['id' => $pdo->lastInsertId()];
            }
            break;
            
        case '/payments':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM payments ORDER BY payment_date DESC');
                $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $payments;
            }
            break;
            
        case '/expenses':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM expenses ORDER BY date DESC');
                $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $expenses;
            }
            break;
            
        case '/stats':
            // Get quick statistics
            $stats = [];
            
            $stmt = $pdo->query('SELECT COUNT(*) as total_units FROM units');
            $stats['total_units'] = $stmt->fetchColumn();
            
            $stmt = $pdo->query('SELECT COUNT(*) as total_tenants FROM tenants');
            $stats['total_tenants'] = $stmt->fetchColumn();
            
            $stmt = $pdo->query('SELECT COUNT(*) as occupied_units FROM units WHERE status = "occupied"');
            $stats['occupied_units'] = $stmt->fetchColumn();
            
            $stmt = $pdo->query('SELECT SUM(monthly_rent) as total_rent FROM units WHERE status = "occupied"');
            $stats['total_rent'] = $stmt->fetchColumn() ?: 0;
            
            $result = $stats;
            break;
            
        default:
            http_response_code(404);
            $result = ['error' => 'Not found'];
    }
    
    $dbEnd = microtime(true);
    $dbTime = round(($dbEnd - $dbStart) * 1000, 2);
    
    $end = microtime(true);
    $totalTime = round(($end - $start) * 1000, 2);
    
    echo json_encode([
        'data' => $result,
        'performance' => [
            'database_time_ms' => $dbTime,
            'total_time_ms' => $totalTime,
            'overhead_ms' => $totalTime - $dbTime
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
