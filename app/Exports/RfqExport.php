<?php

namespace App\Exports;

use App\Models\Request as RfqRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RfqExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function __construct(
        private RfqRequest $rfq
    ) {
        $this->rfq->load(['items.product.category', 'buyer', 'createdBy']);
    }

    public function collection()
    {
        return $this->rfq->items;
    }

    public function headings(): array
    {
        return [
            'Item #',
            'Product Code',
            'Product Name',
            'Category',
            'Quantity',
            'Unit Price',
            'Total',
            'Description',
            'Specifications',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->product->code ?? 'N/A',
            $item->product->name ?? 'N/A',
            $item->product->category->name ?? 'N/A',
            $item->quantity,
            $item->unit_price ?? 0,
            ($item->quantity * ($item->unit_price ?? 0)),
            $item->description ?? '',
            $item->specifications ?? '',
        ];
    }

    public function title(): string
    {
        return 'RFQ ' . $this->rfq->request_number;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
