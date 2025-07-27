@extends('dashboards.farmer')

@section('content')
    <h2 class="text-xl font-bold mb-4">Received Seed Orders</h2>

    @if($seedOrders->count())
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Paddy Type</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Purchased From</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Actions</th> <!-- New column -->
                </tr>
            </thead>
            <tbody>
                @foreach($seedOrders as $order)
                    <tr>
                        <td class="border px-4 py-2">{{ $order->paddy->type ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $order->qty }}</td>
                        <td class="border px-4 py-2">{{ $order->seedProvider->company_name ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $order->creation_date->format('Y-m-d H:i:s') }}</td>
                        <td class="border px-4 py-2">
                            @if (!isset($order->farmer_confirmed) || $order->farmer_confirmed === null)
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @elseif ($order->farmer_confirmed)
                                <span class="text-green-600 font-semibold">Confirmed</span>
                            @else
                                <span class="text-red-600 font-semibold">Rejected</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            @if (!isset($order->farmer_confirmed) || $order->farmer_confirmed === null)  {{-- Show buttons only if pending --}}
                                <form action="{{ route('farmer.seed_orders.confirm', $order->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        Confirm
                                    </button>
                                </form>

                                <form action="{{ route('farmer.seed_orders.reject', $order->id) }}" method="POST" class="inline-block ml-2">
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
            {{ $seedOrders->links() }}
        </div>
    @else
        <p>No seed orders found.</p>
    @endif
@endsection
