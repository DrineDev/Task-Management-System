@php
    $showEditTaskModal = isset($editTask) && $editTask;
@endphp
@if($showEditTaskModal)
<!-- Edit Task Modal -->
<div id="editTaskModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-full max-w-md">
        <form action="{{ route('tasks.update', $editTask['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                    <i class="fas fa-times text-xl"></i>
                </a>
                <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Edit Task</h2>
                <div></div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="editTaskTitle" class="block text-[#C7B89B] text-sm font-bold mb-2">Title</label>
                    <input type="text" id="editTaskTitle" name="title" value="{{ $editTask['title'] }}" required
                           class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                </div>
                <div>
                    <label for="editTaskDescription" class="block text-[#C7B89B] text-sm font-bold mb-2">Description</label>
                    <textarea id="editTaskDescription" name="description"
                              class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">{{ $editTask['description'] }}</textarea>
                </div>
                <div>
                    <label for="editProjectSelect" class="block text-[#C7B89B] text-sm font-bold mb-2">Project</label>
                    <select id="editProjectSelect" name="project_id" required
                            class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                        <option value="">Select a project</option>
                        @foreach($projectsForTask as $project)
                            <option value="{{ $project['id'] }}" {{ ($editTask['project_id'] ?? '') == $project['id'] ? 'selected' : '' }}>
                                {{ $project['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="editDeadline" class="block text-[#C7B89B] text-sm font-bold mb-2">Deadline</label>
                    <div class="relative">
                        <input type="date" id="editDeadline" name="deadline" value="{{ $editTask['deadline'] }}" required
                               class="shadow appearance-none border rounded-xl w-full py-2 px-3 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5]">
                    </div>
                </div>
                <div>
                    <label class="block text-[#C7B89B] text-sm font-bold mb-2">Priority</label>
                    <div class="flex items-center space-x-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="1" {{ $editTask['priority'] == 1 ? 'checked' : '' }} class="hidden peer">
                            <div class="peer-checked:bg-[#2F2D2A] peer-checked:text-[#D2C5A5] bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center">
                                <i class="fas fa-minus mr-1"></i> Low
                            </div>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="2" {{ $editTask['priority'] == 2 ? 'checked' : '' }} class="hidden peer">
                            <div class="peer-checked:bg-[#2F2D2A] peer-checked:text-[#D2C5A5] bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center">
                                <i class="fas fa-equals mr-1"></i> Normal
                            </div>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priority" value="3" {{ $editTask['priority'] == 3 ? 'checked' : '' }} class="hidden peer">
                            <div class="peer-checked:bg-[#2F2D2A] peer-checked:text-[#D2C5A5] bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-3 py-2 font-bold flex items-center">
                                <i class="fas fa-exclamation mr-1"></i> High
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-[#D2C5A5] text-[#2F2D2A] rounded-xl py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                    Update Task
                </button>
            </div>
        </form>
    </div>
</div>
@endif 