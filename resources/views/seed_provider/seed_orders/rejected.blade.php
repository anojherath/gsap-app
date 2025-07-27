@extends('dashboards.seed_provider')

@section('content')
<h1 class="text-2xl font-bold mb-4">Rejected Seed Orders</h1>

<a href="{{ route('seed_orders.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
    Back
</a>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="w-full table-auto border-collapse border border-gray-300">
    <thead class="bg-teal-100">
        <tr>
            <th class="border p-2">Farmer</th>
            <th class="border p-2">Paddy Type</th>
            <th class="border p-2">Quantity</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Date</th>
            <th class="border p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rejectedOrders as $order)
            <tr>
                <td class="border p-2">{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                <td class="border p-2">{{ $order->paddy->type }}</td>
                <td class="border p-2">{{ $order->qty }}</td>
                <td class="border p-2">
                    <span class="text-red-600 font-semibold">Rejected</span>
                </td>
                <td class="border p-2">{{ $order->creation_date }}</td>
                <td class="border p-2">
                    <a href="{{ route('seed_orders.edit', $order->id) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('seed_orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border p-4 text-center text-gray-500">No rejected orders found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $rejectedOrders->links() }}
</div>
@endsection
