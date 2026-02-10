<?php

namespace App\Exports;

use App\Models\Request as RfqRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuotesExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function __construct(
        private RfqRequest $rfq
    ) {
        $this->rfq->load(['quotes.supplier', 'quotes.items.requestItem.product']);
    }

    public function collection()
    {
        return $this->rfq->quotes()
            ->where('status', 'submitted')
            ->orderBy('total_amount')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Quote #',
            'Supplier',
            'Total Amount',
            'Currency',
            'Delivery Time (Days)',
            'Payment Terms',
            'Validity (Days)',
            'Notes',
            'Status',
            'Submitted At',
        ];
    }

    public function map($quote): array
    {
        return [
            $quote->quote_number,
            $quote->supplier->name ?? 'N/A',
            $quote->total_amount,
            $quote->currency ?? 'AZN',
            $quote->delivery_time ?? 'N/A',
            $quote->payment_terms ?? 'N/A',
            $quote->validity_days ?? 'N/A',
            $quote->notes ?? '',
            ucfirst($quote->status),
            $quote->submitted_at?->format('Y-m-d H:i'),
        ];
    }

    public function title(): string
    {
        return 'Quotes for ' . $this->rfq->request_number;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
