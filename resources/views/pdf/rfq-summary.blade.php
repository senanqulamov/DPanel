<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RFQ {{ $rfq->request_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #3b82f6;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3b82f6;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
        }
        .badge-approved {
            background-color: #10b981;
            color: white;
        }
        .badge-pending {
            background-color: #f59e0b;
            color: white;
        }
        .badge-rejected {
            background-color: #ef4444;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REQUEST FOR QUOTATION</h1>
        <p>{{ $rfq->request_number }}</p>
    </div>

    <div class="info-section">
        <h2>RFQ Information</h2>
        <div class="info-row">
            <div class="info-label">RFQ Number:</div>
            <div class="info-value">{{ $rfq->request_number }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Buyer:</div>
            <div class="info-value">{{ $rfq->buyer->name ?? 'N/A' }} ({{ $rfq->buyer->company_name ?? '' }})</div>
        </div>
        <div class="info-row">
            <div class="info-label">Request Type:</div>
            <div class="info-value">{{ ucfirst($rfq->request_type ?? 'internal') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="badge badge-{{ $rfq->status }}">{{ ucfirst($rfq->status) }}</span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Created:</div>
            <div class="info-value">{{ $rfq->created_at->format('d M Y H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Deadline:</div>
            <div class="info-value">{{ $rfq->deadline?->format('d M Y H:i') ?? 'N/A' }}</div>
        </div>
        @if($rfq->delivery_location)
        <div class="info-row">
            <div class="info-label">Delivery Location:</div>
            <div class="info-value">{{ $rfq->delivery_location }}</div>
        </div>
        @endif
    </div>

    @if($rfq->description)
    <div class="info-section">
        <h2>Description</h2>
        <p>{{ $rfq->description }}</p>
    </div>
    @endif

    <div class="info-section">
        <h2>Items Requested</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rfq->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->category->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price ?? 0, 2) }}</td>
                    <td>{{ number_format(($item->quantity * ($item->unit_price ?? 0)), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($rfq->requires_field_assessment)
    <div class="info-section">
        <h2>Field Assessment</h2>
        <div class="info-row">
            <div class="info-label">Required:</div>
            <div class="info-value">Yes</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">{{ ucfirst($rfq->field_assessment_status ?? 'pending') }}</div>
        </div>
        @if($rfq->fieldAssessment)
        <div class="info-row">
            <div class="info-label">Recommended Price:</div>
            <div class="info-value">{{ number_format($rfq->fieldAssessment->recommended_price ?? 0, 2) }}</div>
        </div>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }} | DPanel Procurement System</p>
    </div>
</body>
</html>
