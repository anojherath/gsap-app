@extends('dashboards.admin')

@section('content')
    <h2 class="text-2xl font-semibold mb-6">Reports</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.reports.users') }}" 
           class="p-6 bg-white shadow rounded-lg hover:bg-gray-100 transition text-center">
            <h3 class="text-lg font-semibold">User Report</h3>
            <p class="text-gray-500 text-sm mt-2">View all registered users</p>
        </a>
        <!-- You can add more report links here -->
    </div>
@endsection
