<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Report - Admin's View</title>
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
    <h2>User Report - Admin's View</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Company Name</th>
                <th>NIC</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Type</th>
                <th>Reg. Number</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->company_name ?? '-' }}</td>
                    <td>{{ $user->nic }}</td>
                    <td>{{ $user->mobile_number }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->userType->user_type ?? '-' }}</td>
                    <td>{{ $user->reg_number ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($user->creation_date)->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
