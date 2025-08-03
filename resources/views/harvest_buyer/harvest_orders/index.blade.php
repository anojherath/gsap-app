@extends('dashboards.harvest_buyer')

@section('title', 'Harvest Orders')

@section('content')
<h2 class="text-2xl font-semibold mb-4">Harvest Orders</h2>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full border border-gray-200 rounded">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2 border">Farmer</th>
            <th class="px-4 py-2 border">Paddy Type</th>
            <th class="px-4 py-2 border">Quantity</th>
            <th class="px-4 py-2 border">Field</th>
            <th class="px-4 py-2 border">Date</th>
            <th class="px-4 py-2 border">Status</th>
            <th class="px-4 py-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
            <tr>
                <td class="border px-4 py-2">{{ $order->user->first_name ?? 'N/A' }} {{ $order->user->last_name ?? '' }}</td>
                <td class="border px-4 py-2">{{ $order->paddy->type ?? 'N/A' }}</td>
                <td class="border px-4 py-2">{{ $order->qty }}</td>
                <td class="border px-4 py-2">{{ $order->field->name ?? 'N/A' }}</td>
                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i') }}</td>
                <td class="border px-4 py-2">{{ $order->status }}</td>
                <td class="border px-4 py-2 space-x-2">
                    @if($order->status === 'Pending')
                        <form method="POST" action="{{ route('buyer.harvest_orders.accept', $order->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Accept</button>
                        </form>

                        <form method="POST" action="{{ route('buyer.harvest_orders.reject', $order->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Reject</button>
                        </form>
                    @else
                        <span class="text-gray-500 italic">No actions</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center py-4">No orders found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection
