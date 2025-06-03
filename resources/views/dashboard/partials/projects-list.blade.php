@php
    $incompleteProjects = array_filter($projects, function($project) {
        return !($project['is_complete'] ?? false);
    });
    $completeProjects = array_filter($projects, function($project) {
        return $project['is_complete'] ?? false;
    });
@endphp

@if(count($incompleteProjects) > 0 || count($completeProjects) > 0)
    @if(count($incompleteProjects) > 0)
        @foreach($incompleteProjects as $project)
            <div class="project-item bg-[#C7B89B] rounded-lg p-4">
                <div class="flex flex-col gap-3">
                    <div class="flex-grow">
                        <div class="flex items-center gap-2">
                            <h4 class="project-title font-semibold text-[#2F2D2A] mb-2">{{ $project['name'] }}</h4>
                        </div>
                        <p class="project-description text-sm text-[#2F2D2A]">{{ $project['description'] ?? 'No description' }}</p>
                        <p class="text-xs text-[#2F2D2A] mt-2">
                            Created: {{ \Carbon\Carbon::parse($project['created_at'])->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="flex sm:justify-end space-x-2">
                        <a href="{{ route('dashboard', ['edit_project' => $project['id']]) }}" 
                           class="px-3 py-1 text-sm bg-[#2F2D2A] text-[#D2C5A5] rounded-md hover:bg-[#3D3D3D]">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('projects.toggle-complete', $project['id']) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="toggle-project-complete px-3 py-1 text-sm bg-[#2F2D2A] text-green-500 rounded-md hover:bg-[#3D3D3D]">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <a href="{{ route('dashboard', ['delete_project' => $project['id']]) }}" 
                           class="px-3 py-1 text-sm bg-[#2F2D2A] text-red-500 rounded-md hover:bg-[#3D3D3D]">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if(count($completeProjects) > 0)
        @if(count($incompleteProjects) > 0)
            <div class="my-4 border-t border-[#3D3D3D]"></div>
        @endif
        <div class="text-[#C7B89B] text-sm font-semibold mb-2">Completed Projects</div>
        @foreach($completeProjects as $project)
            <div class="project-item bg-[#C7B89B] rounded-lg p-4 opacity-75">
                <div class="flex flex-col gap-3">
                    <div class="flex-grow">
                        <div class="flex items-center gap-2">
                            <h4 class="project-title font-semibold text-[#2F2D2A] mb-2">{{ $project['name'] }}</h4>
                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Completed</span>
                        </div>
                        <p class="project-description text-sm text-[#2F2D2A]">{{ $project['description'] ?? 'No description' }}</p>
                        <p class="text-xs text-[#2F2D2A] mt-2">
                            Created: {{ \Carbon\Carbon::parse($project['created_at'])->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@else
    <p class="text-[#C7B89B]">No projects yet. Create your first project!</p>
@endif 