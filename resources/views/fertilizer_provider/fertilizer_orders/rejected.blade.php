@extends('dashboards.fertilizer_provider')

@section('content')
<h1 class="text-xl font-bold mb-4">Rejected Fertilizer Orders</h1>

<a href="{{ route('fertilizer_orders.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
    Back
</a>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if($rejectedOrders->count())
    <table class="w-full table-auto border rounded-lg overflow-hidden shadow">
        <thead>
            <tr class="bg-gray-300">
                <th class="border px-4 py-2">Farmer</th>
                <th class="border px-4 py-2">Fertilizer Type</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Date</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rejectedOrders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                    <td class="border px-4 py-2">{{ $order->type }}</td>
                    <td class="border px-4 py-2">{{ $order->qty }}</td>
                    <td class="border px-4 py-2">
                        <span class="text-red-600 font-semibold">Rejected</span>
                    </td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i') }}</td>
                    <td class="border px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('fertilizer_orders.edit', $order->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>

                        <form action="{{ route('fertilizer_orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $rejectedOrders->links() }}
    </div>
@else
    <p class="text-gray-500">No rejected orders found.</p>
@endif
@endsection
