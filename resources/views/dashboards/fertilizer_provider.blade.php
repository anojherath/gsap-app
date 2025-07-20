<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fertilizer Provider Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex font-sans">

    <!-- Sidebar -->
    <aside class="w-1/6 bg-teal-900 text-white p-5 flex flex-col">
        <!-- Logo & App Name -->
        <div class="mb-10 flex flex-col items-center space-y-2">
            <!-- Paddy Clipart -->
            <img src="{{ asset('images/plant.png') }}" alt="Paddy Icon" class="w-12 h-12">

            <!-- App Name -->
            <div class="text-center leading-tight">
                <span class="text-lg font-bold block">Green Sun Agri Products</span>
                <span class="text-sm text-teal-200">Fertilizer Provider</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col space-y-4 text-sm">
            <a href="#" class="hover:bg-teal-800 px-3 py-2 rounded">Fertilizer Order</a>
            <a href="#" class="hover:bg-teal-800 px-3 py-2 rounded">Reports</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="w-5/6 flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white shadow flex items-center justify-between px-6 py-4">
            <!-- Search -->
            <div class="w-1/2">
                <input type="text" placeholder="Search..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Profile -->
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 font-medium">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </span>
                <img 
                    src="{{ Auth::user()->image_url ? asset('storage/' . Auth::user()->image_url) : 'https://via.placeholder.com/40' }}" 
                    alt="Profile Image" 
                    class="rounded-full w-10 h-10 border border-gray-300">
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="flex-1 bg-white p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">Welcome, Fertilizer Provider</h1>
            <p class="text-gray-600">This is your dashboard overview. Choose an option from the left.</p>
        </main>
    </div>

</body>
</html>
