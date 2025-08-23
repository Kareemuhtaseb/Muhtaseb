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
            SELECT od.*, o.name as owner_name 
            FROM owner_distributions od 
            LEFT JOIN owners o ON od.owner_id = o.id 
            ORDER BY od.date DESC
        ');
        $distributions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($distributions);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['owner_id']) || !isset($data['date']) || !isset($data['amount'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Owner ID, date, and amount are required']);
            exit;
        }
        
        $stmt = $pdo->prepare('INSERT INTO owner_distributions (owner_id, date, amount, notes) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $data['owner_id'], 
            $data['date'], 
            $data['amount'],
            $data['notes'] ?? null
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get ID from query string
        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid distribution ID is required']);
            exit;
        }
        
        if (!isset($data['owner_id']) || !isset($data['date']) || !isset($data['amount'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Owner ID, date, and amount are required']);
            exit;
        }
        
        $stmt = $pdo->prepare('UPDATE owner_distributions SET owner_id = ?, date = ?, amount = ?, notes = ? WHERE id = ?');
        $stmt->execute([
            $data['owner_id'],
            $data['date'],
            $data['amount'],
            $data['notes'] ?? null,
            $id
        ]);
        echo json_encode(['success' => true, 'message' => 'Distribution updated successfully']);
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
            $stmt = $pdo->prepare('DELETE FROM owner_distributions WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Distribution deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid distribution ID']);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
