<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove /api.php from path
$path = str_replace('/api.php', '', $path);

try {
    switch ($path) {
        case '/units':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM units');
                $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($units);
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $pdo->prepare('INSERT INTO units (unit_number, size, monthly_rent, status) VALUES (?, ?, ?, ?)');
                $stmt->execute([$data['unit_number'], $data['size'], $data['monthly_rent'], $data['status']]);
                echo json_encode(['id' => $pdo->lastInsertId()]);
            }
            break;
            
        case '/tenants':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM tenants');
                $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($tenants);
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $pdo->prepare('INSERT INTO tenants (name, phone, email, unit_id, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$data['name'], $data['phone'], $data['email'], $data['unit_id'], $data['start_date'], $data['end_date']]);
                echo json_encode(['id' => $pdo->lastInsertId()]);
            }
            break;
            
        case '/payments':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM payments');
                $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($payments);
            }
            break;
            
        case '/expenses':
            if ($method === 'GET') {
                $stmt = $pdo->query('SELECT * FROM expenses');
                $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($expenses);
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
