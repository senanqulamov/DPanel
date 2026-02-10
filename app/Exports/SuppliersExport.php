<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuppliersExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function query()
    {
        return User::query()
            ->where('is_supplier', true)
            ->with('roles')
            ->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Company Name',
            'Phone',
            'Mobile',
            'Supplier Code',
            'DUNS Number',
            'Currency',
            'Credit Limit',
            'Status',
            'Public Tender Eligible',
            'Active',
            'Registered At',
        ];
    }

    public function map($supplier): array
    {
        return [
            $supplier->id,
            $supplier->name,
            $supplier->email,
            $supplier->company_name ?? 'N/A',
            $supplier->phone ?? 'N/A',
            $supplier->mobile ?? 'N/A',
            $supplier->supplier_code ?? 'N/A',
            $supplier->duns_number ?? 'N/A',
            $supplier->currency ?? 'AZN',
            $supplier->credit_limit ?? 0,
            $supplier->supplier_status ?? 'N/A',
            $supplier->is_public_tender_eligible ? 'Yes' : 'No',
            $supplier->is_active ? 'Yes' : 'No',
            $supplier->created_at?->format('Y-m-d H:i'),
        ];
    }

    public function title(): string
    {
        return 'Suppliers';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
