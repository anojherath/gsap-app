@extends('dashboards.seed_provider')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Add New Seed Order</h1>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('seed_orders.store') }}" class="bg-white p-6 rounded shadow-md w-full max-w-xl">
        @csrf

        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">Select Farmer</label>
            <select name="user_id" id="user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Choose a Farmer --</option>
                @foreach($farmers as $farmer)
                    <option value="{{ $farmer->id }}">{{ $farmer->first_name }} {{ $farmer->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="paddy_id" class="block text-sm font-medium text-gray-700">Paddy Type</label>
            <select name="paddy_id" class="w-full mt-1 border p-2 rounded" required>
                <option value="">-- Select Paddy Type --</option>
                @foreach($paddyTypes as $paddy)
                    <option value="{{ $paddy->id }}">{{ $paddy->type }}</option>
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

        <a href="{{ route('seed_orders.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
            Back
        </a>

    </form>
@endsection
