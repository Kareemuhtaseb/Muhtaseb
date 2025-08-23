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
        $stmt = $pdo->query('
            SELECT e.*, p.name as property_name, c.name as category_name 
            FROM expenses e 
            LEFT JOIN properties p ON e.property_id = p.id 
            LEFT JOIN categories c ON e.category_id = c.id 
            ORDER BY e.date DESC
        ');
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($expenses);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO expenses (property_id, date, payee, memo, category_id, amount) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['property_id'], 
            $data['date'], 
            $data['payee'], 
            $data['memo'] ?? null,
            $data['category_id'],
            $data['amount']
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Handle DELETE request - get ID from query string or path
        $id = null;
        
        // Try to get ID from query string first
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            // Try to get ID from path
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $segments = explode('/', trim($path, '/'));
            $id = end($segments);
        }
        
        if (is_numeric($id)) {
            $stmt = $pdo->prepare('DELETE FROM expenses WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Expense deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid expense ID']);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
