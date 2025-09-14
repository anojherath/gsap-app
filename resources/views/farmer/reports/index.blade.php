@extends('dashboards.farmer')

@section('content')
    <h2 class="text-2xl font-semibold mb-6">Reports</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Harvest Order Report -->
        <a href="{{ route('farmer.reports.harvest_orders') }}" 
           class="p-6 bg-white shadow rounded-lg hover:bg-gray-100 transition text-center">
            <h3 class="text-lg font-semibold">Harvest Order Report</h3>
            <p class="text-gray-500 text-sm mt-2">View your harvest order details</p>
        </a>

        <!-- Seed Order Report -->
        <a href="{{ route('farmer.reports.seed_orders') }}" 
           class="p-6 bg-white shadow rounded-lg hover:bg-gray-100 transition text-center">
            <h3 class="text-lg font-semibold">Seed Order Report</h3>
            <p class="text-gray-500 text-sm mt-2">View your seed order details</p>
        </a>

        <!-- Fertilizer Order Report -->
        <a href="{{ route('farmer.reports.fertilizer_orders') }}" 
           class="p-6 bg-white shadow rounded-lg hover:bg-gray-100 transition text-center">
            <h3 class="text-lg font-semibold">Fertilizer Order Report</h3>
            <p class="text-gray-500 text-sm mt-2">View your fertilizer order details</p>
        </a>

    </div>
@endsection
