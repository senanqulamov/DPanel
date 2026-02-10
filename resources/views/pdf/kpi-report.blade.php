<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KPI Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
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
        }
        .date-range {
            text-align: center;
            margin-bottom: 20px;
            font-style: italic;
            color: #666;
        }
        .kpi-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .kpi-section h2 {
            background-color: #3b82f6;
            color: white;
            padding: 8px;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e5e7eb;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .metric-value {
            font-weight: bold;
            color: #3b82f6;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KEY PERFORMANCE INDICATORS REPORT</h1>
    </div>

    <div class="date-range">
        @if($startDate && $endDate)
            Period: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        @else
            Period: All Time
        @endif
    </div>

    <div class="kpi-section">
        <h2>Procurement KPIs</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total RFQs</td>
                    <td class="metric-value">{{ $kpiData['procurement']['totalRfqs'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Active RFQs</td>
                    <td class="metric-value">{{ $kpiData['procurement']['activeRfqs'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Completed RFQs</td>
                    <td class="metric-value">{{ $kpiData['procurement']['completedRfqs'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Average Processing Time</td>
                    <td class="metric-value">{{ number_format($kpiData['procurement']['avgProcessingTime'] ?? 0, 2) }} days</td>
                </tr>
                <tr>
                    <td>Average Response Time</td>
                    <td class="metric-value">{{ number_format($kpiData['procurement']['avgResponseTime'] ?? 0, 2) }} hours</td>
                </tr>
                <tr>
                    <td>On-time Completion Rate</td>
                    <td class="metric-value">{{ number_format($kpiData['procurement']['onTimeRate'] ?? 0, 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="kpi-section">
        <h2>Supplier KPIs</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Suppliers</td>
                    <td class="metric-value">{{ $kpiData['supplier']['totalSuppliers'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Active Suppliers</td>
                    <td class="metric-value">{{ $kpiData['supplier']['activeSuppliers'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Public Tender Eligible Suppliers</td>
                    <td class="metric-value">{{ $kpiData['supplier']['publicTenderEligible'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Average Response Time</td>
                    <td class="metric-value">{{ number_format($kpiData['supplier']['avgResponseTime'] ?? 0, 2) }} hours</td>
                </tr>
                <tr>
                    <td>Response Rate</td>
                    <td class="metric-value">{{ number_format($kpiData['supplier']['responseRate'] ?? 0, 2) }}%</td>
                </tr>
                <tr>
                    <td>On-time Delivery Rate</td>
                    <td class="metric-value">{{ number_format($kpiData['supplier']['onTimeDeliveryRate'] ?? 0, 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="kpi-section">
        <h2>Quote KPIs</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Quotes</td>
                    <td class="metric-value">{{ $kpiData['quote']['totalQuotes'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Submitted Quotes</td>
                    <td class="metric-value">{{ $kpiData['quote']['submittedQuotes'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Average Quotes per RFQ</td>
                    <td class="metric-value">{{ number_format($kpiData['quote']['avgQuotesPerRfq'] ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td>Average Quote Value</td>
                    <td class="metric-value">{{ number_format($kpiData['quote']['avgQuoteValue'] ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Quote Value</td>
                    <td class="metric-value">{{ number_format($kpiData['quote']['totalQuoteValue'] ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td>Acceptance Rate</td>
                    <td class="metric-value">{{ number_format($kpiData['quote']['acceptanceRate'] ?? 0, 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="kpi-section">
        <h2>Field Assessment KPIs</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Assessments</td>
                    <td class="metric-value">{{ $kpiData['fieldAssessment']['totalAssessments'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Completed Assessments</td>
                    <td class="metric-value">{{ $kpiData['fieldAssessment']['completedAssessments'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Average Time to Start</td>
                    <td class="metric-value">{{ number_format($kpiData['fieldAssessment']['avgTimeToStart'] ?? 0, 2) }} hours</td>
                </tr>
                <tr>
                    <td>Average Time to Complete</td>
                    <td class="metric-value">{{ number_format($kpiData['fieldAssessment']['avgTimeToComplete'] ?? 0, 2) }} hours</td>
                </tr>
                <tr>
                    <td>Success Rate</td>
                    <td class="metric-value">{{ number_format($kpiData['fieldAssessment']['successRate'] ?? 0, 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }} | DPanel Procurement System</p>
    </div>
</body>
</html>
