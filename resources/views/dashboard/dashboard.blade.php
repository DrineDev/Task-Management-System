<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>dashboard</title>
        @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    </head>
    <body>
        <body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white border-r hidden md:block">
            <div class="p-6">
                <h1 class="text-xl font-bold text-gray-800">My Dashboard</h1>
            </div>
            <nav class="mt-6">
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-200">Dashboard</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-200">Users</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-200">Settings</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navbar -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-700">Dashboard</h2>
                <div>
                    <span class="text-gray-600">Hi, Admin</span>
                </div>
            </header>

            <!-- Content Area -->
            <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl shadow p-4">
                        <div class="text-sm text-gray-500">Users</div>
                        <div class="text-2xl font-bold">1,234</div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl shadow p-4">
                        <div class="text-sm text-gray-500">Revenue</div>
                        <div class="text-2xl font-bold">$12,345</div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-xl shadow p-4">
                        <div class="text-sm text-gray-500">New Orders</div>
                        <div class="text-2xl font-bold">123</div>
                    </div>
                </div>

                <!-- Additional Section -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Recent Activity</h3>
                    <div class="bg-white rounded-xl shadow p-4">
                        <ul>
                            <li class="border-b py-2">User John registered</li>
                            <li class="border-b py-2">Order #4321 placed</li>
                            <li class="py-2">Profile updated</li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body
    </body>
</html>
