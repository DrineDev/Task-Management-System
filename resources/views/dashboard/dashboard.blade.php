<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    <script src="{{ asset('js/data-store.js') }}"></script>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
    <body class="overflow-x-hidden">
    <!-- Top Bar -->
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

    <!-- Main Content -->
    <main class="grid grid-cols-1 md:grid-cols-3 gap-6 m-9 pb-32">
        <!-- Left Column -->
            <div class="md:col-span-2 space-y-6">
            <!-- Search Bar -->
                <div class="flex items-center relative">
                    <input type="text" placeholder="Search..." class="flex-grow bg-stone-200 rounded-xl outline-none text-stone-900/50 text-xl font-bold font-['Inter']">
                    <button id="filterBtn" class="ml-3 p-2 bg-[#C7B89B] rounded-md hover:bg-[#7c6e5b] transition-colors ease-in-out">
                        <i class="fas fa-sliders-h text-[#3D3D3]"></i>
                    </button>
                    
                    <!-- Filter Panel -->
                    <div id="filterPanel" class="hidden absolute right-0 top-full mt-2 w-72 bg-[#2f2d2a] rounded-xl shadow-lg p-4 z-50">
                        <div class="mb-4">
                            <h3 class="text-[#C7B89B] font-semibold mb-2">Priority</h3>
                            <div class="space-y-2">
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-priority" value="high" class="mr-2">
                                    <span class="ml-2">High Priority</span>
                                </label>
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-priority" value="medium" class="mr-2">
                                    <span class="ml-2">Medium Priority</span>
                                </label>
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-priority" value="low" class="mr-2">
                                    <span class="ml-2">Low Priority</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="text-[#C7B89B] font-semibold mb-2">Status</h3>
                            <div class="space-y-2">
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-status" value="completed" class="mr-2">
                                    <span class="ml-2">Completed</span>
                                </label>
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-status" value="incomplete" class="mr-2">
                                    <span class="ml-2">Incomplete</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h3 class="text-[#C7B89B] font-semibold mb-2">Due Date</h3>
                            <div class="space-y-2">
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-date" value="today" class="mr-2">
                                    <span class="ml-2">Due Today</span>
                                </label>
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-date" value="week" class="mr-2">
                                    <span class="ml-2">Due This Week</span>
                                </label>
                                <label class="flex items-center text-[#D2C5A5]">
                                    <input type="checkbox" class="filter-date" value="overdue" class="mr-2">
                                    <span class="ml-2">Overdue</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex justify-between">
                            <button id="clearFilters" class="px-4 py-2 bg-[#C7B89B] text-[#2F2D2A] rounded-md hover:bg-[#7c6e5b] transition-colors">
                                Clear Filters
                            </button>
                            <button id="applyFilters" class="px-4 py-2 bg-[#2F2D2A] text-[#C7B89B] rounded-md hover:bg-[#3D3D3D] transition-colors">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
                <div id="noSearchResults" class="hidden mt-4 p-4 bg-[#2f2d2a] text-[#C7B89B] rounded-xl text-center">
                    No tasks or projects found matching your search.
                </div>

            <!-- Status Cards -->
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

            <!-- Calendar -->
             <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <button id="prevBtn" class="text-[#ece3d2] hover:text-gray-300 transition-colors"><i class="fas fa-chevron-left"></i></button>
                        <h3 id="monthYear" class="text-[26px] font-semibold text-[#ece3d2]">{{ \Carbon\Carbon::now()->format('F Y') }}</h3>
                        <button id="nextBtn" class="text-[#ece3d2] hover:text-gray-300 transition-colors"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="grid grid-cols-7 text-center text-[#ece3d2] font-medium mb-3">
                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                    </div>
                    <div id="calendarDays" class="grid grid-cols-7 gap-2">
                        @php
                            $firstDayOfMonth = \Carbon\Carbon::now()->startOfMonth();
                            $lastDayOfMonth = \Carbon\Carbon::now()->endOfMonth();
                            $firstDayOfWeek = $firstDayOfMonth->dayOfWeek;
                            $daysInMonth = $lastDayOfMonth->day;
                        @endphp
                        
                        @for($i = 0; $i < $firstDayOfWeek; $i++)
                            <div class="h-8"></div>
                        @endfor
                        
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            <div class="calendar-day text-center py-1 rounded hover:bg-[#3d3b38] cursor-pointer text-[#ece3d2]" 
                                 data-date="{{ \Carbon\Carbon::now()->setDay($day)->format('Y-m-d') }}">
                                {{ $day }}
                            </div>
                        @endfor
                    </div>
                </div>

            <!-- Projects and Tasks -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Projects Section -->
                <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-[#C7B89B]">Projects</h3>
                        <a href="{{ route('dashboard', ['add_project' => true]) }}" class="bg-[#C7B89B] text-[#2F2D2A] rounded-md px-3 py-2 font-bold hover:bg-[#B8A98B] focus:outline-none focus:shadow-outline">
                            Add Project
                        </a>
                    </div>
                    <div class="projects-container space-y-4 max-h-[400px] overflow-y-auto pr-2 relative">
                        @include('dashboard.partials.projects-list')
                    </div>
                </div>

                <!-- Tasks Section -->
                    <div class="bg-[#2f2d2a] rounded-xl p-6 shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[#C7B89B] text-xl font-semibold">All Tasks</h3>
                        <a href="{{ route('dashboard', ['add_task' => true]) }}" class="bg-[#C7B89B] text-[#2F2D2A] rounded-md px-3 py-2 font-bold hover:bg-[#B8A98B] focus:outline-none focus:shadow-outline">
                            Add Task
                        </a>
                    </div>
                        <div class="tasks-container space-y-4 max-h-[400px] overflow-y-auto pr-2 relative">
                        @include('dashboard.partials.tasks-list')
                    </div>
                    </div>
                </div>
            </div>
            </div>

        <!-- Right Column - Priority Tasks -->
            <div class="md:col-span-1 space-y-6">
            @include('dashboard.partials.priority-tasks')
            </div>
        </main>

    <!-- Bottom Navigation -->
  <footer class="fixed bottom-0 left-0 right-0 w-full h-28 z-30 bg-[#D2C5A5] rounded-tl-[72px] rounded-tr-[72px] inset-x-0 flex items-center justify-around md:justify-center md:space-x-16 px-8 py-4 shadow-lg">
            <a href="{{ route('profile.show') }}" class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-user text-[40px] mb-1"></i>
            </a>
        <a href="{{ route('dashboard', ['add_task' => true]) }}" class="w-28 h-28 md:w-24 md:h-24 bg-[#ECE3D2] rounded-lg flex items-center justify-center text-[#1B1A19] text-[40px] md:text-[20px] shadow-xl hover:bg-[#928c80] transition-colors -mt-8 md:-mt-12">
                <i class="fas fa-plus"></i>
        </a>
            <button class="text-[#1B1A19] hover:text-[#53504c] transition-colors flex flex-col items-center">
                <i class="fas fa-clipboard-list text-[40px] mb-1"></i>
            </button>
        </footer>

    <!-- Modals -->
    @if(isset($editProject))
        @include('dashboard.modals.edit-project')
    @endif
    @if(isset($addProject) && $addProject === true)
        @include('dashboard.modals.add-project')
    @endif
    @if(isset($editTask))
        @include('dashboard.modals.edit-task')
    @endif
    @if(isset($addTask) && $addTask === true)
        @include('dashboard.modals.add-task')
    @endif
    @if(isset($deleteTask))
        @include('dashboard.modals.delete-task')
    @endif
    @if(isset($deleteProject))
        @include('dashboard.modals.delete-project')
    @endif

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

    <script>
        // Initialize data store with server data
        DataStore.setProjects(@json($projects));
        DataStore.setTasks(@json($tasks));
    </script>

    <!-- Add this before the closing body tag -->
    <script id="tasks-data" type="application/json">
        {!! json_encode($tasks) !!}
    </script>
    <script src="{{ asset('js/calendar.js') }}"></script>

    <script>
        // Task Functions
        function openEditTaskModal(taskId) {
            taskManager.openEditTaskModal(taskId);
        }

        function openDeleteTaskModal(taskId) {
            taskManager.openDeleteTaskModal(taskId);
        }

        // Project Functions
        function openEditProjectModal(projectId) {
            taskManager.openEditProjectModal(projectId);
        }

        function openDeleteProjectModal(projectId) {
            taskManager.openDeleteProjectModal(projectId);
        }

        // Modal Functions
        function openModal(modalId) {
            taskManager.openModal(modalId);
        }

        function closeModal(modalId) {
            taskManager.closeModal(modalId);
        }

        // Progress Bar Function
        function updateProgressValue(value) {
            document.getElementById('editProgressValue').textContent = value + '%';
        }

        // Calendar Functions
        let currentDate = new Date();
        let tasks = {};

        function formatDateKey(date) {
            return date.toISOString().split('T')[0];
        }

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

        function renderCalendar(date) {
            const monthYear = document.getElementById('monthYear');
            const calendarDays = document.getElementById('calendarDays');
            
            // Set month and year
            monthYear.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });
            
            // Clear previous calendar
            calendarDays.innerHTML = '';
            
            // Get first day of month and total days
            const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            
            // Add empty cells for days before first day of month
            for (let i = 0; i < firstDay.getDay(); i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'h-8';
                calendarDays.appendChild(emptyDay);
            }
            
            // Add days of month
            for (let day = 1; day <= lastDay.getDate(); day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'h-8 flex items-center justify-center text-[#ece3d2] hover:bg-[#3D3D3D] rounded cursor-pointer';
                
                const currentDate = new Date(date.getFullYear(), date.getMonth(), day);
                const dateKey = formatDateKey(currentDate);
                
                dayElement.textContent = day;
                dayElement.dataset.date = dateKey;
                
                // Check if there are tasks for this day
                if (tasks[dateKey]) {
                    dayElement.classList.add('has-task');
                    dayElement.title = `${tasks[dateKey].length} task(s)`;
                }
                
                calendarDays.appendChild(dayElement);
            }
        }

        // Search Function
        function performSearch(searchQuery) {
            searchQuery = searchQuery.toLowerCase().trim();
            
            // Get all task and project elements
            const taskElements = document.querySelectorAll('.task-item');
            const projectElements = document.querySelectorAll('.project-item');
            
            // Search through tasks
            taskElements.forEach(task => {
                const taskTitle = task.querySelector('.task-title')?.textContent.toLowerCase() || '';
                const taskDescription = task.querySelector('.task-description')?.textContent.toLowerCase() || '';
                const taskProject = task.querySelector('.task-project')?.textContent.toLowerCase() || '';
                
                const isVisible = taskTitle.includes(searchQuery) || 
                                taskDescription.includes(searchQuery) || 
                                taskProject.includes(searchQuery);
                
                task.style.display = isVisible ? 'block' : 'none';
            });
            
            // Search through projects
            projectElements.forEach(project => {
                const projectTitle = project.querySelector('.project-title')?.textContent.toLowerCase() || '';
                const projectDescription = project.querySelector('.project-description')?.textContent.toLowerCase() || '';
                
                const isVisible = projectTitle.includes(searchQuery) || 
                                projectDescription.includes(searchQuery);
                
                project.style.display = isVisible ? 'block' : 'none';
            });
            
            // Show/hide "no results" message if needed
            const noResultsMessage = document.getElementById('noSearchResults');
            const hasVisibleTasks = Array.from(taskElements).some(task => task.style.display !== 'none');
            const hasVisibleProjects = Array.from(projectElements).some(project => project.style.display !== 'none');
            
            if (noResultsMessage) {
                noResultsMessage.style.display = (hasVisibleTasks || hasVisibleProjects) ? 'none' : 'block';
            }
        }

        // Filter Functions
        let activeFilters = {
            priority: [],
            status: [],
            date: []
        };

        function toggleFilterPanel() {
            const panel = document.getElementById('filterPanel');
            panel.classList.toggle('hidden');
        }

        function updateFilters() {
            // Get all checked filters
            activeFilters.priority = Array.from(document.querySelectorAll('.filter-priority:checked')).map(cb => cb.value);
            activeFilters.status = Array.from(document.querySelectorAll('.filter-status:checked')).map(cb => cb.value);
            activeFilters.date = Array.from(document.querySelectorAll('.filter-date:checked')).map(cb => cb.value);
        }

        function clearFilters() {
            // Uncheck all filter checkboxes
            document.querySelectorAll('.filter-priority, .filter-status, .filter-date').forEach(cb => {
                cb.checked = false;
            });
            
            // Reset active filters
            activeFilters = {
                priority: [],
                status: [],
                date: []
            };
            
            // Show all items
            document.querySelectorAll('.task-item, .project-item').forEach(item => {
                item.style.display = 'block';
            });
            
            // Hide no results message
            const noResultsMessage = document.getElementById('noSearchResults');
            if (noResultsMessage) {
                noResultsMessage.style.display = 'none';
            }
        }

        function applyFilters() {
            updateFilters();
            const searchQuery = document.querySelector('input[placeholder="Search..."]').value.toLowerCase().trim();
            
            // Get all task and project elements
            const taskElements = document.querySelectorAll('.task-item');
            const projectElements = document.querySelectorAll('.project-item');
            
            let hasVisibleItems = false;
            
            // Filter tasks
            taskElements.forEach(task => {
                const taskTitle = task.querySelector('.task-title')?.textContent.toLowerCase() || '';
                const taskDescription = task.querySelector('.task-description')?.textContent.toLowerCase() || '';
                const taskProject = task.querySelector('.task-project')?.textContent.toLowerCase() || '';
                const priority = task.querySelector('.bg-red-500') ? 'high' : 
                               task.querySelector('.bg-orange-500') ? 'medium' : 
                               task.querySelector('.bg-green-500') ? 'low' : '';
                const isCompleted = task.querySelector('.bg-green-500.text-white')?.textContent === 'Completed';
                const dueDate = new Date(task.querySelector('.task-project')?.textContent.split('Due: ')[1] || '');
                
                // Check if task matches search query
                const matchesSearch = taskTitle.includes(searchQuery) || 
                                    taskDescription.includes(searchQuery) || 
                                    taskProject.includes(searchQuery);
                
                // Check if task matches priority filter
                const matchesPriority = activeFilters.priority.length === 0 || 
                                      activeFilters.priority.includes(priority);
                
                // Check if task matches status filter
                const matchesStatus = activeFilters.status.length === 0 || 
                                    (activeFilters.status.includes('completed') && isCompleted) ||
                                    (activeFilters.status.includes('incomplete') && !isCompleted);
                
                // Check if task matches date filter
                const today = new Date();
                const endOfWeek = new Date(today);
                endOfWeek.setDate(today.getDate() + (7 - today.getDay()));
                
                const matchesDate = activeFilters.date.length === 0 || 
                                  (activeFilters.date.includes('today') && dueDate.toDateString() === today.toDateString()) ||
                                  (activeFilters.date.includes('week') && dueDate >= today && dueDate <= endOfWeek) ||
                                  (activeFilters.date.includes('overdue') && dueDate < today && !isCompleted);
                
                // Show/hide task based on all filters
                const isVisible = matchesSearch && matchesPriority && matchesStatus && matchesDate;
                task.style.display = isVisible ? 'block' : 'none';
                
                if (isVisible) hasVisibleItems = true;
            });
            
            // Filter projects
            projectElements.forEach(project => {
                const projectTitle = project.querySelector('.project-title')?.textContent.toLowerCase() || '';
                const projectDescription = project.querySelector('.project-description')?.textContent.toLowerCase() || '';
                const isCompleted = project.querySelector('.bg-green-500.text-white')?.textContent === 'Completed';
                
                // Check if project matches search query
                const matchesSearch = projectTitle.includes(searchQuery) || 
                                    projectDescription.includes(searchQuery);
                
                // Check if project matches status filter
                const matchesStatus = activeFilters.status.length === 0 || 
                                    (activeFilters.status.includes('completed') && isCompleted) ||
                                    (activeFilters.status.includes('incomplete') && !isCompleted);
                
                // Show/hide project based on filters
                const isVisible = matchesSearch && matchesStatus;
                project.style.display = isVisible ? 'block' : 'none';
                
                if (isVisible) hasVisibleItems = true;
            });
            
            // Show/hide no results message
            const noResultsMessage = document.getElementById('noSearchResults');
            if (noResultsMessage) {
                noResultsMessage.style.display = hasVisibleItems ? 'none' : 'block';
            }
            
            // Hide filter panel
            document.getElementById('filterPanel').classList.add('hidden');
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial calendar data
            loadCalendarData();

            // Calendar navigation
            document.getElementById('prevBtn').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });

            document.getElementById('nextBtn').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });

            // Task completion handlers
            document.querySelectorAll('.toggle-task-complete').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to mark this task as complete?')) {
                        const taskId = this.dataset.taskId;
                        fetch(`/tasks/${taskId}/complete`, {
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

            // Project completion handlers
            document.querySelectorAll('.toggle-project-complete').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to mark this project as complete?')) {
                        const projectId = this.dataset.projectId;
                        fetch(`/projects/${projectId}/toggle-complete`, {
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

            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('[id$="Modal"]');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });

            // Close modal when pressing Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const modals = document.querySelectorAll('[id$="Modal"]');
                    modals.forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                            closeModal(modal.id);
                        }
                    });
                }
            });

            // Add search functionality
            const searchInput = document.querySelector('input[placeholder="Search..."]');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    performSearch(e.target.value);
                });
            }

            // Filter panel toggle
            const filterBtn = document.getElementById('filterBtn');
            if (filterBtn) {
                filterBtn.addEventListener('click', toggleFilterPanel);
            }

            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFilters');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', clearFilters);
            }

            // Apply filters
            const applyFiltersBtn = document.getElementById('applyFilters');
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', applyFilters);
            }

            // Close filter panel when clicking outside
            document.addEventListener('click', function(event) {
                const filterPanel = document.getElementById('filterPanel');
                const filterBtn = document.getElementById('filterBtn');
                
                if (filterPanel && !filterPanel.contains(event.target) && event.target !== filterBtn) {
                    filterPanel.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
