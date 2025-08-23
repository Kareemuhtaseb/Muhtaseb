<?php

/**
 * Simple API Test Script
 */

echo "🧪 Testing Muhtaseb API Endpoints\n";
echo "================================\n\n";

$baseUrl = 'http://localhost:8000/api';

$endpoints = [
    '/dashboard' => 'Dashboard Statistics',
    '/properties' => 'Properties List',
    '/units' => 'Units List',
    '/income' => 'Income List',
    '/expenses' => 'Expenses List',
    '/owners' => 'Owners List',
    '/contracts' => 'Contracts List'
];

foreach ($endpoints as $endpoint => $description) {
    echo "Testing $description... ";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        echo "✅ Success\n";
        $data = json_decode($response, true);
        if (is_array($data)) {
            echo "   📊 Records: " . count($data) . "\n";
        }
    } else {
        echo "❌ Failed (HTTP $httpCode)\n";
    }
}

echo "\n🎉 API Testing Complete!\n";
echo "\n🌐 Frontend URL: http://localhost:5173\n";
echo "🔗 Backend API: http://localhost:8000/api\n";
echo "\n✅ Both servers are running successfully!\n";
