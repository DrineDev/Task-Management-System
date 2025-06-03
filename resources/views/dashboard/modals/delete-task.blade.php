@php
    $showDeleteModal = isset($deleteTask) && $deleteTask;
@endphp
@if($showDeleteModal)
<!-- Delete Task Modal -->
<div id="deleteTaskModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                <i class="fas fa-times text-xl"></i>
            </a>
            <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Delete Task</h2>
            <div></div>
        </div>

        <div class="mb-6">
            <p class="text-[#C7B89B] text-lg">Are you sure you want to delete this task?</p>
            <p class="text-[#D2C5A5] font-bold mt-2">{{ $deleteTask['title'] }}</p>
        </div>

        <form action="{{ route('tasks.destroy', $deleteTask['id']) }}" method="POST" class="space-y-4">
            @csrf
            @method('DELETE')
            
            <div class="flex space-x-4">
                <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-600 text-white rounded-xl py-3 font-bold text-center hover:bg-gray-700 focus:outline-none focus:shadow-outline">
                    Cancel
                </a>
                <button type="submit" class="flex-1 bg-red-600 text-white rounded-xl py-3 font-bold hover:bg-red-700 focus:outline-none focus:shadow-outline">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>
@endif 