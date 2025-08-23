<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendMonthlyOwnerReports;
use Carbon\Carbon;

class SendMonthlyReports extends Command
{
    protected $signature = 'reports:send-monthly {--month=} {--year=} {--force}';
    protected $description = 'Send monthly property reports to all owners';

    public function handle()
    {
        $month = $this->option('month') ?? now()->subMonth()->month;
        $year = $this->option('year') ?? now()->subMonth()->year;
        $force = $this->option('force');
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        
        if (!$force) {
            if (!$this->confirm("Send monthly reports for {$monthName}?")) {
                $this->info('Operation cancelled.');
                return;
            }
        }
        
        $this->info("Sending monthly reports for {$monthName}...");
        
        SendMonthlyOwnerReports::dispatch($month, $year);
        
        $this->info('✅ Monthly reports job has been queued successfully!');
        $this->info("Reports will be sent to all owners with active properties.");
    }
}
