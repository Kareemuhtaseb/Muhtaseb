<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Owner;

class OwnerMonthlyReport extends Mailable
{
    use Queueable, SerializesModels;

    public $summary;
    public $month;

    public function __construct($summary, $month)
    {
        $this->summary = $summary;
        $this->month = $month;
    }

    public function build()
    {
        $pdfService = app(\App\Services\PdfGenerationService::class);
        $pdf = $pdfService->generateOwnerMonthlyReport($this->summary);
        
        $filename = "monthly-report-{$this->summary['owner']->name}-{$this->month}.pdf";
        $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '-', $filename);
        
        return $this->subject("📊 Monthly Property Report - {$this->month}")
                    ->view('emails.owner-monthly-report-email')
                    ->attachData($pdf->output(), $filename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
