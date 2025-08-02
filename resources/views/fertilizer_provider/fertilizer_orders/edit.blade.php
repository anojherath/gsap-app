@extends('dashboards.fertilizer_provider')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Fertilizer Order</h1>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit Form --}}
    <form method="POST" action="{{ route('fertilizer_orders.update', $order->id) }}" class="bg-white p-6 rounded shadow w-full max-w-xl">
        @csrf
        @method('PUT')

        {{-- Farmer Selection --}}
        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">Select Farmer</label>
            <select name="user_id" id="user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Choose a Farmer --</option>
                @foreach($farmers as $farmer)
                    <option value="{{ $farmer->id }}" {{ $order->user_id == $farmer->id ? 'selected' : '' }}>
                        {{ $farmer->first_name }} {{ $farmer->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Fertilizer Type --}}
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Fertilizer Type</label>
            <input
                type="text"
                name="type"
                id="type"
                value="{{ $order->type }}"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2"
            />
        </div>

        {{-- Quantity --}}
        <div class="mb-4">
            <label for="qty" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input
                type="number"
                name="qty"
                id="qty"
                min="1"
                required
                value="{{ $order->qty }}"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2"
            />
        </div>

        {{-- Submit & Back --}}
        <div class="flex justify-between items-center">
            <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded hover:bg-teal-800">
                Update Order
            </button>

            <a href="{{ route('fertilizer_orders.index') }}" class="text-sm text-gray-600 hover:underline">
                Back
            </a>
        </div>
    </form>
@endsection
