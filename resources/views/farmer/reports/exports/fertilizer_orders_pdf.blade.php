<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fertilizer Orders Report - Farmer's View</title>
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
    <h2>Fertilizer Orders Report - Farmer's View</h2>

    <table>
        <thead>
            <tr>
                <th>Fertilizer Provider</th>
                <th>Company Name</th> <!-- Added column -->
                <th>Fertilizer Type</th>
                <th>Quantity</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fertilizerOrders as $order)
                <tr>
                    <!-- Provider Name -->
                    <td>{{ $order->fertilizerProvider ? $order->fertilizerProvider->first_name . ' ' . $order->fertilizerProvider->last_name : '-' }}</td>
                    <!-- Company Name -->
                    <td>{{ $order->fertilizerProvider->company_name ?? 'N/A' }}</td>
                    <td>{{ $order->type }}</td>
                    <td>{{ $order->qty }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i:s') }}</td>
                    <td>
                        @if(is_null($order->farmer_confirmed))
                            <span class="status-pending">Pending</span>
                        @elseif($order->farmer_confirmed)
                            <span class="status-confirmed">Confirmed</span>
                        @else
                            <span class="status-rejected">Rejected</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No fertilizer orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
