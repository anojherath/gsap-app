<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Report - Harvest Buyer’s View</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: center; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        h2 { 
            text-align: center; 
            margin-top: 20px; 
        }
    </style>
</head>
<body>
    <h2>Customer Report - Harvest Buyer’s View</h2>

    <table>
        <thead>
            <tr>
                <th>Harvest Type</th>
                <th>Harvest Date</th>
                <th>Origin</th>
                <th>Seed Provider Date</th>
                <th>Fertilizer Type</th>
                <th>Fertilizer Applied Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->harvest_type ?? '-' }}</td>
                    <td>{{ $row->harvest_date ?? '-' }}</td>
                    <td>{{ $row->origin ?? '-' }}</td>
                    <td>{{ $row->seed_provider_date ?? '-' }}</td>
                    <td>{{ $row->fertilizer_type ?? '-' }}</td>
                    <td>{{ $row->fertilizer_applied_date ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
