@props(['tasks'])

<?php
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

    // Show notifications if there are any
    $showNotifications = count($notifications) > 0;
?>

<div id="notificationPanel" class="fixed top-20 right-4 w-96 bg-[#2F2D2A] rounded-xl shadow-lg z-50 transform transition-transform duration-300 ease-in-out {{ $showNotifications ? '' : 'hidden' }} {{ $showNotifications ? '' : 'translate-x-full' }}">
    <div class="p-4 border-b border-[#3D3D3D]">
        <div class="flex justify-between items-center">
            <h3 class="text-[#C7B89B] text-lg font-semibold">Notifications</h3>
            <button onclick="hideNotification()" class="text-[#D2C5A5] hover:text-[#C7B89B] transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="max-h-96 overflow-y-auto">
        <?php if (count($notifications) > 0): ?>
            <?php foreach($notifications as $notification): ?>
                <div class="p-4 border-b border-[#3D3D3D] hover:bg-[#3D3D3D] transition-colors">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <?php if($notification['type'] === 'deadline'): ?>
                                <span class="text-2xl">📅</span>
                            <?php elseif($notification['type'] === 'warning'): ?>
                                <span class="text-2xl">⚠️</span>
                            <?php else: ?>
                                <span class="text-2xl">ℹ️</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[#C7B89B] font-medium"><?php echo $notification['title']; ?></h4>
                            <p class="text-[#D2C5A5] text-sm mt-1"><?php echo $notification['message']; ?></p>
                            <div class="mt-2">
                                <a href="<?php echo $notification['action_url']; ?>" class="text-[#C7B89B] text-sm hover:underline">
                                    View Task
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="p-4 text-center text-[#D2C5A5]">
                No notifications at the moment
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    let notificationTimeout;
    let isNotificationVisible = {{ $showNotifications ? 'true' : 'false' }};

    function showNotification() {
        console.log('Showing notification...');
        const panel = document.getElementById('notificationPanel');
        if (panel) {
            panel.classList.remove('hidden');
            // Force a reflow
            panel.offsetHeight;
            panel.classList.remove('translate-x-full');
            isNotificationVisible = true;
            
            // Reset the auto-hide timeout
            resetNotificationTimeout();
        }
    }

    function hideNotification() {
        console.log('Hiding notification...');
        const panel = document.getElementById('notificationPanel');
        if (panel) {
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                panel.classList.add('hidden');
                isNotificationVisible = false;
            }, 300);
        }
    }

    function resetNotificationTimeout() {
        if (notificationTimeout) {
            clearTimeout(notificationTimeout);
        }
        notificationTimeout = setTimeout(hideNotification, 10000);
    }

    // Show notifications automatically when dashboard loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');
        const panel = document.getElementById('notificationPanel');
        console.log('Notification panel:', panel);
        console.log('Show notifications:', {{ $showNotifications ? 'true' : 'false' }});
        
        if (panel && {{ $showNotifications ? 'true' : 'false' }}) {
            showNotification();
        }

        // Add click event listener to notification button
        const notificationBtn = document.getElementById('notificationBtn');
        if (notificationBtn) {
            console.log('Adding click event listener to notification button');
            notificationBtn.addEventListener('click', function(event) {
                console.log('Notification button clicked');
                event.stopPropagation(); // Prevent event from bubbling up
                if (isNotificationVisible) {
                    hideNotification();
                } else {
                    showNotification();
                }
            });
        }
    });

    // Close notification when clicking outside
    document.addEventListener('click', function(event) {
        const panel = document.getElementById('notificationPanel');
        const notificationBtn = document.getElementById('notificationBtn');
        
        if (panel && isNotificationVisible && 
            !panel.contains(event.target) && 
            notificationBtn && !notificationBtn.contains(event.target)) {
            hideNotification();
        }
    });
</script>
