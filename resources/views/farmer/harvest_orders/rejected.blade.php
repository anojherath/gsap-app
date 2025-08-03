@extends('dashboards.farmer')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Rejected Harvest Orders</h2>

    <a href="{{ route('farmer.harvest_orders.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
        Back to Harvest Orders
    </a>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-300">
            <tr>
                <th class="px-3 py-2">Harvest Buyer</th>
                <th class="px-3 py-2">Paddy Type</th>
                <th class="px-3 py-2">Quantity</th>
                <th class="px-3 py-2">Field</th>
                <th class="px-3 py-2">Date</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr class="border-t text-center">
                    <td class="px-3 py-2">{{ $order->buyer->first_name ?? '-' }} {{ $order->buyer->last_name ?? '' }}</td>
                    <td class="px-3 py-2">{{ $order->paddy->type ?? $order->paddy->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $order->qty }}</td>
                    <td class="px-3 py-2">{{ $order->field->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i') }}</td>
                    <td class="px-3 py-2 text-red-600 font-semibold">{{ $order->status }}</td>
                    <td class="px-3 py-2">
                        <a href="{{ route('farmer.harvest_orders.edit', $order->id) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('farmer.harvest_orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this order?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">No rejected orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
