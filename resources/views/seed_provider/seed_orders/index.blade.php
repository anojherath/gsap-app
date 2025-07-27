@extends('dashboards.seed_provider')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Seed Orders</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
        <form method="GET" action="{{ route('seed_orders.index') }}" class="flex-grow flex space-x-2 max-w-xl">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by farmer name, paddy type, or quantity..."
                class="border p-2 rounded w-full"
            />
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded whitespace-nowrap">Search</button>
        </form>

        <div class="flex space-x-2">
            <a href="{{ route('seed_orders.rejected') }}" class="bg-red-600 text-white px-4 py-2 rounded whitespace-nowrap">
                Rejected Orders
            </a>
            <a href="{{ route('seed_orders.create') }}" class="bg-teal-700 text-white px-4 py-2 rounded whitespace-nowrap">
                Add Order
            </a>
        </div>
    </div>

    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-teal-100">
            <tr>
                <th class="border p-2">Farmer</th>
                <th class="border p-2">Paddy Type</th>
                <th class="border p-2">Quantity</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($seedOrders as $order)
                <tr>
                    <td class="border p-2">{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                    <td class="border p-2">{{ $order->paddy->type }}</td>
                    <td class="border p-2">{{ $order->qty }}</td>
                    <td class="border p-2">
                        @if($order->farmer_confirmed)
                            <span class="text-green-600 font-semibold">Confirmed</span>
                        @else
                            <span class="text-yellow-600">Pending</span>
                        @endif
                    </td>
                    <td class="border p-2">{{ $order->creation_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border p-4 text-center text-gray-500">No seed orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $seedOrders->appends(['search' => request('search')])->links() }}
    </div>
@endsection
