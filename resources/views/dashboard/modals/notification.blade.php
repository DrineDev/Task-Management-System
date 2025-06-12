@props(['tasks'])

@php
    $upcomingDeadlines = array_filter($tasks, function($task) {
        return !($task['is_completed'] ?? false) && 
               isset($task['deadline']) && 
               strtotime($task['deadline']) > time() && 
               (strtotime($task['deadline']) - time()) <= 86400; // Within 24 hours
    });

    $overdueTasks = array_filter($tasks, function($task) {
        return !($task['is_completed'] ?? false) && 
               isset($task['deadline']) && 
               strtotime($task['deadline']) < time();
    });

    $notifications = [];
    
    // Add upcoming deadline notifications
    foreach ($upcomingDeadlines as $task) {
        $deadline = \Carbon\Carbon::parse($task['deadline']);
        $notifications[] = [
            'type' => 'deadline',
            'title' => 'Upcoming Deadline',
            'message' => "Task '{$task['title']}' is due in " . $deadline->diffForHumans(),
            'created_at' => now(),
            'action_url' => route('dashboard', ['edit_task' => $task['id']])
        ];
    }
    
    // Add overdue task notifications
    foreach ($overdueTasks as $task) {
        $deadline = \Carbon\Carbon::parse($task['deadline']);
        $notifications[] = [
            'type' => 'warning',
            'title' => 'Overdue Task',
            'message' => "Task '{$task['title']}' is overdue by " . $deadline->diffForHumans(),
            'created_at' => now(),
            'action_url' => route('dashboard', ['edit_task' => $task['id']])
        ];
    }

    // Only show notifications if this is a direct dashboard load (no modal parameters)
    $showNotifications = empty(request()->query()) && count($notifications) > 0;
@endphp

<div id="notificationPanel" class="fixed top-20 right-4 w-96 bg-[#2F2D2A] rounded-xl shadow-lg z-50 {{ $showNotifications ? '' : 'hidden' }}">
    <div class="p-4 border-b border-[#3D3D3D]">
        <div class="flex justify-between items-center">
            <h3 class="text-[#C7B89B] text-lg font-semibold">Notifications</h3>
            <button onclick="hideNotification()" class="text-[#D2C5A5] hover:text-[#C7B89B]">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    
    <div class="max-h-96 overflow-y-auto">
        @forelse($notifications as $notification)
            <div class="p-4 border-b border-[#3D3D3D] hover:bg-[#3D3D3D] transition-colors">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        @if($notification['type'] === 'deadline')
                            <span class="text-2xl">📅</span>
                        @elseif($notification['type'] === 'warning')
                            <span class="text-2xl">⚠️</span>
                        @else
                            <span class="text-2xl">ℹ️</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[#C7B89B] font-medium">{{ $notification['title'] }}</h4>
                        <p class="text-[#D2C5A5] text-sm mt-1">{{ $notification['message'] }}</p>
                        <div class="mt-2">
                            <a href="{{ $notification['action_url'] }}" class="text-[#C7B89B] text-sm hover:underline">
                                View Task
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-[#D2C5A5]">
                No notifications at the moment
            </div>
        @endforelse
    </div>
</div>

<style>
    #notificationPanel {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<script>
    function hideNotification() {
        const panel = document.getElementById('notificationPanel');
        panel.classList.add('hidden');
    }

    // Auto-hide notifications after 10 seconds if they are visible
    document.addEventListener('DOMContentLoaded', function() {
        const panel = document.getElementById('notificationPanel');
        if (!panel.classList.contains('hidden')) {
            setTimeout(() => {
                panel.classList.add('hidden');
            }, 10000);
        }
    });
</script>
