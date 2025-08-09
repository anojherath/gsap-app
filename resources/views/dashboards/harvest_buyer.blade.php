@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Harvest Buyer Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for dropdown -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 h-screen flex font-sans">

    <!-- Sidebar -->
    <aside class="w-1/6 bg-gray-200 text-gray-800 p-5 flex flex-col shadow-md">
        <!-- Logo & App Name -->
        <div class="mb-10 flex flex-col items-center space-y-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16"> <!-- Larger logo -->
            <div class="text-center leading-tight">
                <span class="text-lg font-bold block">Green Sun Agri Products</span>
                <span class="text-sm text-gray-500">Harvest Buyer</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col space-y-3 text-sm font-medium">
            <a href="{{ route('harvest_buyer.orders.index') }}" class="hover:bg-gray-300 px-3 py-2 rounded transition">Harvest Order</a>
            <a href="#" class="hover:bg-gray-300 px-3 py-2 rounded transition">Reports</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="w-5/6 flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white shadow flex items-center justify-between px-6 py-4 relative">
            <div></div>

            <!-- Right section: Notifications + Profile -->
            <div class="flex items-center space-x-6" x-data="{ open: false }" @click.away="open = false">

                <!-- Notification Bell -->
                <div class="relative">
                    <button @click="open = !open" class="relative focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600 hover:text-teal-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.64 5.36 6 7.92 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9" />
                        </svg>

                        @php
                            use App\Models\Notification;
                            $unreadCount = Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                        @endphp

                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 shadow-lg rounded-lg z-20 max-h-60 overflow-y-auto" style="display: none;">
                        <div class="p-4 text-sm text-gray-700 font-semibold border-b">Notifications</div>
                        <div class="divide-y divide-gray-100 text-sm">
                            @php
                                $notifications = Notification::where('user_id', auth()->id())->latest()->take(10)->get();
                            @endphp

                            @forelse($notifications as $notification)
                                <div class="px-4 py-2 hover:bg-gray-50 flex justify-between items-center">
                                    <div class="flex-1 pr-2">
                                        <p class="{{ $notification->read_at ? 'text-gray-400' : 'font-semibold text-gray-800' }}">
                                            {{ $notification->message ?? 'Notification message' }}
                                        </p>
                                        <small class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="flex space-x-1">
                                        @if(!$notification->read_at)
                                            <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}">
                                                @csrf
                                                <button type="submit" title="Mark as Read" class="text-green-600 hover:text-green-800 px-2">✔️</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="text-red-600 hover:text-red-800 px-2">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-2 text-gray-500">No notifications.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                        <span class="text-gray-700 font-medium">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </span>
                        <img src="{{ Auth::user()->image_url ? asset('storage/' . Auth::user()->image_url) : 'https://via.placeholder.com/40' }}" alt="Profile Image" class="rounded-full w-10 h-10 border border-gray-300 cursor-pointer">
                    </button>

                    <!-- Dropdown menu -->
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg z-30" style="display: none;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="flex-1 bg-white p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
