@extends('dashboards.seed_provider')

@section('content')
    <h1 class="text-xl font-bold mb-4">Seed Orders</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search & Action Buttons -->
    <div class="flex justify-between items-center mb-4">
        <form method="GET" action="{{ route('seed_orders.index') }}" class="flex items-center space-x-2 w-full max-w-xl">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by farmer name, paddy type, or quantity..."
                class="border rounded px-3 py-2 w-full"
            />
            <button
                type="submit"
                class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800 whitespace-nowrap"
            >
                Search
            </button>
        </form>

        <div class="flex space-x-2 ml-4">
            <a
                href="{{ route('seed_orders.rejected') }}"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 whitespace-nowrap"
            >
                Rejected Orders
            </a>
            <a
                href="{{ route('seed_orders.create') }}"
                class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800 whitespace-nowrap"
            >
                Add Order
            </a>
        </div>
    </div>

    @if($seedOrders->count())
        <table class="w-full table-auto border rounded-lg overflow-hidden shadow">
            <thead>
                <tr class="bg-gray-300">
                    <th class="border px-4 py-2">Farmer</th>
                    <th class="border px-4 py-2">Paddy Type</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seedOrders as $order)
                    <tr>
                        <td class="border px-4 py-2">{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                        <td class="border px-4 py-2">{{ $order->paddy->type }}</td>
                        <td class="border px-4 py-2">{{ $order->qty }}</td>
                        <td class="border px-4 py-2">{{ $order->creation_date }}</td>
                        <td class="border px-4 py-2">
                            @if($order->farmer_confirmed)
                                <span class="text-green-600 font-semibold">Confirmed</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $seedOrders->appends(['search' => request('search')])->links() }}
        </div>
    @else
        <p>No seed orders found.</p>
    @endif
@endsection
