@extends('dashboards.farmer')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Add New Harvest Order</h1>

    {{-- Global error list (keeps existing behaviour) --}}
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

        {{-- Paddy dropdown (only confirmed paddies should be provided by controller) --}}
        <div class="mb-4">
            <label for="paddy_id" class="block text-sm font-medium text-gray-700">Paddy Type</label>
            <select name="paddy_id" id="paddy_id" class="w-full mt-1 border p-2 rounded" required>
                <option value="">-- Select Paddy Type --</option>
                @if(isset($paddies) && $paddies->count())
                    @foreach($paddies as $paddy)
                        <option value="{{ $paddy->id }}" {{ old('paddy_id') == $paddy->id ? 'selected' : '' }}>
                            {{ $paddy->type ?? $paddy->name }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>No confirmed paddy types available.</option>
                @endif
            </select>
            @error('paddy_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fertilizer dropdown (new) --}}
        <div class="mb-4">
            <label for="fertilizer_id" class="block text-sm font-medium text-gray-700">Fertilizer Type</label>

            <select name="fertilizer_id" id="fertilizer_id" class="w-full mt-1 border p-2 rounded"
                    {{ (empty($fertilizers) || $fertilizers->count() === 0) ? 'disabled' : 'required' }}>
                <option value="">-- Select Fertilizer Type --</option>

                @if(isset($fertilizers) && $fertilizers->count())
                    @foreach($fertilizers as $fertilizer)
                        <option value="{{ $fertilizer->id }}" {{ old('fertilizer_id') == $fertilizer->id ? 'selected' : '' }}>
                            {{-- adapt display field depending on your fertilizer model (type or name) --}}
                            {{ $fertilizer->type ?? $fertilizer->name ?? 'Fertilizer #' . $fertilizer->id }}
                        </option>
                    @endforeach
                @endif
            </select>

            @if(!isset($fertilizers) || $fertilizers->count() === 0)
                <p class="text-sm text-gray-600 mt-1">
                    No confirmed fertilizer types available. Please confirm fertilizer orders first.
                    <a href="{{ route('farmer.fertilizer_orders.index') }}" class="text-teal-700 hover:underline">Check Fertilizer Orders</a>
                </p>
            @endif

            @error('fertilizer_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Field name --}}
        <div class="mb-4">
            <label for="field_name" class="block text-sm font-medium text-gray-700">Field Name</label>
            <input type="text" name="field_name" id="field_name"
                   class="w-full mt-1 border p-2 rounded"
                   value="{{ old('field_name') }}"
                   required />
            @error('field_name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Harvest Buyer --}}
        <div class="mb-4">
            <label for="buyer_id" class="block text-sm font-medium text-gray-700">Harvest Buyer</label>
            <select name="buyer_id" id="buyer_id" class="w-full mt-1 border p-2 rounded" required>
                <option value="">-- Select Harvest Buyer --</option>
                @if(isset($harvestBuyers) && $harvestBuyers->count())
                    @foreach($harvestBuyers as $buyer)
                        <option value="{{ $buyer->id }}" {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>
                            {{ $buyer->first_name }} {{ $buyer->last_name }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>No harvest buyers found.</option>
                @endif
            </select>
            @error('buyer_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Quantity --}}
        <div class="mb-4">
            <label for="qty" class="block text-sm font-medium text-gray-700">Quantity (Kg)</label>
            <input type="number" name="qty" id="qty"
                   class="w-full mt-1 border p-2 rounded"
                   value="{{ old('qty') }}"
                   required min="1" />
            @error('qty')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded">Submit Order</button>
        </div>

        <a href="{{ route('farmer.harvest_orders.index') }}" class="text-sm text-gray-600 hover:underline mb-4 inline-block">
            Back
        </a>
    </form>
@endsection
