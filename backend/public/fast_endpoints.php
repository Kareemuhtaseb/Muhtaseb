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
    
    // Remove /fast_endpoints.php from path
    $path = str_replace('/fast_endpoints.php', '', $path);
    
    $dbStart = microtime(true);
    
    switch ($path) {
        case '/api/units':
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
            
        case '/api/tenants':
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
            
        case '/api/payments':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM payments ORDER BY payment_date DESC');
                $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $payments;
            }
            break;
            
        case '/api/expenses':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM expenses ORDER BY date DESC');
                $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $expenses;
            }
            break;
            
        case '/api/owners':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM owners ORDER BY name');
                $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $owners;
            }
            break;
            
        case '/api/distributions':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM distributions ORDER BY date DESC');
                $distributions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = $distributions;
            }
            break;
            
        case '/api/reports/monthly':
            // Get monthly report data
            $stmt = $pdo->query('SELECT 
                COUNT(*) as total_units,
                SUM(CASE WHEN status = "occupied" THEN 1 ELSE 0 END) as occupied_units,
                SUM(CASE WHEN status = "occupied" THEN monthly_rent ELSE 0 END) as total_rent
                FROM units');
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            break;
            
        case '/api/net-income':
            // Get net income calculation
            $stmt = $pdo->query('SELECT 
                SUM(CASE WHEN status = "occupied" THEN monthly_rent ELSE 0 END) as total_income
                FROM units');
            $income = $stmt->fetchColumn() ?: 0;
            
            $stmt = $pdo->query('SELECT SUM(amount) as total_expenses FROM expenses');
            $expenses = $stmt->fetchColumn() ?: 0;
            
            $result = [
                'total_income' => $income,
                'total_expenses' => $expenses,
                'net_income' => $income - $expenses
            ];
            break;
            
        default:
            http_response_code(404);
            $result = ['error' => 'Not found'];
    }
    
    $dbEnd = microtime(true);
    $dbTime = round(($dbEnd - $dbStart) * 1000, 2);
    
    $end = microtime(true);
    $totalTime = round(($end - $start) * 1000, 2);
    
    // Return just the data for frontend compatibility
    echo json_encode($result);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
