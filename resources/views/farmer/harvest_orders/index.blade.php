@extends('dashboards.farmer')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Harvest Orders</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <form method="GET" action="{{ route('farmer.harvest_orders.index') }}" class="flex space-x-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search..."
                class="px-3 py-2 border border-gray-300 rounded-md"
            />
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Search
            </button>
        </form>

        <div class="flex space-x-2">
            <a
                href="{{ route('farmer.harvest_orders.rejected') }}"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
            >
                Rejected Orders
            </a>
            <a
                href="{{ route('farmer.harvest_orders.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            >
                Add Order
            </a>
        </div>
    </div>

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-3 py-2">Harvest Buyer</th>
                <th class="px-3 py-2">Paddy Type</th>
                <th class="px-3 py-2">Quantity</th>
                <th class="px-3 py-2">Field</th>
                <th class="px-3 py-2">Applied Fertilizer</th>
                <th class="px-3 py-2">Date</th>
                <th class="px-3 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr class="border-t text-center">
                    <td class="px-3 py-2">{{ $order->buyer ? $order->buyer->first_name . ' ' . $order->buyer->last_name : '-' }}</td>
                    <td class="px-3 py-2">{{ $order->paddy->type ?? $order->paddy->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $order->qty }}</td>
                    <td class="px-3 py-2">{{ $order->field->name ?? '-' }}</td>
                    <td class="px-3 py-2">
                        @foreach($order->field->fertilizerOrders ?? [] as $fertilizerOrder)
                            <div>{{ $fertilizerOrder->fertilizer->name ?? '-' }}</div>
                        @endforeach
                    </td>
                    <td class="px-3 py-2">{{ $order->creation_date }}</td>
                    <td class="px-3 py-2">{{ $order->status }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-4">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
