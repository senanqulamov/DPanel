<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit Trail Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #3b82f6;
            font-size: 16px;
        }
        .filters {
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #3b82f6;
            color: white;
            font-size: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .action-create {
            color: #10b981;
            font-weight: bold;
        }
        .action-update {
            color: #f59e0b;
            font-weight: bold;
        }
        .action-delete {
            color: #ef4444;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>AUDIT TRAIL REPORT</h1>
    </div>

    <div class="filters">
        <strong>Applied Filters:</strong>
        @if($filters['start_date'] ?? null)
            From: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') }} |
        @endif
        @if($filters['end_date'] ?? null)
            To: {{ \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') }} |
        @endif
        @if($filters['action'] ?? null)
            Action: {{ ucfirst($filters['action']) }}
        @endif
        @if(!($filters['start_date'] ?? null) && !($filters['end_date'] ?? null) && !($filters['action'] ?? null))
            No filters applied
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>User</th>
                <th>Action</th>
                <th>Model</th>
                <th>Details</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td>{{ $log->user->name ?? 'System' }}</td>
                <td class="action-{{ strtolower($log->action) }}">{{ ucfirst($log->action) }}</td>
                <td>{{ class_basename($log->model_type) }}</td>
                <td>{{ Str::limit($log->description ?? 'N/A', 50) }}</td>
                <td>{{ $log->ip_address ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">
                    No audit records found for the selected criteria.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }} | DPanel Procurement System</p>
        <p>Total Records: {{ $logs->count() }} (Maximum 1000 records shown)</p>
    </div>
</body>
</html>
