@extends('dashboards.admin')

@section('title', 'User Report')

@section('content')
    <h2 class="text-2xl font-semibold mb-2">User Report</h2>

    <!-- Back Link -->
    <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
        Back
    </a>

    <!-- Search and Export Buttons -->
    <div class="flex justify-between items-center mb-4">
        <form method="GET" class="flex space-x-2">
            <input type="text" name="search" placeholder="Search..." 
                   value="{{ $search ?? '' }}"
                   class="px-4 py-2 border rounded w-64">
            <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Search
            </button>
        </form>

        <div class="flex space-x-2">
            <a href="{{ route('admin.reports.users.export.excel') }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export Excel
            </a>
            <a href="{{ route('admin.reports.users.export.pdf') }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export PDF
            </a>
        </div>
    </div>

    <!-- User Report Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-left border">
            <thead class="bg-gray-300">
                <tr>
                    <th class="px-4 py-2 border text-center">Name</th>
                    <th class="px-4 py-2 border text-center">NIC</th>
                    <th class="px-4 py-2 border text-center">Mobile No.</th>
                    <th class="px-4 py-2 border text-center">Address</th>
                    <th class="px-4 py-2 border text-center">User Type</th>
                    <th class="px-4 py-2 border text-center">Company</th>
                    <th class="px-4 py-2 border text-center">Reg. Number</th>
                    <th class="px-4 py-2 border text-center">Created Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="px-4 py-2 border">{{ $user->nic }}</td>
                        <td class="px-4 py-2 border">{{ $user->mobile_number }}</td>
                        <td class="px-4 py-2 border">{{ $user->address }}</td>
                        <td class="px-4 py-2 border">{{ $user->userType->user_type ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $user->company_name ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $user->reg_number ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            {{ $user->creation_date ? \Carbon\Carbon::parse($user->creation_date)->format('Y-m-d H:i:s') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
