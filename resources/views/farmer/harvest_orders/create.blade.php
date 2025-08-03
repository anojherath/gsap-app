@extends('dashboards.farmer')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Add New Harvest Order</h1>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('farmer.harvest_orders.store') }}" class="bg-white p-6 rounded shadow-md w-full max-w-xl">
        @csrf

        <div class="mb-4">
            <label for="paddy_id" class="block text-sm font-medium text-gray-700">Paddy Type</label>
            <select name="paddy_id" class="w-full mt-1 border p-2 rounded" required>
                <option value="">-- Select Paddy Type --</option>
                @foreach($paddies as $paddy)
                    <option value="{{ $paddy->id }}">{{ $paddy->type ?? $paddy->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="field_name" class="block text-sm font-medium text-gray-700">Field Name</label>
            <input type="text" name="field_name" id="field_name" class="w-full mt-1 border p-2 rounded" required />
        </div>

        <div class="mb-4">
            <label for="buyer_id" class="block text-sm font-medium text-gray-700">Harvest Buyer</label>
            <select name="buyer_id" class="w-full mt-1 border p-2 rounded" required>
                <option value="">-- Select Harvest Buyer --</option>
                @foreach($harvestBuyers as $buyer)
                    <option value="{{ $buyer->id }}">{{ $buyer->first_name }} {{ $buyer->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="qty" class="block text-sm font-medium text-gray-700">Quantity (Kg)</label>
            <input type="number" name="qty" class="w-full mt-1 border p-2 rounded" required min="1" />
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded">Submit Order</button>
        </div>

        <a href="{{ route('farmer.harvest_orders.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
            Back
        </a>

    </form>
@endsection
