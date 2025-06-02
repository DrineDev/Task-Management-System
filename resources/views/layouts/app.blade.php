<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js', 'resources/js/task-manager.js'])
    <title>Task Management System</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="overflow-x-hidden">
    <!-- Top Bar -->
    <div class="w-full h-auto md:h-32 px-4 md:px-8 py-4 bg-[#D2C5A5] rounded-bl-[36px] md:rounded-bl-[72px] rounded-br-[36px] md:rounded-br-[72px] flex items-center justify-between">
        @auth
            @php
                \Log::info('App layout data', [
                    'user' => $user ?? null,
                    'profile' => $profile ?? null,
                    'has_user' => isset($user),
                    'has_profile' => isset($profile)
                ]);
            @endphp
            <a href="{{ route('profile.show') }}" class="flex items-center hover:opacity-80 transition-opacity">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden">
                    <img src="{{ isset($profile) ? (is_array($profile) ? ($profile['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') : ($profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5')) : 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5' }}" alt="User Avatar" class="w-full h-full object-cover">
                </div>
                <div class="ml-3">
                    <h2 class="text-[24px] md:text-[40px] font-semibold text-[#2F2D2A]">{{ isset($profile) ? (is_array($profile) ? ($profile['name'] ?? $user->email) : ($profile->name ?? $user->email)) : $user->email }}</h2>
                    <p class="text-[12px] md:text-[16px] text-[#2F2D2A]">Welcome Back</p>
                </div>
            </a>
            <button class="w-16 h-16 md:w-20 md:h-20 bg-[#2F2D2A] text-[#D2C5A5] rounded-full p-2 hover:text-[#C7B89B] focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 md:size-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </button>
        @endauth
    </div>

    <!-- Main Content -->
    <main class="grid grid-cols-1 md:grid-cols-3 gap-6 m-9 min-h-[calc(100vh-7rem)] overflow-y-auto">
        @yield('content')
    </main>

    <!-- Include Modals -->
    @include('dashboard.modals.add-task')
    @include('dashboard.modals.edit-task')
    @include('dashboard.modals.delete-task')
    @include('dashboard.modals.add-project')
    @include('dashboard.modals.edit-project')
    @include('dashboard.modals.delete-project')

    <!-- Bottom Navigation -->
    @auth
        <footer class="fixed bottom-0 left-0 right-0 w-full h-28 z-30 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] inset-x-0 flex items-center justify-around md:justify-center md:space-x-16 px-8 py-4 shadow-lg">
            <a href="{{ route('profile.show') }}" class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-user text-[40px] mb-1"></i>
            </a>
            <a href="{{ route('tasks.create') }}" class="w-28 h-28 md:w-24 md:h-24 bg-[#ECE3D2] rounded-lg flex items-center justify-center text-[#1B1A19] text-[40px] md:text-[20px] shadow-xl hover:bg-[#928c80] transition-colors -mt-8 md:-mt-12">
                <i class="fas fa-plus"></i>
            </a>
            <a href="{{ route('dashboard') }}" class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-clipboard-list text-[40px] mb-1"></i>
            </a>
        </footer>
    @endauth

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal handling
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('active');
                }
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('active');
                }
            }

            // Make functions globally available
            window.openModal = openModal;
            window.closeModal = closeModal;

            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('.modal.active');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });
        });
    </script>
</body>
</html>
