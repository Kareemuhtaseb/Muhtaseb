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
        // Get all monthly reports
        $stmt = $pdo->query('
            SELECT 
                mr.id,
                mr.owner_id,
                o.name as owner_name,
                mr.month,
                mr.year,
                mr.total_amount,
                mr.status,
                mr.created_at,
                mr.sent_at
            FROM monthly_reports mr
            LEFT JOIN owners o ON mr.owner_id = o.id
            ORDER BY mr.created_at DESC
        ');
        
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($reports);
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['owner_id']) && isset($input['month']) && isset($input['year'])) {
            // Calculate total amount for the owner based on their share percentage
            $ownerId = $input['owner_id'];
            $month = $input['month'];
            $year = $input['year'];
            
            // Get owner's share percentage
            $stmt = $pdo->prepare('SELECT share_percentage FROM owners WHERE id = ?');
            $stmt->execute([$ownerId]);
            $owner = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$owner) {
                throw new Exception('Owner not found');
            }
            
            // Calculate total income for the month
            $monthStr = sprintf('%04d-%02d', $year, $month);
            $stmt = $pdo->prepare('
                SELECT SUM(amount) as total_income 
                FROM income 
                WHERE strftime("%Y-%m", date) = ?
            ');
            $stmt->execute([$monthStr]);
            $income = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Calculate total expenses for the month
            $stmt = $pdo->prepare('
                SELECT SUM(amount) as total_expenses 
                FROM expenses 
                WHERE strftime("%Y-%m", date) = ?
            ');
            $stmt->execute([$monthStr]);
            $expenses = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $totalIncome = $income['total_income'] ?? 0;
            $totalExpenses = $expenses['total_expenses'] ?? 0;
            $netIncome = $totalIncome - $totalExpenses;
            $ownerAmount = ($netIncome * $owner['share_percentage']) / 100;
            
            // Check if report already exists
            $stmt = $pdo->prepare('
                SELECT id FROM monthly_reports 
                WHERE owner_id = ? AND month = ? AND year = ?
            ');
            $stmt->execute([$ownerId, $month, $year]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // Update existing report
                $stmt = $pdo->prepare('
                    UPDATE monthly_reports 
                    SET total_amount = ?, updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ');
                $stmt->execute([$ownerAmount, $existing['id']]);
                $reportId = $existing['id'];
            } else {
                // Create new report
                $stmt = $pdo->prepare('
                    INSERT INTO monthly_reports (owner_id, month, year, total_amount, status, created_at)
                    VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
                ');
                $stmt->execute([$ownerId, $month, $year, $ownerAmount, 'generated']);
                $reportId = $pdo->lastInsertId();
            }
            
            // Return the generated report
            $stmt = $pdo->prepare('
                SELECT 
                    mr.id,
                    mr.owner_id,
                    o.name as owner_name,
                    mr.month,
                    mr.year,
                    mr.total_amount,
                    mr.status,
                    mr.created_at
                FROM monthly_reports mr
                LEFT JOIN owners o ON mr.owner_id = o.id
                WHERE mr.id = ?
            ');
            $stmt->execute([$reportId]);
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'message' => 'Monthly report generated successfully',
                'data' => $report
            ]);
        } else {
            throw new Exception('Missing required fields: owner_id, month, year');
        }
    }
    
    // Handle sending reports
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/send') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['owner_id']) && isset($input['month']) && isset($input['year'])) {
            $ownerId = $input['owner_id'];
            $month = $input['month'];
            $year = $input['year'];
            
            // Update report status to sent
            $stmt = $pdo->prepare('
                UPDATE monthly_reports 
                SET status = ?, sent_at = CURRENT_TIMESTAMP
                WHERE owner_id = ? AND month = ? AND year = ?
            ');
            $stmt->execute(['sent', $ownerId, $month, $year]);
            
            // Get owner email for sending
            $stmt = $pdo->prepare('SELECT name, email FROM owners WHERE id = ?');
            $stmt->execute([$ownerId]);
            $owner = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($owner && $owner['email']) {
                // Here you would implement email sending logic
                // For now, we'll just log it
                error_log("Sending monthly report to {$owner['email']} for {$owner['name']} - {$month}/{$year}");
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Monthly report sent successfully'
            ]);
        } else {
            throw new Exception('Missing required fields for sending report');
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
