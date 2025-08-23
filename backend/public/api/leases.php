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
            SELECT l.*, u.unit_number, t.full_name as tenant_name 
            FROM leases l 
            LEFT JOIN units u ON l.unit_id = u.id 
            LEFT JOIN tenants t ON l.tenant_id = t.id 
            ORDER BY l.start_date DESC
        ');
        $leases = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($leases);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO leases (unit_id, tenant_id, monthly_rent, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['unit_id'], 
            $data['tenant_id'], 
            $data['monthly_rent'], 
            $data['start_date'], 
            $data['end_date'] ?? null,
            $data['status'] ?? 'active'
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
            $stmt = $pdo->prepare('DELETE FROM leases WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Lease deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid lease ID']);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
