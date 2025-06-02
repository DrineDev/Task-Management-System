@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-[#2F2D2A] rounded-3xl p-8">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                    <i class="fas fa-arrow-left text-xl"></i> Back to Dashboard
                </a>
                <h2 class="text-[24px] font-semibold text-[#D2C5A5]">Delete Task</h2>
                <div></div>
            </div>

            <div class="text-center">
                <h3 class="text-[#C7B89B] text-xl font-semibold mb-4">Are you sure you want to delete this task?</h3>
                <p class="text-[#D2C5A5] mb-8">This action cannot be undone.</p>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="bg-[#C7B89B] text-[#2D2A2A] rounded-xl px-6 py-2 font-bold hover:bg-[#B8A98B]">
                        Cancel
                    </a>
                    <form action="{{ route('tasks.destroy', ['id' => $task['id']]) }}" method="POST" class="inline">
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