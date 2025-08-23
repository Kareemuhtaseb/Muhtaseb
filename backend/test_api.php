<?php
// Simple test to verify API endpoints
echo "Testing API endpoints...\n";

// Test owners endpoint
$url = 'http://localhost:8000/api/owners.php';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json'
    ]
]);

$response = file_get_contents($url, false, $context);
if ($response !== false) {
    echo "✅ Owners API is working\n";
    $data = json_decode($response, true);
    echo "Found " . count($data) . " owners\n";
} else {
    echo "❌ Owners API failed\n";
}

// Test income endpoint
$url = 'http://localhost:8000/api/income.php';
$response = file_get_contents($url, false, $context);
if ($response !== false) {
    echo "✅ Income API is working\n";
    $data = json_decode($response, true);
    echo "Found " . count($data) . " income records\n";
} else {
    echo "❌ Income API failed\n";
}

// Test expenses endpoint
$url = 'http://localhost:8000/api/expenses.php';
$response = file_get_contents($url, false, $context);
if ($response !== false) {
    echo "✅ Expenses API is working\n";
    $data = json_decode($response, true);
    echo "Found " . count($data) . " expense records\n";
} else {
    echo "❌ Expenses API failed\n";
}

echo "\nAPI test completed!\n";
?>
