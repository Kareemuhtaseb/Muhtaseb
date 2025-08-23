<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class PdfGenerationService
{
    public function generateOwnerMonthlyReport($summary)
    {
        $pdf = PDF::loadView('emails.owner-monthly-report', [
            'summary' => $summary,
            'month' => $summary['month_name']
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }
    
    public function generateAllOwnersReport($summaries, $month, $year)
    {
        $pdf = PDF::loadView('emails.all-owners-monthly-report', [
            'summaries' => $summaries,
            'month' => Carbon::create($year, $month, 1)->format('F Y')
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }
    
    public function generateDetailedPropertyReport($propertyData, $month)
    {
        $pdf = PDF::loadView('emails.detailed-property-report', [
            'propertyData' => $propertyData,
            'month' => $month
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }
}
