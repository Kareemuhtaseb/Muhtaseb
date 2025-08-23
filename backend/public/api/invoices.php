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
            SELECT i.*, o.name as owner_name 
            FROM invoices i 
            LEFT JOIN owners o ON i.owner_id = o.id 
            ORDER BY i.invoice_date DESC
        ');
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($invoices);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!isset($data['owner_id']) || !isset($data['amount']) || !isset($data['invoice_date']) || !isset($data['due_date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Owner ID, amount, invoice date, and due date are required']);
            exit;
        }
        
        // Generate invoice number
        $lastInvoice = $pdo->query('SELECT invoice_number FROM invoices ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
        $lastNumber = $lastInvoice ? intval(substr($lastInvoice['invoice_number'], 3)) : 0;
        $invoiceNumber = 'INV' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        
        $taxAmount = $data['tax_amount'] ?? 0;
        $totalAmount = $data['amount'] + $taxAmount;
        
        $stmt = $pdo->prepare('
            INSERT INTO invoices (owner_id, invoice_number, invoice_date, due_date, amount, tax_amount, total_amount, status, description, line_items, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['owner_id'],
            $invoiceNumber,
            $data['invoice_date'],
            $data['due_date'],
            $data['amount'],
            $taxAmount,
            $totalAmount,
            $data['status'] ?? 'draft',
            $data['description'] ?? null,
            isset($data['line_items']) ? json_encode($data['line_items']) : null,
            $data['notes'] ?? null
        ]);
        echo json_encode(['id' => $pdo->lastInsertId(), 'invoice_number' => $invoiceNumber]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get ID from query string
        $id = $_GET['id'] ?? null;
        
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid invoice ID is required']);
            exit;
        }
        
        if (!isset($data['owner_id']) || !isset($data['amount']) || !isset($data['invoice_date']) || !isset($data['due_date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Owner ID, amount, invoice date, and due date are required']);
            exit;
        }
        
        $taxAmount = $data['tax_amount'] ?? 0;
        $totalAmount = $data['amount'] + $taxAmount;
        
        $stmt = $pdo->prepare('
            UPDATE invoices 
            SET owner_id = ?, invoice_date = ?, due_date = ?, amount = ?, tax_amount = ?, total_amount = ?, 
                status = ?, description = ?, line_items = ?, notes = ?, paid_date = ? 
            WHERE id = ?
        ');
        $stmt->execute([
            $data['owner_id'],
            $data['invoice_date'],
            $data['due_date'],
            $data['amount'],
            $taxAmount,
            $totalAmount,
            $data['status'] ?? 'draft',
            $data['description'] ?? null,
            isset($data['line_items']) ? json_encode($data['line_items']) : null,
            $data['notes'] ?? null,
            $data['paid_date'] ?? null,
            $id
        ]);
        echo json_encode(['success' => true, 'message' => 'Invoice updated successfully']);
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
            $stmt = $pdo->prepare('DELETE FROM invoices WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid invoice ID']);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
