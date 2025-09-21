@extends('dashboards.admin')

@section('title', 'Sent Notifications')

@section('content')
<h2 class="text-2xl font-semibold mb-2">Sent Notifications</h2>

<!-- Notification Form -->
<div class="mb-6 bg-white shadow rounded-lg p-6">
    <form method="POST" action="{{ route('admin.push_notification.send') }}" class="space-y-4">
        @csrf
        <div>
            <label for="user_type_id" class="block font-medium">Select User Type:</label>
            <select name="user_type_id" id="user_type_id" class="w-full border px-4 py-2 rounded">
                <option value="">All</option>
                @foreach($userTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->user_type }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="message" class="block font-medium">Message:</label>
            <textarea name="message" id="message" rows="3" class="w-full border px-4 py-2 rounded" required></textarea>
        </div>

        <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">
            Send
        </button>
    </form>
</div>

<!-- Filter / Search by User Type -->
<div class="mb-4 flex items-center space-x-2">
    <form method="GET" action="{{ route('admin.push_notification') }}" class="flex items-center space-x-2">
        <label for="filter_user_type" class="text-sm font-medium">Filter by User Type:</label>
        <select name="user_type_id" id="filter_user_type" class="border px-2 py-1 rounded">
            <option value="">All</option>
            @foreach($userTypes as $type)
                <option value="{{ $type->id }}" {{ request('user_type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->user_type }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-teal-700 text-white px-3 py-1 rounded hover:bg-teal-800">
            Filter
        </button>
    </form>
</div>

<!-- Sent Notifications Table -->
<div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="w-full text-left border">
        <thead class="bg-gray-300">
            <tr>
                <th class="px-4 py-2 border text-center">Date & Time</th>
                <th class="px-4 py-2 border text-center">Message</th>
                <th class="px-4 py-2 border text-center">Recipient Name</th>
                <th class="px-4 py-2 border text-center">User Type</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $note)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border text-center">{{ $note->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $note->message }}</td>
                    <td class="px-4 py-2 border text-center">{{ $note->user->first_name ?? '-' }} {{ $note->user->last_name ?? '' }}</td>
                    <td class="px-4 py-2 border text-center">{{ $note->user->userType->user_type ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">No notifications found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
