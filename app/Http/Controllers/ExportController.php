<?php

namespace App\Http\Controllers;

use App\Exports\KpiExport;
use App\Exports\QuotesExport;
use App\Exports\RfqExport;
use App\Exports\SuppliersExport;
use App\Models\Request as RfqRequest;
use App\Models\User;
use App\Services\KpiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController
{
    public function __construct(
        private KpiService $kpiService
    ) {}

    /**
     * Export RFQ Summary as PDF
     */
    public function exportRfqPdf(RfqRequest $rfq): Response
    {
        if (!Auth::user()->hasPermission('view_rfqs')) {
            abort(403, 'Unauthorized');
        }

        $rfq->load([
            'buyer',
            'items.product.category',
            'quotes.supplier',
            'quotes.items',
            'createdBy'
        ]);

        $pdf = Pdf::loadView('pdf.rfq-summary', [
            'rfq' => $rfq
        ]);

        return $pdf->download("RFQ-{$rfq->request_number}.pdf");
    }

    /**
     * Export RFQ Summary as Excel
     */
    public function exportRfqExcel(RfqRequest $rfq)
    {
        if (!Auth::user()->hasPermission('view_rfqs')) {
            abort(403, 'Unauthorized');
        }

        return Excel::download(new RfqExport($rfq), "RFQ-{$rfq->request_number}.xlsx");
    }

    /**
     * Export Quote Comparison as PDF
     */
    public function exportQuoteComparisonPdf(RfqRequest $rfq): Response
    {
        if (!Auth::user()->hasPermission('view_quotes')) {
            abort(403, 'Unauthorized');
        }

        $rfq->load([
            'quotes.supplier',
            'quotes.items.requestItem.product',
            'buyer',
            'fieldAssessment'
        ]);

        $quotes = $rfq->quotes()
            ->where('status', 'submitted')
            ->orderBy('total_amount')
            ->get();

        $pdf = Pdf::loadView('pdf.quote-comparison', [
            'rfq' => $rfq,
            'quotes' => $quotes
        ]);

        return $pdf->download("Quote-Comparison-{$rfq->request_number}.pdf");
    }

    /**
     * Export Quotes as Excel
     */
    public function exportQuotesExcel(RfqRequest $rfq)
    {
        if (!Auth::user()->hasPermission('view_quotes')) {
            abort(403, 'Unauthorized');
        }

        return Excel::download(new QuotesExport($rfq), "Quotes-{$rfq->request_number}.xlsx");
    }

    /**
     * Export KPI Report as PDF
     */
    public function exportKpiPdf(Request $request): Response
    {
        if (!Auth::user()->hasPermission('view_reports')) {
            abort(403, 'Unauthorized');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $kpiData = [
            'procurement' => $this->kpiService->calculateProcurementKpis($startDate, $endDate),
            'supplier' => $this->kpiService->calculateSupplierKpis($startDate, $endDate),
            'quote' => $this->kpiService->calculateQuoteKpis($startDate, $endDate),
            'fieldAssessment' => $this->kpiService->calculateFieldAssessmentKpis($startDate, $endDate),
        ];

        $pdf = Pdf::loadView('pdf.kpi-report', [
            'kpiData' => $kpiData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download("KPI-Report-{$startDate}-{$endDate}.pdf");
    }

    /**
     * Export KPI Report as Excel
     */
    public function exportKpiExcel(Request $request)
    {
        if (!Auth::user()->hasPermission('view_reports')) {
            abort(403, 'Unauthorized');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(
            new KpiExport($startDate, $endDate),
            "KPI-Report-{$startDate}-{$endDate}.xlsx"
        );
    }

    /**
     * Export Audit Trail as PDF
     */
    public function exportAuditPdf(Request $request): Response
    {
        if (!Auth::user()->hasPermission('view_logs')) {
            abort(403, 'Unauthorized');
        }

        $filters = $request->only(['start_date', 'end_date', 'user_id', 'action']);

        $logs = \App\Models\Log::query()
            ->with('user')
            ->when($filters['start_date'] ?? null, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['end_date'] ?? null, fn($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->when($filters['user_id'] ?? null, fn($q, $userId) => $q->where('user_id', $userId))
            ->when($filters['action'] ?? null, fn($q, $action) => $q->where('action', $action))
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();

        $pdf = Pdf::loadView('pdf.audit-report', [
            'logs' => $logs,
            'filters' => $filters
        ]);

        return $pdf->download("Audit-Trail-" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Export Suppliers as Excel
     */
    public function exportSuppliersExcel()
    {
        if (!Auth::user()->hasPermission('view_users')) {
            abort(403, 'Unauthorized');
        }

        return Excel::download(new SuppliersExport(), "Suppliers-" . now()->format('Y-m-d') . ".xlsx");
    }
}
