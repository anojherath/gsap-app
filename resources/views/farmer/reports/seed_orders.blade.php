@extends('dashboards.farmer')

@section('title', 'Seed Orders Report')

@section('content')
    <h2 class="text-2xl font-semibold mb-2">Seed Orders Report</h2>

    <!-- Back Link -->
    <a href="{{ route('farmer.reports.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
        Back
    </a>

    <!-- Search & Export -->
    <div class="flex justify-between items-center mb-4">
        <!-- Search Form -->
        <form method="GET" action="{{ route('farmer.reports.seed_orders') }}" class="flex space-x-2">
            <input type="text" name="search" placeholder="Search by Seed Provider, Company, Paddy Type, or Quantity..."
                   value="{{ request('search') }}"
                   class="px-4 py-2 border rounded w-96">
            <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Search
            </button>
        </form>

        <!-- Export Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('farmer.reports.seed_orders.excel', request()->only('search')) }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export Excel
            </a>
            <a href="{{ route('farmer.reports.seed_orders.pdf', request()->only('search')) }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export PDF
            </a>
        </div>
    </div>

    <!-- Seed Orders Table -->
    @if($seedOrders->count())
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="w-full text-left border">
                <thead class="bg-gray-300">
                    <tr>
                        <th class="px-4 py-2 border text-center">Seed Provider</th>
                        <th class="px-4 py-2 border text-center">Company Name</th> <!-- Added -->
                        <th class="px-4 py-2 border text-center">Paddy Type</th>
                        <th class="px-4 py-2 border text-center">Quantity</th>
                        <th class="px-4 py-2 border text-center">Date</th>
                        <th class="px-4 py-2 border text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($seedOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <!-- Seed Provider Name -->
                            <td class="px-4 py-2 border text-center">
                                {{ $order->seedProvider ? $order->seedProvider->first_name . ' ' . $order->seedProvider->last_name : '-' }}
                            </td>

                            <!-- Company Name -->
                            <td class="px-4 py-2 border text-center">
                                {{ $order->seedProvider->company_name ?? 'N/A' }}
                            </td>

                            <td class="px-4 py-2 border text-center">{{ $order->paddy->type ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border text-center">{{ $order->qty }}</td>
                            <td class="px-4 py-2 border text-center">{{ $order->creation_date->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-2 border text-center">
                                @if (!isset($order->farmer_confirmed) || $order->farmer_confirmed === null)
                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                @elseif ($order->farmer_confirmed)
                                    <span class="text-green-600 font-semibold">Confirmed</span>
                                @else
                                    <span class="text-red-600 font-semibold">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                                No seed orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $seedOrders->withQueryString()->links() }}
        </div>
    @else
        <p>No seed orders found.</p>
    @endif
@endsection
