<!DOCTYPE html>
<html>
<head>
    <title>User Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; font-size: 12px; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>User Report</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>NIC</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Type</th>
                <th>Company</th>
                <th>Reg. Number</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->nic }}</td>
                <td>{{ $user->mobile_number }}</td>
                <td>{{ $user->address }}</td>
                <td>{{ $user->userType->user_type ?? '' }}</td>
                <td>{{ $user->company_name ?? '-' }}</td>
                <td>{{ $user->reg_number ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($user->creation_date)->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
