<?php

/**
 * Simple API Server for Muhtaseb
 * 
 * This server provides API endpoints using file-based storage
 * when PDO drivers are not available
 */

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Simple database class
class SimpleDB {
    private $dataDir;
    
    public function __construct() {
        $this->dataDir = __DIR__ . '/data';
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
    }
    
    public function getAll($table) {
        $file = $this->dataDir . "/$table.json";
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true) ?: [];
        }
        return [];
    }
    
    public function getById($table, $id) {
        $data = $this->getAll($table);
        foreach ($data as $record) {
            if ($record['id'] == $id) {
                return $record;
            }
        }
        return null;
    }
    
    public function create($table, $data) {
        $records = $this->getAll($table);
        $data['id'] = count($records) + 1;
        $records[] = $data;
        file_put_contents($this->dataDir . "/$table.json", json_encode($records, JSON_PRETTY_PRINT));
        return $data;
    }
    
    public function update($table, $id, $data) {
        $records = $this->getAll($table);
        foreach ($records as &$record) {
            if ($record['id'] == $id) {
                $record = array_merge($record, $data);
                break;
            }
        }
        file_put_contents($this->dataDir . "/$table.json", json_encode($records, JSON_PRETTY_PRINT));
        return true;
    }
    
    public function delete($table, $id) {
        $records = $this->getAll($table);
        $records = array_filter($records, function($record) use ($id) {
            return $record['id'] != $id;
        });
        file_put_contents($this->dataDir . "/$table.json", json_encode(array_values($records), JSON_PRETTY_PRINT));
        return true;
    }
}

// Initialize database
$db = new SimpleDB();

// Get request path
$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove base path if exists
$path = str_replace('/api', '', $path);
$path = parse_url($path, PHP_URL_PATH);

// Route handling
try {
    switch ($path) {
        case '/dashboard':
            if ($method === 'GET') {
                $properties = $db->getAll('properties');
                $units = $db->getAll('units');
                $income = $db->getAll('income');
                $expenses = $db->getAll('expenses');
                
                $totalIncome = array_sum(array_column($income, 'amount'));
                $totalExpenses = array_sum(array_column($expenses, 'amount'));
                $occupiedUnits = count(array_filter($units, fn($u) => $u['status'] === 'occupied'));
                
                echo json_encode([
                    'overview' => [
                        'total_income' => $totalIncome,
                        'total_expenses' => $totalExpenses,
                        'net_income' => $totalIncome - $totalExpenses,
                        'properties' => count($properties),
                        'units' => count($units),
                        'occupied_units' => $occupiedUnits,
                        'occupancy_rate' => count($units) > 0 ? ($occupiedUnits / count($units)) * 100 : 0
                    ],
                    'recent_activities' => [
                        'recent_income' => array_slice($income, -5),
                        'recent_expenses' => array_slice($expenses, -5)
                    ]
                ]);
            }
            break;
            
        case '/properties':
            if ($method === 'GET') {
                echo json_encode($db->getAll('properties'));
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $db->create('properties', $data);
                echo json_encode($result);
            }
            break;
            
        case '/units':
            if ($method === 'GET') {
                echo json_encode($db->getAll('units'));
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $db->create('units', $data);
                echo json_encode($result);
            }
            break;
            
        case '/income':
            if ($method === 'GET') {
                echo json_encode($db->getAll('income'));
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $db->create('income', $data);
                echo json_encode($result);
            }
            break;
            
        case '/expenses':
            if ($method === 'GET') {
                echo json_encode($db->getAll('expenses'));
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $db->create('expenses', $data);
                echo json_encode($result);
            }
            break;
            
        case '/owners':
            if ($method === 'GET') {
                echo json_encode($db->getAll('owners'));
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $db->create('owners', $data);
                echo json_encode($result);
            }
            break;
            
        case '/contracts':
            if ($method === 'GET') {
                echo json_encode($db->getAll('contracts'));
            } elseif ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = $db->create('contracts', $data);
                echo json_encode($result);
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
