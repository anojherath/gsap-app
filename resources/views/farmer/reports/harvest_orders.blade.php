@extends('dashboards.farmer')

@section('title', 'Harvest Order Report')

@section('content')
    <h2 class="text-2xl font-semibold mb-2">Harvest Orders Report</h2>

    <!-- Back Link -->
    <a href="{{ route('farmer.reports.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
        Back
    </a>

    <!-- Search & Export -->
    <div class="flex justify-between items-center mb-4">
        <!-- Search Form -->
        <form method="GET" action="{{ route('farmer.reports.harvest_orders') }}" class="flex space-x-2">
            <input type="text" name="search" placeholder="Search by Buyer Name, Company, Paddy Type, or Quantity..."
                   value="{{ request('search') }}"
                   class="px-4 py-2 border rounded w-96">
            <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Search
            </button>
        </form>

        <!-- Export Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('farmer.reports.harvest_orders.excel', request()->only('search')) }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export Excel
            </a>
            <a href="{{ route('farmer.reports.harvest_orders.pdf', request()->only('search')) }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export PDF
            </a>
        </div>
    </div>

    <!-- Harvest Order Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-left border">
            <thead class="bg-gray-300">
                <tr>
                    <th class="px-4 py-2 border text-center">Harvest Buyer</th>
                    <th class="px-4 py-2 border text-center">Company Name</th> <!-- Added -->
                    <th class="px-4 py-2 border text-center">Paddy Type</th>
                    <th class="px-4 py-2 border text-center">Quantity</th>
                    <th class="px-4 py-2 border text-center">Field Name</th>
                    <th class="px-4 py-2 border text-center">Date</th>
                    <th class="px-4 py-2 border text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <!-- Harvest Buyer -->
                        <td class="px-4 py-2 border text-center">
                            {{ $order->buyer ? $order->buyer->first_name . ' ' . $order->buyer->last_name : '-' }}
                        </td>

                        <!-- Company Name -->
                        <td class="px-4 py-2 border text-center">
                            {{ $order->buyer->company_name ?? '-' }}
                        </td>

                        <!-- Paddy Type -->
                        <td class="px-4 py-2 border text-center">
                            {{ $order->paddy->type ?? $order->paddy->name ?? '-' }}
                        </td>

                        <td class="px-4 py-2 border text-center">{{ $order->qty }}</td>
                        <td class="px-4 py-2 border text-center">{{ $order->field->name ?? '-' }}</td>
                        <td class="px-4 py-2 border text-center">{{ $order->creation_date }}</td>

                        <td class="px-4 py-2 border text-center">
                            @php $status = strtolower($order->status); @endphp
                            @if($status === 'pending' || is_null($status))
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @elseif($status === 'confirmed')
                                <span class="text-green-600 font-semibold">Confirmed</span>
                            @elseif($status === 'rejected')
                                <span class="text-red-600 font-semibold">Rejected</span>
                            @else
                                <span>{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                            No harvest orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection
