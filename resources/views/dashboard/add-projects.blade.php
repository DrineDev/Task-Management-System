@extends('layouts.app')

@section('content')
<div class="col-span-1 md:col-span-3 flex items-center justify-center pb-32">
    <div class="w-full max-w-2xl">
        <div class="bg-[#3D3D3D] rounded-3xl p-8">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                    <i class="fas fa-arrow-left text-xl"></i> Back to Dashboard
                </a>
                <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Add New Project</h2>
                <div></div>
            </div>

            <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Name</label>
                    <input type="text" id="name" name="name" required
                           class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg"
                           placeholder="Enter project name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-[#C7B89B] text-sm font-bold mb-2">Project Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="shadow appearance-none border rounded-xl w-full py-3 px-4 text-[#2F2D2A] leading-tight focus:outline-none focus:shadow-outline bg-[#D2C5A5] text-lg"
                              placeholder="Enter project description"></textarea>
                    @error('description')
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
                        Create Project
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
@endsection 