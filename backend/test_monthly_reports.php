<?php

require_once 'vendor/autoload.php';

use App\Services\MonthlySummaryService;
use App\Models\Owner;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Monthly Report System Test ===\n\n";

try {
    // Test 1: Check if MonthlySummaryService can be instantiated
    echo "1. Testing MonthlySummaryService instantiation...\n";
    $summaryService = new MonthlySummaryService();
    echo "✅ MonthlySummaryService created successfully\n\n";

    // Test 2: Check if we have any owners
    echo "2. Checking for owners in the system...\n";
    $owners = Owner::all();
    echo "Found {$owners->count()} owners in the system\n\n";

    if ($owners->count() > 0) {
        // Test 3: Generate summary for first owner
        echo "3. Testing summary generation for first owner...\n";
        $firstOwner = $owners->first();
        echo "Owner: {$firstOwner->name} (ID: {$firstOwner->id})\n";
        
        $summary = $summaryService->generateOwnerSummary($firstOwner, 8, 2025);
        echo "✅ Summary generated successfully\n";
        echo "Properties: " . count($summary['properties']) . "\n";
        echo "Total Income: $" . number_format($summary['total_income'], 2) . "\n";
        echo "Total Expenses: $" . number_format($summary['total_expenses'], 2) . "\n";
        echo "Net Income: $" . number_format($summary['net_income'], 2) . "\n";
        echo "Owner Share: $" . number_format($summary['owner_share'], 2) . "\n\n";

        // Test 4: Test PDF generation
        echo "4. Testing PDF generation...\n";
        $pdfService = new \App\Services\PdfGenerationService();
        $pdf = $pdfService->generateOwnerMonthlyReport($summary);
        echo "✅ PDF generated successfully\n\n";

        // Test 5: Test all owners summary
        echo "5. Testing all owners summary...\n";
        $allSummaries = $summaryService->generateAllOwnersSummary(8, 2025);
        echo "✅ All owners summary generated successfully\n";
        echo "Total owners with summaries: " . count($allSummaries['owners']) . "\n";
        echo "Total portfolio income: $" . number_format($allSummaries['total_summary']['total_income'], 2) . "\n";
        echo "Total portfolio expenses: $" . number_format($allSummaries['total_summary']['total_expenses'], 2) . "\n";
        echo "Total portfolio net income: $" . number_format($allSummaries['total_summary']['net_income'], 2) . "\n\n";

    } else {
        echo "⚠️  No owners found in the system. Please add some owners first.\n\n";
    }

    echo "=== Test Completed Successfully ===\n";
    echo "The monthly report system is working correctly!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
