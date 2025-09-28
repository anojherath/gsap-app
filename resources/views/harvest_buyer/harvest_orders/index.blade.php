@extends('dashboards.harvest_buyer')

@section('title', 'Harvest Orders')

@section('content')
<h2 class="text-xl font-bold mb-4">Harvest Orders</h2>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

<!-- Search Form -->
<form method="GET" action="{{ route('harvest_buyer.orders.index') }}" class="flex items-center mb-4 space-x-2">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search by Farmer, Paddy, Fertilizer, Quantity, or Field..."
        class="border rounded px-3 py-2 w-1/3"
    />
    <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
        Search
    </button>
</form>

@if($orders->count())
    <table class="w-full table-auto border rounded-lg overflow-hidden shadow">
        <thead>
            <tr class="bg-gray-300">
                <th class="border px-4 py-2">Farmer</th>
                <th class="border px-4 py-2">Paddy Type</th>
                <th class="border px-4 py-2">Fertilizer Type</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Field</th>
                <th class="border px-4 py-2">Date</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->user->first_name ?? 'N/A' }} {{ $order->user->last_name ?? '' }}</td>
                    <td class="border px-4 py-2">{{ $order->paddy->type ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $order->fertilizerOrder->type ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $order->qty }}</td>
                    <td class="border px-4 py-2">{{ $order->field->name ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i') }}</td>
                    <td class="border px-4 py-2">
                        @php $status = strtolower($order->status); @endphp
                        @if($status === 'pending' || is_null($status))
                            <span class="text-yellow-600 font-semibold">Pending</span>
                        @elseif($status === 'confirmed' || $status === 'accepted')
                            <span class="text-green-600 font-semibold">Confirmed</span>
                        @elseif($status === 'rejected')
                            <span class="text-red-600 font-semibold">Rejected</span>
                        @else
                            <span>{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2 whitespace-nowrap">
                        @if($status === 'pending')
                            <form method="POST" action="{{ route('buyer.harvest_orders.accept', $order->id) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    Accept
                                </button>
                            </form>

                            <form method="POST" action="{{ route('buyer.harvest_orders.reject', $order->id) }}" class="inline-block ml-2">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Reject
                                </button>
                            </form>
                        @else
                            <span class="text-gray-500 italic">No actions</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->appends(['search' => request('search')])->links() }}
    </div>
@else
    <p class="text-gray-500">No orders found.</p>
@endif
@endsection
