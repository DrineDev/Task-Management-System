@php
    $showAddTaskModal = isset($addTask) && $addTask;
    
    // Filter incomplete projects using the same logic as projects-list.blade.php
    $incompleteProjects = array_filter($projects, function($project) {
        return !($project['is_complete'] ?? false);
    });
@endphp
@if($showAddTaskModal)
<!-- Add Task Modal -->
<div id="addTaskModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-full max-w-md">
        <form action="{{ route('tasks.store') }}" method="POST" id="addTaskForm">
            @csrf
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                    <i class="fas fa-times text-xl"></i>
                </a>
                <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Create New Task</h2>
                <div></div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="taskTitle" class="block text-[#C7B89B] text-sm font-bold mb-2">Title</label>
                    <input type="text" id="taskTitle" name="title" placeholder="Write Here" required
                           class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                </div>
                <div>
                    <label for="taskDescription" class="block text-[#C7B89B] text-sm font-bold mb-2">Description</label>
                    <textarea id="taskDescription" name="description" placeholder="Write Here"
                              class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]"></textarea>
                </div>
                <div>
                    <label for="projectSelect" class="block text-[#C7B89B] text-sm font-bold mb-2">Project</label>
                    <div class="relative">
                        <select id="projectSelect" name="project_id" required
                                class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                            <option value="">Select a project</option>
                            @if(count($incompleteProjects) > 0)
                                @foreach($incompleteProjects as $project)
                                    <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        @if(count($incompleteProjects) === 0)
                            <div class="mt-2 text-[#D2C5A5] text-sm">
                                No active projects available. 
                                <a href="{{ route('dashboard', ['add_project' => true]) }}" class="text-[#C7B89B] hover:underline">
                                    Create a project first
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <label for="deadline" class="block text-[#C7B89B] text-sm font-bold mb-2">Deadline</label>
                    <div class="relative">
                        <input type="date" id="deadline" name="deadline" required
                               min="{{ date('Y-m-d') }}"
                               class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                    </div>
                </div>
                <div>
                    <label class="block text-[#C7B89B] text-sm font-bold mb-2">Priority</label>
                    <div class="flex items-center space-x-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="1" class="hidden peer">
                            <div class="peer-checked:bg-[#2F2D2A] peer-checked:text-[#D2C5A5] bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center">
                                <i class="fas fa-minus mr-1"></i> Low
                            </div>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="2" checked class="hidden peer">
                            <div class="peer-checked:bg-[#2F2D2A] peer-checked:text-[#D2C5A5] bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center">
                                <i class="fas fa-equals mr-1"></i> Normal
                            </div>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="3" class="hidden peer">
                            <div class="peer-checked:bg-[#2F2D2A] peer-checked:text-[#D2C5A5] bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center">
                                <i class="fas fa-exclamation mr-1"></i> High
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" id="submitTask" class="w-full bg-[#D2C5A5] text-[#2F2D2A] rounded-xl py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addTaskForm');
    const submitButton = document.getElementById('submitTask');
    const projectSelect = document.getElementById('projectSelect');

    // Set minimum date for deadline to today
    const deadlineInput = document.getElementById('deadline');
    deadlineInput.min = new Date().toISOString().split('T')[0];

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button and show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
        
        // Submit the form
        form.submit();
    });

    // Handle project selection
    projectSelect.addEventListener('change', function() {
        if (this.value === '') {
            this.classList.add('border-red-500');
        } else {
            this.classList.remove('border-red-500');
        }
    });
});
</script>
@endif 