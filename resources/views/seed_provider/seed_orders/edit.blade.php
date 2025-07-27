@extends('dashboards.seed_provider')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Seed Order</h1>

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

    <form method="POST" action="{{ route('seed_orders.update', $order->id) }}" class="bg-white p-6 rounded shadow w-full max-w-xl">
        @csrf
        @method('PUT')

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

        <div class="mb-4">
            <label for="paddy_id" class="block text-sm font-medium text-gray-700">Paddy Type</label>
            <select name="paddy_id" id="paddy_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Select Paddy Type --</option>
                @foreach($paddyTypes as $paddy)
                    <option value="{{ $paddy->id }}" {{ $order->paddy_id == $paddy->id ? 'selected' : '' }}>
                        {{ $paddy->type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="qty" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" name="qty" id="qty" min="1" required value="{{ $order->qty }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded hover:bg-teal-800">Resend Order</button>

            <a href="{{ route('seed_orders.rejected') }}" class="text-sm text-gray-600 hover:underline">Back</a>
        </div>
    </form>
@endsection
