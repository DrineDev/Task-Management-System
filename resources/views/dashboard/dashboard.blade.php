<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/dashboard.css', 'resources/js/app.js', 'resources/js/task-manager.js'])
    <title>dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
    <body class="overflow-x-hidden">
<!-- ------------------------------- TOP BAR ------------------------------- -->

<div class="w-full h-auto md:h-32 px-4 md:px-8 py-4 bg-[#D2C5A5] rounded-bl-[36px] md:rounded-bl-[72px] rounded-br-[36px] md:rounded-br-[72px] flex items-center justify-between">
    <a href="{{ route('profile.show') }}" class="flex items-center hover:opacity-80 transition-opacity">
        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden">
            <img src="{{ is_array($profile) ? ($profile['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') : ($profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->email) . '&background=2F2D2A&color=D2C5A5') }}" alt="User Avatar" class="w-full h-full object-cover">
        </div>
        <div class="ml-3">
            <h2 class="text-[24px] md:text-[40px] font-semibold text-[#2F2D2A]">{{ is_array($profile) ? ($profile['name'] ?? $user->email) : ($profile->name ?? $user->email) }}</h2>
            <p class="text-[12px] md:text-[16px] text-[#2F2D2A]">Welcome Back</p>
        </div>
    </a>
    <button class="w-16 h-16 md:w-20 md:h-20 bg-[#2F2D2A] text-[#D2C5A5] rounded-full p-2 hover:text-[#C7B89B] focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center justify-center">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 md:size-10">
    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
    </svg>
    </button>
</div>

<!-- ----------------------------------------------------------------------- -->
<!--                             DASHBOARD MAIN                              -->
<!-- ----------------------------------------------------------------------- -->

    <main class="grid grid-cols-1 md:grid-cols-3 gap-6 m-9 pb-32">
<!-- ------------------------------- SEARCH -------------------------------- -->
            <div class="md:col-span-2 space-y-6">
                <div class="flex items-center">
                    <input type="text" placeholder="Search..." class="flex-grow bg-stone-200 rounded-xl outline-none text-stone-900/50 text-xl font-bold font-['Inter']">
                    <button class="ml-3 p-2 bg-[#C7B89B] rounded-md hover:bg-[#7c6e5b] transition-colors ease-in-out">
                        <i class="fas fa-sliders-h text-[#3D3D3]"></i>
                    </button>
                </div>
<!-- ------------------------------- STATUS -------------------------------- -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-[#c7bb9b] rounded-xl p-5 text-center shadow-lg">
                        <i class="fa-regular fa-clock text-[#2F2D2A] text-3xl mb-2"></i>
                        <h2 id="ongoing" class="text-4xl font-bold text-[#2F2D2A]">{{ $stats['ongoing'] }}</h2>
                        <p class="text-[#1B1A19]">Ongoing</p>
                    </div>
                    <div class="bg-[#c7bb9b] rounded-xl p-5 text-center shadow-lg">
                        <i class="fa-regular fa-circle-check text-[#2F2D2A] text-3xl mb-2"></i>
                        <h2 id="completed" class="text-4xl font-bold text-[#2F2D2A]">{{ $stats['completed'] }}</h2>
                        <p class="text-[#1B1A19]">Completed</p>
                    </div>
                    <div class="bg-[#c7bb9b] rounded-xl p-5 text-center shadow-lg">
                        <i class="fa-regular fa-calendar-xmark text-[#2F2D2A] text-3xl mb-2"></i>
                        <h2 id="overdue" class="text-4xl font-bold text-[#2F2D2A]">{{ $stats['overdue'] }}</h2>
                        <p class="text-[#1B1A19]">Overdue</p>
                    </div>
                </div>
<!-- ------------------------------ CALENDAR ------------------------------- -->
             <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <button id="prevBtn" class="text-[#ece3d2] hover:text-gray-300 transition-colors"><i class="fas fa-chevron-left"></i></button>
                        <h3 id="monthYear" class="text-[26px] font-semibold text-[#ece3d2]"></h3>
                        <button id="nextBtn" class="text-[#ece3d2] hover:text-gray-300 transition-colors"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="grid grid-cols-7 text-center text-[#ece3d2] font-medium mb-3">
                        <span>Sun</span>
                        <span>Mon</span>
                        <span>Tue</span>
                        <span>Wed</span>
                        <span>Thu</span>
                        <span>Fri</span>
                        <span>Sat</span>
                    </div>
                    <div id="calendarDays" class="grid grid-cols-7 gap-2">
                    </div>
                </div>

<!-- ------------------------------ PROJECTS AND TASKS ------------------------------- -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-[#C7B89B]">Projects</h3>
                            <a href="{{ route('dashboard.add-project') }}" class="bg-[#C7B89B] text-[#2F2D2A] rounded-md px-3 py-2 font-bold hover:bg-[#B8A98B] focus:outline-none focus:shadow-outline">
                                Add Project
                            </a>
                        </div>
                        <div class="projects-container space-y-4 max-h-[400px] overflow-y-auto pr-2 relative">
                            @php
                                $incompleteProjects = array_filter($projects, function($project) {
                                    return !($project['is_complete'] ?? false);
                                });
                                $completeProjects = array_filter($projects, function($project) {
                                    return $project['is_complete'] ?? false;
                                });
                            @endphp

                            @if(count($incompleteProjects) > 0 || count($completeProjects) > 0)
                                @if(count($incompleteProjects) > 0)
                                    @foreach($incompleteProjects as $project)
                                        <div class="bg-[#C7B89B] rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <h4 class="font-semibold text-[#2F2D2A] mb-2">{{ $project['name'] }}</h4>
                                                    </div>
                                                    <p class="text-sm text-[#2F2D2A]">{{ $project['description'] ?? 'No description' }}</p>
                                                    <p class="text-xs text-[#2F2D2A] mt-2">
                                                        Created: {{ \Carbon\Carbon::parse($project['created_at'])->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('projects.edit', ['id' => $project['id']]) }}" class="px-3 py-1 text-sm bg-[#2F2D2A] text-[#D2C5A5] rounded-md hover:bg-[#3D3D3D]">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="toggle-project-complete px-3 py-1 text-sm bg-[#2F2D2A] text-green-500 rounded-md hover:bg-[#3D3D3D]" data-project-id="{{ $project['id'] }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="delete-project-btn px-3 py-1 text-sm bg-[#2F2D2A] text-red-500 rounded-md hover:bg-[#3D3D3D]" data-project-id="{{ $project['id'] }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(count($completeProjects) > 0)
                                    @if(count($incompleteProjects) > 0)
                                        <div class="my-4 border-t border-[#3D3D3D]"></div>
                                    @endif
                                    <div class="text-[#C7B89B] text-sm font-semibold mb-2">Completed Projects</div>
                                    @foreach($completeProjects as $project)
                                        <div class="bg-[#C7B89B] rounded-lg p-4 opacity-75">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <h4 class="font-semibold text-[#2F2D2A] mb-2">{{ $project['name'] }}</h4>
                                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Completed</span>
                                                    </div>
                                                    <p class="text-sm text-[#2F2D2A]">{{ $project['description'] ?? 'No description' }}</p>
                                                    <p class="text-xs text-[#2F2D2A] mt-2">
                                                        Created: {{ \Carbon\Carbon::parse($project['created_at'])->format('M d, Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @else
                                <p class="text-[#C7B89B]">No projects yet. Create your first project!</p>
                            @endif
                        </div>
                    </div>
                    <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                        <h3 class="text-[#C7B89B] text-xl font-semibold mb-4">All Tasks</h3>
                        <div class="tasks-container space-y-4 max-h-[400px] overflow-y-auto pr-2 relative">
                            @php
                                $incompleteTasks = array_filter($tasks, function($task) {
                                    return !($task['is_completed'] ?? false);
                                });
                                $completeTasks = array_filter($tasks, function($task) {
                                    return $task['is_completed'] ?? false;
                                });
                            @endphp

                            @if(count($incompleteTasks) > 0 || count($completeTasks) > 0)
                                @if(count($incompleteTasks) > 0)
                                    @foreach($incompleteTasks as $task)
                                        <div class="bg-[#C7B89B] rounded-lg p-4 flex justify-between items-center">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h4 class="font-semibold text-[#2F2D2A]">{{ $task['title'] }}</h4>
                                                    @if($task['priority'] == 3)
                                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">High</span>
                                                    @elseif($task['priority'] == 2)
                                                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">Medium</span>
                                                    @else
                                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Low</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-[#2F2D2A]">{{ $task['description'] ?? 'No description' }}</p>
                                                <p class="text-xs text-[#2F2D2A] mt-1">
                                                    Due: {{ \Carbon\Carbon::parse($task['deadline'])->format('M d, Y') }}
                                                </p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('tasks.edit', ['id' => $task['id']]) }}" class="px-3 py-1 text-sm bg-[#2F2D2A] text-[#D2C5A5] rounded-md hover:bg-[#3D3D3D]">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="toggle-task-complete px-3 py-1 text-sm bg-[#2F2D2A] text-green-500 rounded-md hover:bg-[#3D3D3D]" data-task-id="{{ $task['id'] }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="delete-task-btn px-3 py-1 text-sm bg-[#2F2D2A] text-red-500 rounded-md hover:bg-[#3D3D3D]" data-task-id="{{ $task['id'] }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(count($completeTasks) > 0)
                                    @if(count($incompleteTasks) > 0)
                                        <div class="my-4 border-t border-[#3D3D3D]"></div>
                                    @endif
                                    <div class="text-[#C7B89B] text-sm font-semibold mb-2">Completed Tasks</div>
                                    @foreach($completeTasks as $task)
                                        <div class="bg-[#C7B89B] rounded-lg p-4 flex justify-between items-center opacity-75">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h4 class="font-semibold text-[#2F2D2A]">{{ $task['title'] }}</h4>
                                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Completed</span>
                                                </div>
                                                <p class="text-sm text-[#2F2D2A]">{{ $task['description'] ?? 'No description' }}</p>
                                                <p class="text-xs text-[#2F2D2A] mt-1">
                                                    Due: {{ \Carbon\Carbon::parse($task['deadline'])->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @else
                                <p class="text-[#C7B89B]">No tasks yet. Create your first task!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

<!-- --------------------------- Priority Tasks ---------------------------- -->
            <div class="md:col-span-1 space-y-6">
                @php
                    $highPriorityTasks = array_filter($tasks, function($task) {
                        return ($task['priority'] ?? 0) === 3 && !($task['is_completed'] ?? false);
                    });
                    $highPriorityTasks = array_slice($highPriorityTasks, 0, 3);
                @endphp
                @if(count($highPriorityTasks) > 0)
                    @foreach($highPriorityTasks as $task)
                    <div class="bg-[#C7B89B] rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-[#2F2D2A] text-[#D2C5A5] text-xs font-semibold px-2 py-1 rounded-full">High</span>
                            @if(!($task['is_completed'] ?? false))
                                <div class="flex space-x-2">
                                    <a href="{{ route('tasks.edit', ['id' => $task['id']]) }}" class="px-3 py-1 text-sm bg-[#2F2D2A] text-[#D2C5A5] rounded-md hover:bg-[#3D3D3D]">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="toggle-task-complete px-3 py-1 text-sm bg-[#2F2D2A] text-green-500 rounded-md hover:bg-[#3D3D3D]" data-task-id="{{ $task['id'] }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="delete-task-btn px-3 py-1 text-sm bg-[#2F2D2A] text-red-500 rounded-md hover:bg-[#3D3D3D]" data-task-id="{{ $task['id'] }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @else
                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Completed</span>
                            @endif
                        </div>
                        <h3 class="text-[#1B1A19] text-lg font-semibold mb-2">{{ $task['title'] }}</h3>
                        <p class="text-[#2F2D2A] text-sm mb-4">{{ $task['description'] }}</p>
                        <div class="text-[#2F2D2A] flex items-center justify-between text-sm">
                            <p>Progress</p>
                            <p>{{ $task['progress'] ?? 0 }}%</p>
                        </div>
                        <div class="w-full bg-[#D9D9D9] rounded-full h-2.5 mt-2">
                            <div class="bg-[#3D3D3D] h-2.5 rounded-full" style="width: {{ $task['progress'] ?? 0 }}%"></div>
                        </div>
                        <p class="text-[#2F2D2A] text-xs mt-4">{{ \Carbon\Carbon::parse($task['deadline'])->format('D, d M Y') }}</p>
                    </div>
                    @endforeach
                @endif
            </div>
        </main>

<!-- ------------------------------- NAVBAR -------------------------------- -->

  <footer class="fixed bottom-0 left-0 right-0 w-full h-28 z-30 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] inset-x-0 flex items-center justify-around md:justify-center md:space-x-16 px-8 py-4 shadow-lg">
            <a href="{{ route('profile.show') }}" class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-user text-[40px] mb-1"></i>
            </a>
            <a href="{{ route('tasks.create') }}" class="w-28 h-28 md:w-24 md:h-24 bg-[#ECE3D2] rounded-lg flex items-center justify-center text-[#1B1A19] text-[40px] md:text-[20px] shadow-xl hover:bg-[#928c80] transition-colors -mt-8 md:-mt-12">
                <i class="fas fa-plus"></i>
            </a>
            <button class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-clipboard-list text-[40px] mb-1"></i>
            </button>
        </footer>

<!-- ----------------------------------------------------------------------- -->
<!--                                 MODALS                                  -->
<!-- ----------------------------------------------------------------------- -->

<!-- --------------------------- CONFIRM DELETE MODAL --------------------------- -->
<div id="confirmDeleteModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 hidden">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-[90vw] max-w-xs md:max-w-sm text-center">
        <h2 class="text-[24px] font-semibold text-[#D2C5A5] mb-4">Confirm Delete</h2>
        <p class="text-[#C7B89B] mb-8">Are you sure you want to delete this item? This action cannot be undone.</p>
        <div class="flex justify-center space-x-4">
            <button id="cancelDeleteBtn" class="bg-[#C7B89B] text-[#2D2A2A] rounded-xl px-6 py-2 font-bold hover:bg-[#B8A98B]">Cancel</button>
            <button id="confirmDeleteBtn" class="bg-red-600 text-white rounded-xl px-6 py-2 font-bold hover:bg-red-700">Delete</button>
        </div>
    </div>
        </div>

<!-- Add the delete confirmation modal -->
<div id="deleteTaskModal" class="fixed inset-0 w-full h-full bg-black bg-opacity-50 z-50 hidden">
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="bg-[#2F2D2A] rounded-3xl p-8 w-[90%] max-w-md">
            <div class="text-center">
                <h2 class="text-[24px] font-semibold text-[#D2C5A5] mb-4">Delete Task</h2>
                <p class="text-[#C7B89B] mb-8">Are you sure you want to delete this task? This action cannot be undone.</p>
                <div class="flex justify-center space-x-4">
                    <button id="cancelDeleteTask" class="bg-[#C7B89B] text-[#2D2A2A] rounded-xl px-6 py-2 font-bold hover:bg-[#B8A98B]">Cancel</button>
                    <button id="confirmDeleteTask" class="bg-red-600 text-white rounded-xl px-6 py-2 font-bold hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ----------------------------------------------------------------------- -->
<!--                                 SCRIPT -------------------------------- -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Setting up event handlers');

        // Settings options toggle for both tasks and projects
        const settingsIcons = document.querySelectorAll('.task-settings-icon, .project-settings-icon');
        console.log('Found settings icons:', settingsIcons.length);

        settingsIcons.forEach((icon) => {
            icon.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get the options menu for this icon
                const options = this.nextElementSibling;
                console.log('Options menu found:', options);
                
                // Hide all other options menus
                document.querySelectorAll('.task-options, .project-options').forEach(menu => {
                    if (menu !== options) {
                        menu.classList.add('hidden');
                    }
                });
                
                // Toggle this options menu
                options.classList.toggle('hidden');
                console.log('Options menu visibility toggled:', !options.classList.contains('hidden'));
            });
        });

        // Close settings when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.task-settings-icon') && !e.target.closest('.project-settings-icon') && 
                !e.target.closest('.task-options') && !e.target.closest('.project-options')) {
                document.querySelectorAll('.task-options, .project-options').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Prevent options menu from closing when clicking inside it
        document.querySelectorAll('.task-options, .project-options').forEach((menu) => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Add a test click handler to verify event binding
        document.body.addEventListener('click', function(e) {
            console.log('Body clicked at:', e.target);
        });

        // Add form submission handler for delete task
        document.querySelectorAll('form[action*="tasks/destroy"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this task?')) {
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-HTTP-Method-Override': 'DELETE'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to dashboard
                            window.location.href = '{{ route("dashboard") }}';
                        } else {
                            throw new Error(data.message || 'Failed to delete task');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting task:', error);
                        alert('Error deleting task: ' + error.message);
                    });
                }
            });
        });

        // Delete Task Modal handling
        const deleteTaskModal = document.getElementById('deleteTaskModal');
        const cancelDeleteTask = document.getElementById('cancelDeleteTask');
        const confirmDeleteTask = document.getElementById('confirmDeleteTask');
        let taskToDelete = null;

        // Show delete modal
        document.querySelectorAll('.delete-task-btn').forEach(button => {
            button.addEventListener('click', function() {
                taskToDelete = this.dataset.taskId;
                deleteTaskModal.classList.remove('hidden');
            });
        });

        // Hide delete modal
        cancelDeleteTask.addEventListener('click', function() {
            deleteTaskModal.classList.add('hidden');
            taskToDelete = null;
        });

        // Handle delete confirmation
        confirmDeleteTask.addEventListener('click', function() {
            if (taskToDelete) {
                const baseUrl = window.location.pathname.split('/dashboard')[0];
                fetch(`${baseUrl}/tasks/${taskToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal and redirect to dashboard
                        deleteTaskModal.classList.add('hidden');
                        window.location.href = '{{ route("dashboard") }}';
                    } else {
                        throw new Error(data.message || 'Failed to delete task');
                    }
                })
                .catch(error => {
                    console.error('Error deleting task:', error);
                    alert('Error deleting task: ' + error.message);
                });
            }
        });

        // Close modal when clicking outside
        deleteTaskModal.addEventListener('click', function(e) {
            if (e.target === deleteTaskModal) {
                deleteTaskModal.classList.add('hidden');
                taskToDelete = null;
            }
        });

        // Handle project deletion
        document.querySelectorAll('.delete-project-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this project?')) {
                    const projectId = this.dataset.projectId;
                    const baseUrl = window.location.pathname.split('/dashboard')[0];
                    fetch(`${baseUrl}/projects/${projectId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to delete project');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting project:', error);
                        alert('Error deleting project: ' + error.message);
                    });
                }
            });
        });

        // Handle project completion toggle
        document.querySelectorAll('.toggle-project-complete').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to mark this project as complete?')) {
                    const projectId = this.dataset.projectId;
                    const baseUrl = window.location.pathname.split('/dashboard')[0];
                    fetch(`${baseUrl}/projects/${projectId}/toggle-complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to mark project as complete');
                        }
                    })
                    .catch(error => {
                        console.error('Error marking project as complete:', error);
                        alert('Error marking project as complete: ' + error.message);
                    });
                }
            });
        });

        // Handle task completion
        document.querySelectorAll('.toggle-task-complete').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to mark this task as complete?')) {
                    const taskId = this.dataset.taskId;
                    const baseUrl = window.location.pathname.split('/dashboard')[0];
                    fetch(`${baseUrl}/tasks/${taskId}/complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to mark task as complete');
                        }
                    })
                    .catch(error => {
                        console.error('Error marking task as complete:', error);
                        alert('Error marking task as complete: ' + error.message);
                    });
                }
            });
        });

        // Rest of your existing JavaScript code...
    });

    const monthYear = document.getElementById("monthYear");
    const calendarDays = document.getElementById("calendarDays");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    let currentDate = new Date();
    let tasks = {}; // Stores tasks keyed by 'YYYY-MM-DD'

    function formatDateKey(date) {
        return date.toISOString().split('T')[0];
    }

    // Load calendar data from backend
    async function loadCalendarData() {
        try {
            const response = await fetch('/dashboard/calendar');
            if (response.ok) {
                tasks = await response.json();
                renderCalendar(currentDate);
            } else {
                console.error('Failed to load calendar data');
            }
        } catch (error) {
            console.error('Error loading calendar data:', error);
        }
    }

    // Load initial calendar data
    loadCalendarData();

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDay = firstDay.getDay();
        const totalDays = lastDay.getDate();

        monthYear.textContent = `${date.toLocaleString("default", { month: "long" })} ${year}`;
        calendarDays.innerHTML = "";

        const totalCells = 42; // 6 rows * 7 days
        let dayCounter = 1;

        for (let i = 0; i < totalCells; i++) {
            const cell = document.createElement("div");

            if (i < startDay || dayCounter > totalDays) {
                cell.classList.add("text-gray-600");
                cell.textContent = ""; // Empty cell
            } else {
                const dateObj = new Date(year, month, dayCounter);
                const dateKey = formatDateKey(dateObj);
                const isToday = 
                    dayCounter === new Date().getDate() &&
                    month === new Date().getMonth() &&
                    year === new Date().getFullYear();
                const hasTask = tasks[dateKey] && tasks[dateKey].length > 0;

                cell.className = `py-7 rounded-lg cursor-pointer relative text-center text-gray-100 ${
                    isToday ? 'bg-[#1B1A1B] text-white font-bold' : 'hover:bg-gray-700'
                }`;

                cell.innerHTML = `${dayCounter}`;

                if (hasTask) {
                    // Group tasks by priority
                    const tasksByPriority = tasks[dateKey].reduce((acc, task) => {
                        if (!acc[task.priority]) acc[task.priority] = [];
                        acc[task.priority].push(task);
                        return acc;
                    }, {});

                    // Create indicators for each priority level
                    Object.entries(tasksByPriority).forEach(([priority, priorityTasks]) => {
                        const indicator = document.createElement('div');
                        const color = priority === '3' ? '#ef4444' : // High priority - red
                                    priority === '2' ? '#f59e0b' : // Medium priority - orange
                                    '#22c55e'; // Low priority - green
                        
                        indicator.className = 'absolute bottom-1 left-1 right-1 h-1.5 rounded';
                        indicator.style.backgroundColor = color;
                        cell.appendChild(indicator);

                        // Add tooltip with task details
                        const tooltip = document.createElement('div');
                        tooltip.className = 'absolute z-50 hidden bg-[#2F2D2A] text-white p-2 rounded-lg shadow-lg text-sm whitespace-nowrap';
                        tooltip.style.bottom = '100%';
                        tooltip.style.left = '50%';
                        tooltip.style.transform = 'translateX(-50%)';
                        tooltip.style.marginBottom = '0.5rem';
                        
                        const taskList = priorityTasks.map(task => 
                            `<div class="mb-1">
                                <span class="font-semibold">${task.title}</span>
                                <span class="text-xs ml-2">(${task.priority === '3' ? 'High' : task.priority === '2' ? 'Medium' : 'Low'} priority)</span>
                            </div>`
                        ).join('');
                        
                        tooltip.innerHTML = taskList;
                        cell.appendChild(tooltip);

                        // Show/hide tooltip on hover
                        cell.addEventListener('mouseenter', () => {
                            tooltip.classList.remove('hidden');
                        });
                        cell.addEventListener('mouseleave', () => {
                            tooltip.classList.add('hidden');
                        });
                    });
                }

                dayCounter++;
            }

            calendarDays.appendChild(cell);
        }
    }

    prevBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    // Initial render of the calendar
    renderCalendar(currentDate);
</script>

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
</body>

</html>
