@extends('dashboards.admin')

@section('content')
<h2 class="text-xl font-bold mb-4">Push Notification</h2>

<form method="POST" action="{{ route('admin.push_notification.send') }}" class="space-y-4">
    @csrf
    <div>
        <label for="user_type_id" class="block font-medium">Select User Type:</label>
        <select name="user_type_id" id="user_type_id" class="w-full border px-4 py-2 rounded">
            <option value="">All</option>
            @foreach(\App\Models\UserType::all() as $type)
                <option value="{{ $type->id }}">{{ $type->user_type }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="message" class="block font-medium">Message:</label>
        <textarea name="message" id="message" rows="3" class="w-full border px-4 py-2 rounded" required></textarea>
    </div>

    <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-800">Send</button>
</form>

<hr class="my-6">

<h3 class="text-lg font-semibold mb-2">Sent Notifications</h3>
<ul class="space-y-2">
    @foreach($notifications as $note)
        <li class="bg-gray-100 p-3 rounded shadow">
            <strong>{{ $note->created_at->format('Y-m-d H:i') }}</strong> - {{ $note->message }}
        </li>
    @endforeach
</ul>
@endsection
