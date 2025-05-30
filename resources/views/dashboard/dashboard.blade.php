<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todo App</title>
    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <div class="header">
            <h1>My Todo List</h1>
            <a href="{{ route('profile.show') }}" class="profile-button">Profile</a>
        </div>

        <!-- Add Task Form -->
        <div>
            <h2>Add New Task</h2>
            <form method="POST" action="/tasks">
                @csrf
                <input type="text" name="title" placeholder="Task title" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="datetime-local" name="deadline">
                <select name="priority">
                    <option value="0">Low</option>
                    <option value="1" selected>Medium</option>
                    <option value="2">High</option>
                </select>
                <button type="submit">Add Task</button>
            </form>
        </div>

        <!-- Task Statistics -->
        <div>
            <h2>Task Summary</h2>
            <?php
            // These variables would come from your controller
            $totalTasks = count($tasks ?? []);
            $ongoingTasks = count(array_filter($tasks ?? [], function($task) {
                return !$task['is_completed'] && (empty($task['deadline']) || strtotime($task['deadline']) > time());
            }));
            $completedTasks = count(array_filter($tasks ?? [], function($task) {
                return $task['is_completed'];
            }));
            $overdueTasks = count(array_filter($tasks ?? [], function($task) {
                return !$task['is_completed'] && !empty($task['deadline']) && strtotime($task['deadline']) < time();
            }));
            ?>
            <p>Total Tasks: <?php echo $totalTasks; ?></p>
            <p>Ongoing: <?php echo $ongoingTasks; ?></p>
            <p>Completed: <?php echo $completedTasks; ?></p>
            <p>Overdue: <?php echo $overdueTasks; ?></p>
        </div>

        <!-- Task List -->
        <div>
            <h2>My Tasks</h2>
            <?php if(empty($tasks)): ?>
                <p>No tasks found. Add your first task!</p>
            <?php else: ?>
                <ul>
                    <?php foreach($tasks as $task): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                            <?php if(!empty($task['description'])): ?>
                                <p><?php echo htmlspecialchars($task['description']); ?></p>
                            <?php endif; ?>
                            <?php if(!empty($task['deadline'])): ?>
                                <p>Deadline: <?php echo date('M j, Y g:i A', strtotime($task['deadline'])); ?>
                                <?php if(!$task['is_completed'] && strtotime($task['deadline']) < time()): ?>
                                    (OVERDUE)
                                <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <p>Priority:
                                <?php
                                switch($task['priority']) {
                                    case 0: echo 'Low'; break;
                                    case 1: echo 'Medium'; break;
                                    case 2: echo 'High'; break;
                                }
                                ?>
                            </p>
                            <p>Status: <?php echo $task['is_completed'] ? 'Completed' : 'Pending'; ?></p>

                            <!-- Mark as complete form -->
                            <?php if(!$task['is_completed']): ?>
                                <form method="POST" action="/tasks/<?php echo $task['id']; ?>/complete" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Mark Complete</button>
                                </form>
                            <?php endif; ?>

                            <!-- Delete form -->
                            <form method="POST" action="/tasks/<?php echo $task['id']; ?>" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
