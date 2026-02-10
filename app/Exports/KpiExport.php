<?php

namespace App\Exports;

use App\Services\KpiService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KpiExport implements WithMultipleSheets
{
    public function __construct(
        private ?string $startDate = null,
        private ?string $endDate = null
    ) {}

    public function sheets(): array
    {
        $kpiService = app(KpiService::class);

        $procurementKpis = $kpiService->calculateProcurementKpis($this->startDate, $this->endDate);
        $supplierKpis = $kpiService->calculateSupplierKpis($this->startDate, $this->endDate);
        $quoteKpis = $kpiService->calculateQuoteKpis($this->startDate, $this->endDate);
        $fieldAssessmentKpis = $kpiService->calculateFieldAssessmentKpis($this->startDate, $this->endDate);

        return [
            new ProcurementKpiSheet($procurementKpis),
            new SupplierKpiSheet($supplierKpis),
            new QuoteKpiSheet($quoteKpis),
            new FieldAssessmentKpiSheet($fieldAssessmentKpis),
        ];
    }
}

class ProcurementKpiSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data) {}

    public function array(): array
    {
        return [
            ['Total RFQs', $this->data['totalRfqs'] ?? 0],
            ['Active RFQs', $this->data['activeRfqs'] ?? 0],
            ['Completed RFQs', $this->data['completedRfqs'] ?? 0],
            ['Avg Processing Time (Days)', $this->data['avgProcessingTime'] ?? 0],
            ['Avg Response Time (Hours)', $this->data['avgResponseTime'] ?? 0],
            ['On-time Completion Rate (%)', $this->data['onTimeRate'] ?? 0],
        ];
    }

    public function headings(): array
    {
        return ['KPI Metric', 'Value'];
    }

    public function title(): string
    {
        return 'Procurement KPIs';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:A' => ['font' => ['bold' => true]],
        ];
    }
}

class SupplierKpiSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data) {}

    public function array(): array
    {
        return [
            ['Total Suppliers', $this->data['totalSuppliers'] ?? 0],
            ['Active Suppliers', $this->data['activeSuppliers'] ?? 0],
            ['Public Tender Eligible', $this->data['publicTenderEligible'] ?? 0],
            ['Avg Response Time (Hours)', $this->data['avgResponseTime'] ?? 0],
            ['Response Rate (%)', $this->data['responseRate'] ?? 0],
            ['On-time Delivery Rate (%)', $this->data['onTimeDeliveryRate'] ?? 0],
        ];
    }

    public function headings(): array
    {
        return ['KPI Metric', 'Value'];
    }

    public function title(): string
    {
        return 'Supplier KPIs';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:A' => ['font' => ['bold' => true]],
        ];
    }
}

class QuoteKpiSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data) {}

    public function array(): array
    {
        return [
            ['Total Quotes', $this->data['totalQuotes'] ?? 0],
            ['Submitted Quotes', $this->data['submittedQuotes'] ?? 0],
            ['Avg Quotes per RFQ', $this->data['avgQuotesPerRfq'] ?? 0],
            ['Avg Quote Value', $this->data['avgQuoteValue'] ?? 0],
            ['Total Quote Value', $this->data['totalQuoteValue'] ?? 0],
            ['Acceptance Rate (%)', $this->data['acceptanceRate'] ?? 0],
        ];
    }

    public function headings(): array
    {
        return ['KPI Metric', 'Value'];
    }

    public function title(): string
    {
        return 'Quote KPIs';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:A' => ['font' => ['bold' => true]],
        ];
    }
}

class FieldAssessmentKpiSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private array $data) {}

    public function array(): array
    {
        return [
            ['Total Assessments', $this->data['totalAssessments'] ?? 0],
            ['Completed Assessments', $this->data['completedAssessments'] ?? 0],
            ['Avg Time to Start (Hours)', $this->data['avgTimeToStart'] ?? 0],
            ['Avg Time to Complete (Hours)', $this->data['avgTimeToComplete'] ?? 0],
            ['Success Rate (%)', $this->data['successRate'] ?? 0],
        ];
    }

    public function headings(): array
    {
        return ['KPI Metric', 'Value'];
    }

    public function title(): string
    {
        return 'Field Assessment KPIs';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:A' => ['font' => ['bold' => true]],
        ];
    }
}
