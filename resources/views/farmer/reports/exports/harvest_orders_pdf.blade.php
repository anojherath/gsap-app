<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Harvest Orders Report - Farmer's View</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-top: 20px; }
        .status-pending { color: #d97706; font-weight: bold; } /* yellow */
        .status-confirmed { color: #16a34a; font-weight: bold; } /* green */
        .status-rejected { color: #dc2626; font-weight: bold; } /* red */
    </style>
</head>
<body>
    <h2>Harvest Orders Report - Farmer's View</h2>

    <table>
        <thead>
            <tr>
                <th>Harvest Buyer</th>
                <th>Company Name</th> <!-- Added column -->
                <th>Paddy Type</th>
                <th>Quantity</th>
                <th>Field Name</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <!-- Harvest Buyer -->
                    <td>{{ $order->buyer ? $order->buyer->first_name . ' ' . $order->buyer->last_name : '-' }}</td>

                    <!-- Company Name -->
                    <td>{{ $order->buyer->company_name ?? '-' }}</td>

                    <!-- Paddy Type -->
                    <td>{{ $order->paddy->type ?? $order->paddy->name ?? '-' }}</td>

                    <td>{{ $order->qty }}</td>
                    <td>{{ $order->field->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i:s') }}</td>

                    <!-- Status -->
                    <td>
                        @php $status = strtolower($order->status); @endphp
                        @if($status === 'pending' || is_null($status))
                            <span class="status-pending">Pending</span>
                        @elseif($status === 'confirmed')
                            <span class="status-confirmed">Confirmed</span>
                        @elseif($status === 'rejected')
                            <span class="status-rejected">Rejected</span>
                        @else
                            {{ ucfirst($order->status) }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No harvest orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
