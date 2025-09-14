@extends('dashboards.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <form action="{{ route('admin.user_registration') }}" method="GET" class="flex space-x-2">
        <input type="text" name="search" placeholder="Search by Name, Company, NIC, Mobile Number or Type..." value="{{ $search ?? '' }}"
               class="px-4 py-2 border rounded w-64">
        <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
            Search
        </button>
    </form>

    <a href="{{ route('admin.user_registration.create') }}" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
        Add User
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto bg-white shadow rounded-lg mt-4">
    <table class="w-full text-left border">
        <thead class="bg-gray-300">
            <tr>
                <th class="px-4 py-2 border text-center">Name</th>
                <th class="px-4 py-2 border text-center">Company Name</th> <!-- New Column -->
                <th class="px-4 py-2 border text-center">NIC</th>
                <th class="px-4 py-2 border text-center">Mobile</th>
                <th class="px-4 py-2 border text-center">Type</th>
                <th class="px-4 py-2 border text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border text-center">{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td class="px-4 py-2 border text-center">{{ $user->company_name ?? '-' }}</td> <!-- Company Name -->
                    <td class="px-4 py-2 border text-center">{{ $user->nic }}</td>
                    <td class="px-4 py-2 border text-center">{{ $user->mobile_number }}</td>
                    <td class="px-4 py-2 border text-center">{{ $user->userType->user_type ?? '' }}</td>
                    <td class="px-4 py-2 border text-center space-x-2">
                        <a href="{{ route('admin.user_registration.edit', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.user_registration.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-gray-600 text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="p-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
