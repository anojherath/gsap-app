@extends('dashboards.harvest_buyer')

@section('title', 'End Product Details')

@section('content')
    <h2 class="text-2xl font-semibold mb-2">Product Life Cycle Details</h2>

    <!-- Search and Export Buttons -->
    <div class="flex justify-between items-center mb-4">
        <!-- Search Form -->
        <form method="GET" action="{{ route('harvest_buyer.reports.customers') }}" class="flex items-center space-x-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by Paddy Type, Field, Fertilizer..."
                class="border rounded px-3 py-2 w-64"
            />
            <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Search
            </button>
        </form>

        <!-- Export Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('harvest_buyer.reports.customers.export.excel') }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export Excel
            </a>
            <a href="{{ route('harvest_buyer.reports.customers.export.pdf') }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export PDF
            </a>
        </div>
    </div>

    @if($results->count())
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="w-full text-left border">
                <thead class="bg-gray-300">
                    <tr>
                        <th class="px-4 py-2 border text-center">Harvest Type</th>
                        <th class="px-4 py-2 border text-center">Field</th>
                        <th class="px-4 py-2 border text-center">Fertilizer Type</th>
                        <th class="px-4 py-2 border text-center">Harvest Provided Date</th>
                        <th class="px-4 py-2 border text-center">Seed Provided Date</th>
                        <th class="px-4 py-2 border text-center">Fertilizer Provided Date</th>
                        <th class="px-4 py-2 border text-center">QR Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-center">{{ $order->paddy->type ?? '-' }}</td>
                            <td class="px-4 py-2 border text-center">{{ $order->field->name ?? '-' }}</td>
                            <td class="px-4 py-2 border text-center">{{ $order->fertilizerOrder->type ?? '-' }}</td>
                            <td class="px-4 py-2 border text-center">{{ \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 border text-center">
                                {{ $order->latest_seed_date ? \Carbon\Carbon::parse($order->latest_seed_date)->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                {{ $order->latest_fertilizer_date ? \Carbon\Carbon::parse($order->latest_fertilizer_date)->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                @php
                                    $detailUrl = route('harvest_buyer.reports.customer_details', $order->id);

                                    $qrData = json_encode([
                                        'Harvest Type' => $order->paddy->type ?? '-',
                                        'Field' => $order->field->name ?? '-',
                                        'Harvest Date' => $order->creation_date ? \Carbon\Carbon::parse($order->creation_date)->format('Y-m-d H:i') : '-',
                                        'Seed Provided Date' => $order->latest_seed_date ? \Carbon\Carbon::parse($order->latest_seed_date)->format('Y-m-d H:i') : '-',
                                        'Fertilizer Type' => $order->fertilizerOrder->type ?? '-',
                                        'Fertilizer Provided Date' => $order->latest_fertilizer_date ? \Carbon\Carbon::parse($order->latest_fertilizer_date)->format('Y-m-d H:i') : '-',
                                        'Details URL' => $detailUrl
                                    ]);
                                @endphp

                                {!! QrCode::size(120)->generate($qrData) !!}

                                <div class="mt-1 text-xs text-blue-600 hover:underline">
                                    <a href="{{ $detailUrl }}" target="_blank">View Details</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-4">
                {{ $results->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    @else
        <p class="text-gray-500">No confirmed harvest orders found.</p>
    @endif
@endsection
