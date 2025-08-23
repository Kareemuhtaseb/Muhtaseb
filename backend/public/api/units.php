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
            SELECT u.*, p.name as property_name 
            FROM units u 
            LEFT JOIN properties p ON u.property_id = p.id 
            ORDER BY u.unit_number
        ');
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($units);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['property_id']) || !isset($data['unit_number']) || !isset($data['monthly_rent_expected'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Property ID, unit number, and monthly rent are required']);
            exit;
        }
        
        $stmt = $pdo->prepare('
            INSERT INTO units (property_id, unit_number, shop_name, shop_number, company_name, monthly_rent_expected, status, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['property_id'], 
            $data['unit_number'], 
            $data['shop_name'] ?? null,
            $data['shop_number'] ?? null,
            $data['company_name'] ?? null,
            $data['monthly_rent_expected'], 
            $data['status'] ?? 'available',
            $data['notes'] ?? null
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get ID from query string
        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid unit ID is required']);
            exit;
        }
        
        if (!isset($data['property_id']) || !isset($data['unit_number']) || !isset($data['monthly_rent_expected'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Property ID, unit number, and monthly rent are required']);
            exit;
        }
        
        $stmt = $pdo->prepare('
            UPDATE units 
            SET property_id = ?, unit_number = ?, shop_name = ?, shop_number = ?, company_name = ?, 
                monthly_rent_expected = ?, status = ?, notes = ? 
            WHERE id = ?
        ');
        $stmt->execute([
            $data['property_id'], 
            $data['unit_number'], 
            $data['shop_name'] ?? null,
            $data['shop_number'] ?? null,
            $data['company_name'] ?? null,
            $data['monthly_rent_expected'], 
            $data['status'] ?? 'available',
            $data['notes'] ?? null,
            $id
        ]);
        echo json_encode(['success' => true, 'message' => 'Unit updated successfully']);
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
            $stmt = $pdo->prepare('DELETE FROM units WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Unit deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid unit ID']);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
