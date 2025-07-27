@extends('dashboards.admin')

@section('content')
<h2 class="text-2xl font-bold mb-6">
    {{ isset($user) ? 'Edit User' : 'Register New User' }}
</h2>

@if (session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form 
    action="{{ isset($user) ? route('admin.user_registration.update', $user->id) : route('admin.user_registration.store') }}" 
    method="POST" 
    class="space-y-4 bg-white p-6 rounded shadow w-full max-w-lg"
>
    @csrf
    @if(isset($user))
        @method('PUT')
    @endif

    <div>
        <label for="first_name" class="block text-sm font-medium">First Name</label>
        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required class="mt-1 block w-full px-4 py-2 border rounded">
    </div>

    <div>
        <label for="last_name" class="block text-sm font-medium">Last Name</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required class="mt-1 block w-full px-4 py-2 border rounded">
    </div>

    <div>
        <label for="nic" class="block text-sm font-medium">NIC</label>
        <input type="text" id="nic" name="nic" value="{{ old('nic', $user->nic ?? '') }}" required class="mt-1 block w-full px-4 py-2 border rounded">
    </div>

    @if(!isset($user))
    <div>
        <label for="password" class="block text-sm font-medium">Password</label>
        <input type="password" id="password" name="password" required class="mt-1 block w-full px-4 py-2 border rounded">
    </div>
    @endif

    <div>
        <label for="mobile_number" class="block text-sm font-medium">Mobile Number</label>
        <input type="text" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number ?? '') }}" required class="mt-1 block w-full px-4 py-2 border rounded">
    </div>

    <div>
        <label for="address" class="block text-sm font-medium">Address</label>
        <textarea id="address" name="address" required class="mt-1 block w-full px-4 py-2 border rounded">{{ old('address', $user->address ?? '') }}</textarea>
    </div>

    <div>
        <label for="user_type_id" class="block text-sm font-medium">User Type</label>
        <select id="user_type_id" name="user_type_id" required class="mt-1 block w-full px-4 py-2 border rounded">
            <option value="">-- Select User Type --</option>
            @foreach($userTypes as $type)
                <option value="{{ $type->id }}" 
                    {{ old('user_type_id', $user->user_type_id ?? '') == $type->id ? 'selected' : '' }}>
                    {{ $type->user_type }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="image_url" class="block text-sm font-medium">Image URL</label>
        <input type="text" id="image_url" name="image_url" value="{{ old('image_url', $user->image_url ?? '') }}" class="mt-1 block w-full px-4 py-2 border rounded">
    </div>

    <div>
        <label for="company_name" class="block text-sm font-medium">Company Name</label>
        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name ?? '') }}" class="mt-1 block w-full px-4 py-2 border rounded">
    </div>

    <div>
        <label for="reg_number" class="block text-sm font-medium">Registration Number</label>
        <input type="text" id="reg_number" name="reg_number" value="{{ old('reg_number', $user->reg_number ?? '') }}" class="mt-1 block w-full px-4 py-2 border rounded">
    </div>

    <div class="flex justify-between">
        <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded hover:bg-teal-800">
            {{ isset($user) ? 'Update User' : 'Register User' }}
        </button>

        <a href="{{ route('admin.user_registration') }}" class="text-sm text-gray-600 hover:underline">Back</a>
    </div>
</form>
@endsection
