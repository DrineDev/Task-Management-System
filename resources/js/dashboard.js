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

function initializeCalendarData() {
    // Get tasks from the DataStore that was initialized in dashboard.blade.php
    const allTasks = DataStore.getTasks();
    console.log('Initializing calendar with tasks:', allTasks);
    
    if (!allTasks || allTasks.length === 0) {
        console.warn('No tasks found in DataStore');
        return;
    }
    
    // Format tasks into calendar data structure
    tasks = {};
    allTasks.forEach(task => {
        if (!task.deadline) {
            console.log('Task has no deadline:', task);
            return;
        }
        const date = formatDateKey(new Date(task.deadline));
        if (!tasks[date]) {
            tasks[date] = [];
        }
        tasks[date].push({
            id: task.id,
            title: task.title,
            priority: task.priority,
            is_completed: task.is_completed,
            deadline: task.deadline
        });
    });
    
    console.log('Formatted calendar data:', tasks);
    if (Object.keys(tasks).length === 0) {
        console.warn('No tasks with deadlines found');
    }
    renderCalendar(currentDate);
}

function renderCalendar(date) {
    console.log('Rendering calendar for date:', date);
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
        // Base classes for all days
        dayElement.className = 'calendar-day h-8 flex items-center justify-center text-[#ece3d2] hover:bg-[#3D3D3D] rounded cursor-pointer';
        
        const currentDate = new Date(date.getFullYear(), date.getMonth(), day);
        const dateKey = formatDateKey(currentDate);
        
        dayElement.textContent = day;
        dayElement.dataset.date = dateKey;
        
        // Check if there are tasks for this day
        if (tasks[dateKey]) {
            console.log('Found tasks for date:', dateKey, tasks[dateKey]);
            
            // Add task count to title
            const taskCount = tasks[dateKey].length;
            dayElement.title = `${taskCount} task(s)`;
            
            // Add visual indicator for tasks
            dayElement.classList.add('has-task');
            console.log('Added has-task class to day:', day, 'Classes:', dayElement.className);
            
            // Check if any task has a deadline and is not completed
            const hasDeadline = tasks[dateKey].some(task => {
                const taskDate = new Date(task.deadline);
                const taskDateKey = formatDateKey(taskDate);
                const isDeadline = taskDateKey === dateKey && !task.is_completed;
                console.log('Checking task deadline:', {
                    day,
                    taskDate,
                    taskDateKey,
                    dateKey,
                    isCompleted: task.is_completed,
                    isDeadline,
                    task
                });
                return isDeadline;
            });
            
            if (hasDeadline) {
                console.log('Adding deadline highlight for day:', day);
                dayElement.classList.add('has-deadline');
                console.log('Updated classes for day:', day, 'Classes:', dayElement.className);
            }
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

// Notification handling
function initializeNotifications() {
    console.log('Initializing notifications...');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    
    console.log('Notification button:', notificationBtn);
    console.log('Notification panel:', notificationPanel);
    
    let isNotificationVisible = false;
    let notificationTimeout;

    function showNotification() {
        console.log('Showing notification...');
        if (!isNotificationVisible && notificationPanel) {
            notificationPanel.classList.remove('hidden');
            // Force a reflow
            notificationPanel.offsetHeight;
            notificationPanel.classList.remove('translate-x-full');
            isNotificationVisible = true;
            
            // Reset the auto-hide timeout
            resetNotificationTimeout();
        } else {
            hideNotification();
        }
    }

    function hideNotification() {
        console.log('Hiding notification...');
        if (isNotificationVisible && notificationPanel) {
            notificationPanel.classList.add('translate-x-full');
            setTimeout(() => {
                notificationPanel.classList.add('hidden');
                isNotificationVisible = false;
            }, 300);
        }
    }

    function resetNotificationTimeout() {
        if (notificationTimeout) {
            clearTimeout(notificationTimeout);
        }
        notificationTimeout = setTimeout(hideNotification, 10000);
    }

    // Add click event listener to notification button
    if (notificationBtn) {
        console.log('Adding click event listener to notification button');
        notificationBtn.addEventListener('click', function(event) {
            console.log('Notification button clicked');
            event.stopPropagation(); // Prevent event from bubbling up
            showNotification();
        });
    }

    // Close notification when clicking outside
    document.addEventListener('click', function(event) {
        if (notificationPanel && isNotificationVisible && 
            !notificationPanel.contains(event.target) && 
            notificationBtn && !notificationBtn.contains(event.target)) {
            hideNotification();
        }
    });

    // Make functions globally available
    window.showNotification = showNotification;
    window.hideNotification = hideNotification;
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    // Initialize notifications
    initializeNotifications();

    // Initialize calendar with existing data
    initializeCalendarData();

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
}); 