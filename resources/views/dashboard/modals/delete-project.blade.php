<!-- Delete Project Modal -->
<div id="deleteProjectModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                <i class="fas fa-times text-xl"></i>
            </a>
            <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Delete Project</h2>
            <div></div>
        </div>

        <div class="text-center">
            <h3 class="text-[#C7B89B] text-xl font-semibold mb-4">Are you sure you want to delete this project?</h3>
            <p class="text-[#D2C5A5] mb-8">This action cannot be undone. All tasks in this project will also be deleted.</p>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('dashboard') }}" 
                   class="bg-[#C7B89B] text-[#2D2A2A] rounded-xl px-6 py-2 font-bold hover:bg-[#B8A98B]">
                    Cancel
                </a>
                <form action="{{ route('projects.destroy', $deleteProject['id']) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white rounded-xl px-6 py-2 font-bold hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> 