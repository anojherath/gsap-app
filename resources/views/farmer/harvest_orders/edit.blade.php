@extends('dashboards.farmer')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Harvest Order</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('farmer.harvest_orders.update', $order->id) }}" class="bg-white p-6 rounded shadow w-full max-w-xl">
    @csrf
    @method('PUT')

    {{-- Harvest Buyer --}}
    <div class="mb-4">
        <label for="buyer_id" class="block text-sm font-medium text-gray-700">Select Harvest Buyer</label>
        <select name="buyer_id" id="buyer_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- Choose a Harvest Buyer --</option>
            @foreach($harvestBuyers as $buyer)
                <option value="{{ $buyer->id }}" {{ $order->buyer_id == $buyer->id ? 'selected' : '' }}>
                    {{ $buyer->first_name }} {{ $buyer->last_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Paddy Type --}}
    <div class="mb-4">
        <label for="paddy_id" class="block text-sm font-medium text-gray-700">Paddy Type</label>
        <select name="paddy_id" id="paddy_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- Select Paddy Type --</option>
            @foreach($paddies as $paddy)
                <option value="{{ $paddy->id }}" {{ $order->paddy_id == $paddy->id ? 'selected' : '' }}>
                    {{ $paddy->type }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Field Name --}}
    <div class="mb-4">
        <label for="field_name" class="block text-sm font-medium text-gray-700">Field Name</label>
        <input type="text" name="field_name" id="field_name" required value="{{ $order->field->name ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
    </div>

    {{-- Quantity --}}
    <div class="mb-4">
        <label for="qty" class="block text-sm font-medium text-gray-700">Quantity</label>
        <input type="number" name="qty" id="qty" min="1" required value="{{ $order->qty }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
    </div>

    <div class="flex justify-between">
        <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded hover:bg-teal-800">
            Resubmit Order
        </button>

        <a href="{{ route('farmer.harvest_orders.rejected') }}" class="text-sm text-gray-600 hover:underline">
            Back
        </a>
    </div>
</form>
@endsection
