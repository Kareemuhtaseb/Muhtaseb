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
        $stmt = $pdo->query('SELECT id, name, email, role, phone, is_active, created_at FROM users ORDER BY name');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['role'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Name, email, and role are required']);
            exit;
        }
        
        // Check if email already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already exists']);
            exit;
        }
        
        $stmt = $pdo->prepare('INSERT INTO users (name, email, role, phone, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['name'], 
            $data['email'], 
            $data['role'], 
            $data['phone'] ?? null,
            $data['is_active'] ?? true,
            date('Y-m-d H:i:s')
        ]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get ID from query string
        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid user ID is required']);
            exit;
        }
        
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['role'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Name, email, and role are required']);
            exit;
        }
        
        // Check if email already exists for other users
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$data['email'], $id]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already exists']);
            exit;
        }
        
        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, role = ?, phone = ?, is_active = ? WHERE id = ?');
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['role'],
            $data['phone'] ?? null,
            $data['is_active'] ?? true,
            $id
        ]);
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
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
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid user ID']);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
