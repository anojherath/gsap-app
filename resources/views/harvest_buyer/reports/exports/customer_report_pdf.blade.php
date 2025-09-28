<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmed Harvest Orders Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Confirmed Harvest Orders Report</h2>

    <table>
        <thead>
            <tr>
                <th>Harvest Type</th>
                <th>Field</th>
                <th>Fertilizer Type</th>
                <th>Harvest Provided Date</th>
                <th>Seed Provided Date</th>
                <th>Fertilizer Provided Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($harvests as $order)
                <tr>
                    <td>{{ $order->paddy->type ?? '-' }}</td>
                    <td>{{ $order->field->name ?? '-' }}</td>
                    <td>{{ $order->fertilizerOrder->type ?? '-' }}</td>
                    <td>
                        {{ $order->creation_date ? \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i:s') : '-' }}
                    </td>
                    <td>
                        {{ $order->latest_seed_date ? \Carbon\Carbon::parse($order->latest_seed_date)->format('Y-m-d H:i:s') : '-' }}
                    </td>
                    <td>
                        {{ $order->latest_fertilizer_date ? \Carbon\Carbon::parse($order->latest_fertilizer_date)->format('Y-m-d H:i:s') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No confirmed harvest orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
