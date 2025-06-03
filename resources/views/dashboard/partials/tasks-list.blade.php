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
            <div class="task-item bg-[#C7B89B] rounded-lg p-4 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="task-title font-semibold text-[#2F2D2A]">{{ $task['title'] }}</h4>
                        @if($task['priority'] == 3)
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">High</span>
                        @elseif($task['priority'] == 2)
                            <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">Medium</span>
                        @else
                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Low</span>
                        @endif
                    </div>
                    <p class="task-description text-sm text-[#2F2D2A]">{{ $task['description'] ?? 'No description' }}</p>
                    <p class="task-project text-xs text-[#2F2D2A] mt-1">
                        Due: {{ \Carbon\Carbon::parse($task['deadline'])->format('M d, Y') }}
                    </p>
                </div>
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
                    <a href="{{ route('dashboard', ['delete_task' => $task['id']]) }}" 
                       class="px-3 py-1 text-sm bg-[#2F2D2A] text-red-500 rounded-md hover:bg-[#3D3D3D]">
                        <i class="fas fa-times"></i>
                    </a>
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
            <div class="task-item bg-[#C7B89B] rounded-lg p-4 flex justify-between items-center opacity-75">
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="task-title font-semibold text-[#2F2D2A]">{{ $task['title'] }}</h4>
                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Completed</span>
                    </div>
                    <p class="task-description text-sm text-[#2F2D2A]">{{ $task['description'] ?? 'No description' }}</p>
                    <p class="task-project text-xs text-[#2F2D2A] mt-1">
                        Due: {{ \Carbon\Carbon::parse($task['deadline'])->format('M d, Y') }}
                    </p>
                </div>
            </div>
        @endforeach
    @endif
@else
    <p class="text-[#C7B89B]">No tasks yet. Create your first task!</p>
@endif 