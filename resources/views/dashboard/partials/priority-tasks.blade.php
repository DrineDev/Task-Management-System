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
                        <a href="{{ route('dashboard', ['edit_task' => $task['id']]) }}" 
                           class="px-3 py-1 text-sm bg-[#2F2D2A] text-[#D2C5A5] rounded-md hover:bg-[#3D3D3D]">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('tasks.complete', $task['id']) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm bg-[#2F2D2A] text-green-500 rounded-md hover:bg-[#3D3D3D]">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 text-sm bg-[#2F2D2A] text-red-500 rounded-md hover:bg-[#3D3D3D]"
                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
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
            <p class="text-xs text-[#2F2D2A]">
                Due: {{ \Carbon\Carbon::parse($task['deadline'])->format('M d, Y') }}
            </p>
        </div>
    @endforeach
@endif 