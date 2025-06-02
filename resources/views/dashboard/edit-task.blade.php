@extends('layouts.app')

@section('content')
<div class="col-span-1 md:col-span-3 flex items-center justify-center pb-32">
    <div class="w-full max-w-2xl">
        <div class="bg-[#3D3D3D] rounded-3xl p-8">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                    <i class="fas fa-arrow-left text-xl"></i> Back to Dashboard
                </a>
                <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Edit Task</h2>
                <div></div>
            </div>

            <form action="{{ route('tasks.update', ['id' => $task['id']]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="title" class="block text-[#C7B89B] text-sm font-bold mb-2">Task Title</label>
                    <input type="text" id="title" name="title" required value="{{ $task['title'] }}"
                           class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg"
                           placeholder="Enter task title">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-[#C7B89B] text-sm font-bold mb-2">Task Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg"
                              placeholder="Enter task description">{{ $task['description'] }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="project_id" class="block text-[#C7B89B] text-sm font-bold mb-2">Project</label>
                    <select id="project_id" name="project_id"
                            class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg">
                        <option value="">Select a project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project['id'] }}" {{ ($task['project_id'] ?? '') == $project['id'] ? 'selected' : '' }}>
                                {{ $project['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deadline" class="block text-[#C7B89B] text-sm font-bold mb-2">Deadline</label>
                    <input type="date" id="deadline" name="deadline" required value="{{ \Carbon\Carbon::parse($task['deadline'])->format('Y-m-d') }}"
                           class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg">
                    @error('deadline')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-[#C7B89B] text-sm font-bold mb-2">Priority</label>
                    <select id="priority" name="priority" required
                            class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg">
                        <option value="1" {{ $task['priority'] == 1 ? 'selected' : '' }}>Low</option>
                        <option value="2" {{ $task['priority'] == 2 ? 'selected' : '' }}>Normal</option>
                        <option value="3" {{ $task['priority'] == 3 ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="progress" class="block text-[#C7B89B] text-sm font-bold mb-2">Progress</label>
                    <div class="flex items-center space-x-4">
                        <input type="range" id="progress" name="progress" min="0" max="100" step="1" 
                               value="{{ $task['progress'] ?? 0 }}"
                               class="w-full h-2 bg-[#D2C5A5] rounded-lg appearance-none cursor-pointer"
                               oninput="updateProgressValue(this.value)">
                        <span id="progressValue" class="text-[#C7B89B] font-bold min-w-[3rem] text-right">{{ $task['progress'] ?? 0 }}%</span>
                    </div>
                    @error('progress')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="bg-[#C7B89B] text-[#2F2D2A] rounded-xl px-6 py-3 font-bold hover:bg-[#B8A98B] focus:outline-none focus:shadow-outline">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-[#D2C5A5] text-[#2F2D2A] rounded-xl px-6 py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
function updateProgressValue(value) {
    document.getElementById('progressValue').textContent = value + '%';
    
    // If progress is 100%, mark task as completed
    if (value == 100) {
        // You can add additional logic here to handle task completion
        console.log('Task completed!');
    }
}
</script>
@endsection 