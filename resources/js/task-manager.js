class TaskManager {
    constructor() {
        this.projects = [];
        this.tasks = [];
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Add event listeners for project buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="edit-project"]')) {
                const projectId = e.target.dataset.projectId;
                this.openEditProjectModal(projectId);
            } else if (e.target.matches('[data-action="delete-project"]')) {
                const projectId = e.target.dataset.projectId;
                this.openDeleteProjectModal(projectId);
            }
        });
    }

    // Modal Functions
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Project Functions
    openEditProjectModal(projectId) {
        const project = this.projects.find(p => p.id === projectId);
        if (project) {
            const modal = document.getElementById('editProjectModal');
            if (modal) {
                // Populate the form with project data
                const form = modal.querySelector('form');
                form.querySelector('[name="name"]').value = project.name;
                form.querySelector('[name="description"]').value = project.description || '';
                form.querySelector('[name="project_id"]').value = project.id;
                
                this.openModal('editProjectModal');
            }
        }
    }

    openDeleteProjectModal(projectId) {
        const project = this.projects.find(p => p.id === projectId);
        if (project) {
            const modal = document.getElementById('deleteProjectModal');
            if (modal) {
                // Set the project ID in the delete form
                const form = modal.querySelector('form');
                form.querySelector('[name="project_id"]').value = project.id;
                
                this.openModal('deleteProjectModal');
            }
        }
    }

    // Data Management
    setProjects(projects) {
        this.projects = projects;
    }

    setTasks(tasks) {
        this.tasks = tasks;
    }
}

// Initialize the TaskManager
window.taskManager = new TaskManager(); 