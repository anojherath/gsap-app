@extends('dashboards.admin')

@section('title', 'Customer Report')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Customer Report</h2>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('admin.reports') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
            Back
        </a>

        <div class="flex space-x-2">
            <a href="{{ route('admin.reports.customers.export.excel') }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export Excel
            </a>
            <a href="{{ route('admin.reports.customers.export.pdf') }}" 
               class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
                Export PDF
            </a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-left border">
            <thead class="bg-gray-300">
                <tr>
                    <th class="px-4 py-2 border text-center">Harvest Type</th>
                    <th class="px-4 py-2 border text-center">Harvest Date</th>
                    <th class="px-4 py-2 border text-center">Origin</th>
                    <th class="px-4 py-2 border text-center">Seed Provided Date</th>
                    <th class="px-4 py-2 border text-center">Fertilizer Type</th>
                    <th class="px-4 py-2 border text-center">Fertilizer Applied Date</th>
                    <th class="px-4 py-2 border text-center">QR Code</th> {{-- Updated column --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($results as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $row->harvest_type ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            {{ $row->harvest_date ? \Carbon\Carbon::parse($row->harvest_date)->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-2 border">{{ $row->origin ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            {{ $row->seed_provider_date ? \Carbon\Carbon::parse($row->seed_provider_date)->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-2 border">{{ $row->fertilizer_type ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            {{ $row->fertilizer_applied_date ? \Carbon\Carbon::parse($row->fertilizer_applied_date)->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-2 border text-center">
                            @php
                                $detailUrl = route('admin.reports.customer_details', $row->id);
                            @endphp
                            {!! QrCode::size(100)->generate($detailUrl) !!}
                            <div class="mt-1 text-xs text-blue-600 hover:underline">
                                <a href="{{ $detailUrl }}" target="_blank">View Details</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $results->links() }}
        </div>
    </div>
@endsection
