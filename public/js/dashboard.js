// Project Functions
function showAddProjectModal() {
    document.getElementById('addProjectModal').classList.remove('hidden');
}

function hideAddProjectModal() {
    document.getElementById('addProjectModal').classList.add('hidden');
}

function showEditProjectModal(projectId) {
    const project = projects.find(p => p.id === projectId);
    if (!project) return;

    const form = document.getElementById('editProjectForm');
    form.action = `/projects/${projectId}`;
    form.querySelector('[name="name"]').value = project.name;
    form.querySelector('[name="description"]').value = project.description;
    form.querySelector('[name="start_date"]').value = project.start_date;
    form.querySelector('[name="end_date"]').value = project.end_date;
    form.querySelector('[name="status"]').value = project.status;

    document.getElementById('editProjectModal').classList.remove('hidden');
}

function hideEditProjectModal() {
    document.getElementById('editProjectModal').classList.add('hidden');
}

function deleteProject(projectId) {
    if (!confirm('Are you sure you want to delete this project?')) return;

    fetch(`/projects/${projectId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            projects = projects.filter(p => p.id !== projectId);
            renderProjects();
            showNotification('Project deleted successfully', 'success');
        } else {
            showNotification(data.message || 'Failed to delete project', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to delete project', 'error');
    });
} 