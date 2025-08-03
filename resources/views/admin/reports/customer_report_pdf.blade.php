<!DOCTYPE html>
<html>
<head>
    <title>Customer Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border:1px solid black;
            padding: 5px;
            text-align: left;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>
    <h2>Customer Report</h2>
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
            @foreach($data as $row)
            <tr>
                <td>{{ $row->harvest_type ?? '-' }}</td>
                <td>{{ $row->harvest_date ?? '-' }}</td>
                <td>{{ $row->origin ?? '-' }}</td>
                <td>{{ $row->seed_provider_date ?? '-' }}</td>
                <td>{{ $row->fertilizer_type ?? '-' }}</td>
                <td>{{ $row->fertilizer_applied_date ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
