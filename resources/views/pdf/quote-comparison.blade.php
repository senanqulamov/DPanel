<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote Comparison - {{ $rfq->request_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #3b82f6;
            font-size: 18px;
        }
        .info-section {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #3b82f6;
            color: white;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .highlight-best {
            background-color: #d1fae5 !important;
            font-weight: bold;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .field-assessment-note {
            margin-top: 15px;
            padding: 10px;
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>QUOTE COMPARISON</h1>
        <p>RFQ: {{ $rfq->request_number }}</p>
    </div>

    <div class="info-section">
        <strong>Buyer:</strong> {{ $rfq->buyer->name ?? 'N/A' }}<br>
        <strong>Request Type:</strong> {{ ucfirst($rfq->request_type ?? 'internal') }}<br>
        <strong>Total Quotes Received:</strong> {{ $quotes->count() }}
    </div>

    @if($rfq->fieldAssessment)
    <div class="field-assessment-note">
        <strong>Field Assessment Recommendation:</strong><br>
        Recommended Price: <strong>{{ number_format($rfq->fieldAssessment->recommended_price ?? 0, 2) }} {{ $rfq->fieldAssessment->currency ?? 'AZN' }}</strong><br>
        Notes: {{ $rfq->fieldAssessment->notes ?? 'N/A' }}
    </div>
    @endif

    <div class="info-section">
        <h2>Quote Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Total Amount</th>
                    <th>Currency</th>
                    <th>Delivery Time</th>
                    <th>Payment Terms</th>
                    <th>Validity</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotes as $index => $quote)
                <tr class="{{ $index === 0 ? 'highlight-best' : '' }}">
                    <td>{{ $quote->supplier->name ?? 'N/A' }}</td>
                    <td>{{ number_format($quote->total_amount, 2) }}</td>
                    <td>{{ $quote->currency ?? 'AZN' }}</td>
                    <td>{{ $quote->delivery_time ?? 'N/A' }}</td>
                    <td>{{ $quote->payment_terms ?? 'N/A' }}</td>
                    <td>{{ $quote->validity_days ?? 'N/A' }} days</td>
                    <td>{{ $quote->submitted_at?->format('d M Y H:i') ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($quotes->isNotEmpty())
    <div class="info-section">
        <h2>Detailed Item Comparison</h2>
        @foreach($rfq->items as $requestItem)
        <h3 style="margin-top: 15px;">{{ $requestItem->product->name ?? 'Product' }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Delivery Time</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotes as $quote)
                    @php
                        $quoteItem = $quote->items->where('request_item_id', $requestItem->id)->first();
                    @endphp
                    @if($quoteItem)
                    <tr>
                        <td>{{ $quote->supplier->name ?? 'N/A' }}</td>
                        <td>{{ number_format($quoteItem->unit_price, 2) }}</td>
                        <td>{{ $quoteItem->quantity }}</td>
                        <td>{{ number_format($quoteItem->total_price, 2) }}</td>
                        <td>{{ $quoteItem->delivery_time ?? 'N/A' }}</td>
                        <td>{{ $quoteItem->notes ?? '-' }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
    @endif

    <div class="summary">
        <strong>Summary Statistics:</strong><br>
        Lowest Quote: {{ number_format($quotes->min('total_amount'), 2) }}<br>
        Highest Quote: {{ number_format($quotes->max('total_amount'), 2) }}<br>
        Average Quote: {{ number_format($quotes->avg('total_amount'), 2) }}<br>
        Price Variance: {{ number_format($quotes->max('total_amount') - $quotes->min('total_amount'), 2) }}
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }} | DPanel Procurement System</p>
        <p>Note: Highlighted row indicates the lowest quote</p>
    </div>
</body>
</html>
