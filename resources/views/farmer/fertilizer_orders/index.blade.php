@extends('dashboards.farmer')

@section('content')
    <h2 class="text-xl font-bold mb-4">Received Fertilizer Orders</h2>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('farmer.fertilizer_orders.index') }}" class="flex items-center mb-4 space-x-2">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by Provider Name, Company Name, Fertilizer Type or Quantity..."
            class="border rounded px-3 py-2 w-1/3"
        />
        <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
            Search
        </button>
    </form>

    @if($fertilizerOrders->count())
        <table class="w-full table-auto border rounded-lg overflow-hidden shadow">
            <thead>
                <tr class="bg-gray-300">
                    <th class="border px-4 py-2">Fertilizer Provider</th>
                    <th class="border px-4 py-2">Company Name</th>
                    <th class="border px-4 py-2">Fertilizer Type</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fertilizerOrders as $order)
                    <tr>
                        <!-- Provider Name -->
                        <td class="border px-4 py-2">
                            {{ $order->fertilizerProvider->first_name ?? '' }} {{ $order->fertilizerProvider->last_name ?? '' }}
                        </td>

                        <!-- Company Name -->
                        <td class="border px-4 py-2">
                            {{ $order->fertilizerProvider->company_name ?? 'N/A' }}
                        </td>

                        <!-- Fertilizer Type -->
                        <td class="border px-4 py-2">{{ $order->type }}</td>

                        <!-- Quantity -->
                        <td class="border px-4 py-2">{{ $order->qty }}</td>

                        <!-- Date -->
                        <td class="border px-4 py-2">
                            {{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i:s') }}
                        </td>

                        <!-- Status -->
                        <td class="border px-4 py-2">
                            @if (is_null($order->farmer_confirmed))
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @elseif ($order->farmer_confirmed)
                                <span class="text-green-600 font-semibold">Confirmed</span>
                            @else
                                <span class="text-red-600 font-semibold">Rejected</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="border px-4 py-2">
                            @if (is_null($order->farmer_confirmed))
                                <form action="{{ route('farmer.fertilizer_orders.confirm', $order->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        Confirm
                                    </button>
                                </form>

                                <form action="{{ route('farmer.fertilizer_orders.reject', $order->id) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Reject
                                    </button>
                                </form>
                            @else
                                No actions
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $fertilizerOrders->appends(['search' => request('search')])->links() }}
        </div>
    @else
        <p>No fertilizer orders found.</p>
    @endif
@endsection
