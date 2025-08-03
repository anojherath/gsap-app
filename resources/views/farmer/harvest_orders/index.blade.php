@extends('dashboards.farmer')

@section('content')
    <h2 class="text-xl font-bold mb-4">Harvest Orders</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search & Action Buttons -->
    <div class="flex justify-between items-center mb-4">
        <form method="GET" action="{{ route('farmer.harvest_orders.index') }}" class="flex items-center space-x-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search..."
                class="border rounded px-3 py-2 w-1/3"
            />
            <button
                type="submit"
                class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800"
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
                class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800"
            >
                Add Order
            </a>
        </div>
    </div>

    @if($orders->count())
        <table class="w-full table-auto border rounded-lg overflow-hidden shadow">
            <thead>
                <tr class="bg-gray-300">
                    <th class="border px-4 py-2">Harvest Buyer</th>
                    <th class="border px-4 py-2">Paddy Type</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Field Name</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td class="border px-4 py-2">
                            {{ $order->buyer ? $order->buyer->first_name . ' ' . $order->buyer->last_name : '-' }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ $order->paddy->type ?? $order->paddy->name ?? '-' }}
                        </td>
                        <td class="border px-4 py-2">{{ $order->qty }}</td>
                        <td class="border px-4 py-2">{{ $order->field->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $order->creation_date }}</td>
                        <td class="border px-4 py-2">
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
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    @else
        <p>No harvest orders found.</p>
    @endif
@endsection
