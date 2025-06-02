// Task and Project Management
class TaskManager {
    constructor() {
        this.tasks = [];
        this.projects = [];
        this.currentProject = null;
        this.initializeEventListeners();
        this.loadData();
    }

    initializeEventListeners() {
        // Add Task Modal
        const addTaskForm = document.getElementById('addTaskForm');
        if (addTaskForm) {
            addTaskForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createTask();
            });
        }

        // Add Project Modal
        const addProjectForm = document.getElementById('addProjectForm');
        if (addProjectForm) {
            addProjectForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.createProject();
            });
        }

        // Close buttons for all modals
        document.querySelectorAll('[id^="close"]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.closest('.modal').id;
                this.closeModal(modalId);
            });
        });

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal.id);
                }
            });
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        this.closeModal(modal.id);
                    }
                });
            }
        });

        // Priority buttons
        document.querySelectorAll('.priority-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.setPriority(e.target.dataset.priority);
            });
        });
    }

    async loadData() {
        try {
            const [tasksResponse, projectsResponse] = await Promise.all([
                fetch('/tasks'),
                fetch('/projects')
            ]);

            this.tasks = await tasksResponse.json();
            this.projects = await projectsResponse.json();

            this.renderTasks();
            this.renderProjects();
            this.updateCalendar();
        } catch (error) {
            console.error('Error loading data:', error);
        }
    }

    async createTask() {
        const formData = new FormData(document.getElementById('addTaskForm'));
        const taskData = {
            title: formData.get('title'),
            description: formData.get('description'),
            start_date: formData.get('start_date'),
            deadline: formData.get('deadline'),
            priority: parseInt(formData.get('priority')),
            project_id: formData.get('project_id') || null
        };

        try {
            const response = await fetch('/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(taskData)
            });

            if (response.ok) {
                const task = await response.json();
                this.tasks.push(task);
                this.renderTasks();
                this.updateCalendar();
                closeModal('addTaskModal');
                window.location.reload(); // Reload to show the new task
            }
        } catch (error) {
            console.error('Error creating task:', error);
        }
    }

    async createProject() {
        const formData = new FormData(document.getElementById('addProjectForm'));
        const projectData = {
            name: formData.get('name'),
            description: formData.get('description')
        };

        try {
            const response = await fetch('/projects', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(projectData)
            });

            if (response.ok) {
                const project = await response.json();
                this.projects.push(project);
                this.renderProjects();
                closeModal('addProjectModal');
                window.location.reload(); // Reload to show the new project
            }
        } catch (error) {
            console.error('Error creating project:', error);
        }
    }

    renderTasks() {
        const tasksContainer = document.querySelector('.tasks-container');
        const filteredTasks = this.currentProject 
            ? this.tasks.filter(task => task.project_id === this.currentProject.id)
            : this.tasks;

        if (filteredTasks.length === 0) {
            tasksContainer.innerHTML = '<p class="text-center text-gray-500">No tasks found</p>';
            return;
        }

        tasksContainer.innerHTML = filteredTasks.map(task => `
            <div class="bg-[#C7B89B] rounded-lg p-4 flex items-center justify-between shadow-md">
                <div>
                    <p class="text-[#1B1A19] font-semibold">${task.title}</p>
                    <p class="text-[#2F2D2A] text-sm">${new Date(task.deadline).toLocaleDateString()}</p>
                </div>
                <div class="relative">
                    <i class="fas fa-ellipsis-v text-[#2F2D2A] cursor-pointer task-settings-icon"></i>
                    <div class="absolute right-0 w-44 z-40 bg-[#3D3D3D] rounded-md shadow-lg hidden task-options">
                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" 
                                onclick="taskManager.editTask(${task.id})">Edit</button>
                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" 
                                onclick="taskManager.deleteTask(${task.id})">Delete</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    renderProjects() {
        const projectsContainer = document.querySelector('.projects-container');
        
        if (this.projects.length === 0) {
            projectsContainer.innerHTML = '<p class="text-center text-gray-500">No projects found</p>';
            return;
        }

        projectsContainer.innerHTML = this.projects.map(project => `
            <div class="rounded-lg p-4 flex items-center justify-between shadow-md">
                <div>
                    <p class="font-semibold">${project.name}</p>
                    <p class="text-gray-400 text-sm">${project.tasks?.length || 0} tasks</p>
                </div>
                <div class="relative">
                    <i class="fas fa-ellipsis-v text-[#D2C5A5] cursor-pointer project-settings-icon"></i>
                    <div class="absolute right-0 w-44 z-40 bg-[#3D3D3D] rounded-md shadow-lg hidden project-options">
                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" 
                                onclick="taskManager.editProject(${project.id})">Edit</button>
                        <button class="block px-4 py-2 text-lg text-[#D2C5A5] hover:bg-[#555555] w-full text-left" 
                                onclick="taskManager.deleteProject(${project.id})">Delete</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    updateCalendar() {
        const calendarDays = document.getElementById('calendarDays');
        const days = calendarDays.querySelectorAll('div');
        
        days.forEach(day => {
            const date = day.dataset.date;
            if (date) {
                const hasTask = this.tasks.some(task => 
                    new Date(task.deadline).toDateString() === new Date(date).toDateString()
                );
                
                if (hasTask) {
                    day.classList.add('has-task');
                } else {
                    day.classList.remove('has-task');
                }
            }
        });
    }

    setPriority(priority) {
        document.querySelectorAll('.priority-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-priority="${priority}"]`).classList.add('active');
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('active');
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('active');
        }
    }

    async openEditTaskModal(taskId) {
        try {
            const response = await fetch(`/tasks/${taskId}/edit`);
            const data = await response.json();
            
            // Populate form fields
            document.getElementById('editTaskId').value = taskId;
            document.getElementById('editTaskTitle').value = data.title;
            document.getElementById('editTaskDescription').value = data.description;
            document.getElementById('editTaskDeadline').value = data.deadline;
            document.getElementById('editTaskPriority').value = data.priority;
            document.getElementById('editTaskProgress').value = data.progress;
            
            this.openModal('editTaskModal');
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading task data: ' + error.message);
        }
    }

    async toggleTaskComplete(taskId) {
        if (confirm('Are you sure you want to mark this task as complete?')) {
            try {
                const response = await fetch(`/tasks/${taskId}/progress`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        progress: 100
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to mark task as complete');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error marking task as complete: ' + error.message);
            }
        }
    }

    openDeleteTaskModal(taskId) {
        document.getElementById('deleteTaskId').value = taskId;
        this.openModal('deleteTaskModal');
    }

    async openEditProjectModal(projectId) {
        try {
            const response = await fetch(`/projects/${projectId}/edit`);
            const data = await response.json();
            
            document.getElementById('editProjectId').value = projectId;
            document.getElementById('editProjectName').value = data.name;
            document.getElementById('editProjectDescription').value = data.description;
            
            this.openModal('editProjectModal');
        } catch (error) {
            console.error('Error:', error);
            alert('Error loading project data: ' + error.message);
        }
    }

    async toggleProjectComplete(projectId) {
        if (confirm('Are you sure you want to mark this project as complete?')) {
            try {
                const response = await fetch(`/projects/${projectId}/toggle-complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to mark project as complete');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error marking project as complete: ' + error.message);
            }
        }
    }

    openDeleteProjectModal(projectId) {
        document.getElementById('deleteProjectId').value = projectId;
        this.openModal('deleteProjectModal');
    }
}

// Initialize TaskManager
const taskManager = new TaskManager();

// Add event listeners when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add task form handling
    const addTaskForm = document.getElementById('addTaskForm');
    if (addTaskForm) {
        addTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/tasks', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to create task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating task: ' + error.message);
            });
        });
    }

    // Edit task form handling
    const editTaskForm = document.getElementById('editTaskForm');
    if (editTaskForm) {
        editTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.getElementById('editTaskId').value;
            const formData = new FormData(this);
            
            fetch(`/tasks/${taskId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to update task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating task: ' + error.message);
            });
        });
    }

    // Delete task form handling
    const deleteTaskForm = document.getElementById('deleteTaskForm');
    if (deleteTaskForm) {
        deleteTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.getElementById('deleteTaskId').value;
            
            fetch(`/tasks/${taskId}`, {
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
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to delete task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting task: ' + error.message);
            });
        });
    }

    // Add project form handling
    const addProjectForm = document.getElementById('addProjectForm');
    if (addProjectForm) {
        addProjectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/projects', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to create project');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating project: ' + error.message);
            });
        });
    }

    // Edit project form handling
    const editProjectForm = document.getElementById('editProjectForm');
    if (editProjectForm) {
        editProjectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const projectId = document.getElementById('editProjectId').value;
            const formData = new FormData(this);
            
            fetch(`/projects/${projectId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to update project');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating project: ' + error.message);
            });
        });
    }

    // Delete project form handling
    const deleteProjectForm = document.getElementById('deleteProjectForm');
    if (deleteProjectForm) {
        deleteProjectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const projectId = document.getElementById('deleteProjectId').value;
            
            fetch(`/projects/${projectId}`, {
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
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to delete project');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting project: ' + error.message);
            });
        });
    }
}); 