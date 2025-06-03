<!-- Edit Project Modal -->
<div id="editProjectModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-[#2F2D2A] rounded-3xl p-8 w-full max-w-md">
        <form action="{{ route('projects.update', $editProject['id']) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                    <i class="fas fa-times text-xl"></i>
                </a>
                <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Edit Project</h2>
                <div></div>
            </div>

            <div>
                <label for="editName" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Name</label>
                <input type="text" id="editName" name="name" required
                       class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg"
                       placeholder="Enter project name" value="{{ $editProject['name'] }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="editDescription" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Description</label>
                <textarea id="editDescription" name="description" rows="4"
                          class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg"
                          placeholder="Enter project description">{{ $editProject['description'] }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-[#D2C5A5] text-[#2F2D2A] rounded-xl py-3 font-bold hover:bg-[#C7B89B] focus:outline-none focus:shadow-outline">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div> 