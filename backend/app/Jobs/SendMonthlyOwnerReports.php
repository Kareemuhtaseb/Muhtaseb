<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Owner;
use App\Mail\OwnerMonthlyReport;
use App\Services\MonthlySummaryService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendMonthlyOwnerReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $month;
    public $year;
    public $timeout = 300; // 5 minutes timeout

    public function __construct($month = null, $year = null)
    {
        $this->month = $month ?? now()->subMonth()->month;
        $this->year = $year ?? now()->subMonth()->year;
    }

    public function handle()
    {
        $summaryService = app(MonthlySummaryService::class);
        $owners = Owner::whereNotNull('email')->get();
        
        $sentCount = 0;
        $errorCount = 0;
        
        Log::info("Starting monthly report generation for {$this->month}/{$this->year}");
        
        foreach ($owners as $owner) {
            try {
                if ($owner->properties()->wherePivot('status', 'active')->count() > 0) {
                    $summary = $summaryService->generateOwnerSummary($owner, $this->month, $this->year);
                    
                    // Only send if there's meaningful data
                    if ($summary['total_income'] > 0 || $summary['total_expenses'] > 0 || $summary['distributions'] > 0) {
                        Mail::to($owner->email)
                            ->queue(new OwnerMonthlyReport($summary, $summary['month_name']));
                        
                        $sentCount++;
                        Log::info("Queued monthly report for owner: {$owner->name} ({$owner->email})");
                    } else {
                        Log::info("Skipping report for owner {$owner->name} - no financial activity");
                    }
                }
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Failed to generate report for owner {$owner->name}: " . $e->getMessage());
            }
        }
        
        Log::info("Monthly report generation completed. Sent: {$sentCount}, Errors: {$errorCount}");
    }
}
