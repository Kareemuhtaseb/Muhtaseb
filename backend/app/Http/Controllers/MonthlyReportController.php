<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MonthlySummaryService;
use App\Services\PdfGenerationService;
use App\Models\Owner;
use Carbon\Carbon;

class MonthlyReportController extends Controller
{
    protected $summaryService;
    protected $pdfService;

    public function __construct(MonthlySummaryService $summaryService, PdfGenerationService $pdfService)
    {
        $this->summaryService = $summaryService;
        $this->pdfService = $pdfService;
    }

    /**
     * Generate and send monthly reports to all owners
     */
    public function sendMonthlyReports(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2020|max:2030'
        ]);

        $month = $request->input('month', now()->subMonth()->month);
        $year = $request->input('year', now()->subMonth()->year);

        \App\Jobs\SendMonthlyOwnerReports::dispatch($month, $year);

        return response()->json([
            'success' => true,
            'message' => 'Monthly reports job has been queued successfully',
            'month' => $month,
            'year' => $year,
            'month_name' => Carbon::create($year, $month, 1)->format('F Y')
        ]);
    }

    /**
     * Generate a preview of monthly report for a specific owner
     */
    public function previewOwnerReport(Request $request, Owner $owner)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030'
        ]);

        $month = $request->input('month');
        $year = $request->input('year');

        $summary = $this->summaryService->generateOwnerSummary($owner, $month, $year);

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Generate PDF for a specific owner's monthly report
     */
    public function generateOwnerPdf(Request $request, Owner $owner)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030'
        ]);

        $month = $request->input('month');
        $year = $request->input('year');

        $summary = $this->summaryService->generateOwnerSummary($owner, $month, $year);
        $pdf = $this->pdfService->generateOwnerMonthlyReport($summary);

        $filename = "monthly-report-{$owner->name}-" . Carbon::create($year, $month, 1)->format('F-Y') . ".pdf";
        $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '-', $filename);

        return $pdf->download($filename);
    }

    /**
     * Get summary of all owners for a specific month
     */
    public function getAllOwnersSummary(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030'
        ]);

        $month = $request->input('month');
        $year = $request->input('year');

        $summaries = $this->summaryService->generateAllOwnersSummary($month, $year);

        return response()->json([
            'success' => true,
            'data' => $summaries
        ]);
    }

    /**
     * Get list of owners eligible for monthly reports
     */
    public function getEligibleOwners()
    {
        $owners = Owner::whereNotNull('email')
            ->whereHas('properties', function($query) {
                $query->wherePivot('status', 'active');
            })
            ->with(['properties' => function($query) {
                $query->wherePivot('status', 'active');
            }])
            ->get()
            ->map(function($owner) {
                return [
                    'id' => $owner->id,
                    'name' => $owner->name,
                    'email' => $owner->email,
                    'share_percentage' => $owner->share_percentage,
                    'properties_count' => $owner->properties->count(),
                    'total_ownership_percentage' => $owner->properties->sum('pivot.ownership_percentage')
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $owners
        ]);
    }
}
