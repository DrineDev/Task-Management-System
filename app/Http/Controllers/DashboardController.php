<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Update task counters
        $this->updateTaskCounters($user->id);

        // Get user's profile
        $profileResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/user_profiles', [
            'id' => 'eq.' . $user->id
        ]);

        $profile = null;
        if ($profileResponse->successful() && !empty($profileResponse->json())) {
            $profile = $profileResponse->json()[0];
        }

        // Get tasks count by status from user_profiles
        $stats = [
            'ongoing' => $profile['ongoing_tasks'] ?? 0,
            'completed' => $profile['completed_tasks'] ?? 0,
            'overdue' => $profile['overdue_tasks'] ?? 0,
        ];

        // Get tasks from TaskController
        $taskController = new TaskController();
        $tasks = $taskController->index();

        // Get projects from ProjectController
        $projectController = new ProjectController();
        $projects = $projectController->index();

        return view('dashboard.dashboard', compact('user', 'profile', 'stats', 'projects', 'tasks'));
    }

    /**
     * Search functionality
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        // Search in tasks
        $tasksResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
            'user_id' => 'eq.' . $user->id,
            'or' => '(title.ilike.%' . $query . '%,description.ilike.%' . $query . '%)'
        ]);

        // Search in projects
        $projectsResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/projects', [
            'user_id' => 'eq.' . $user->id,
            'or' => '(name.ilike.%' . $query . '%,description.ilike.%' . $query . '%)'
        ]);

        return response()->json([
            'tasks' => $tasksResponse->successful() ? $tasksResponse->json() : [],
            'projects' => $projectsResponse->successful() ? $projectsResponse->json() : []
        ]);
    }

    /**
     * Get calendar data
     */
    public function getCalendarData()
    {
        $user = Auth::user();

        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
            'user_id' => 'eq.' . $user->id,
            'select' => 'id,title,deadline,priority,is_completed',
            'order' => 'priority.desc'
        ]);

        if ($response->successful()) {
            $tasks = $response->json();
            $calendarData = [];

            foreach ($tasks as $task) {
                $date = date('Y-m-d', strtotime($task['deadline']));
                if (!isset($calendarData[$date])) {
                    $calendarData[$date] = [];
                }
                $calendarData[$date][] = [
                    'id' => $task['id'],
                    'title' => $task['title'],
                    'priority' => $task['priority'],
                    'is_completed' => $task['is_completed']
                ];
            }

            return response()->json($calendarData);
        }

        return response()->json([], 500);
    }

    /**
     * Store a new project
     */
    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        $now = now()->toIso8601String();

        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->post(config('services.supabase.url') . '/rest/v1/projects', [
            'id' => Str::uuid()->toString(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'user_id' => $user->id,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Project created successfully');
        }

        return redirect()->route('dashboard')->with('error', 'Failed to create project: ' . $response->body());
    }

    /**
     * Update a project
     */
    public function updateProject(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Fixed: Use query parameter for filtering, not in body
        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->patch(config('services.supabase.url') . '/rest/v1/projects?id=eq.' . $id . '&user_id=eq.' . $user->id, [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'updated_at' => now()->toIso8601String()
        ]);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Project updated successfully');
        }

        return redirect()->route('dashboard')->with('error', 'Failed to update project: ' . $response->body());
    }

    /**
     * Delete a project
     */
    public function deleteProject($id)
    {
        $user = Auth::user();

        // Fixed: Use query parameter for filtering
        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json'
        ])->delete(config('services.supabase.url') . '/rest/v1/projects?id=eq.' . $id . '&user_id=eq.' . $user->id);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Project deleted successfully');
        }

        return redirect()->route('dashboard')->with('error', 'Failed to delete project: ' . $response->body());
    }

    /**
     * Store a new task
     */
    public function storeTask(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'deadline' => 'required|date',
                'priority' => 'required|integer|min:1|max:3'
            ]);

            $user = Auth::user();
            $now = now()->toIso8601String();

            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->post(config('services.supabase.url') . '/rest/v1/tasks', [
                'id' => Str::uuid()->toString(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'deadline' => $validated['deadline'],
                'priority' => $validated['priority'],
                'user_id' => $user->id,
                'created_at' => $now
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'task' => $response->json()[0]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create task: ' . $response->body()
            ], $response->status());
        } catch (\Exception $e) {
            \Log::error('Task creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a task
     */
    public function updateTask(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'deadline' => 'required|date',
            'priority' => 'required|integer|min:1|max:3',
            'project_id' => 'nullable|string',
            'status' => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100'
        ]);

        $user = Auth::user();

        // Fixed: Use query parameter for filtering
        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ])->patch(config('services.supabase.url') . '/rest/v1/tasks?id=eq.' . $id . '&user_id=eq.' . $user->id, [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'project_id' => $validated['project_id'] ?: null,
            'status' => $validated['status'] ?? 'pending',
            'progress' => $validated['progress'] ?? 0,
            'updated_at' => now()->toIso8601String()
        ]);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Task updated successfully');
        }

        return redirect()->route('dashboard')->with('error', 'Failed to update task: ' . $response->body());
    }

    /**
     * Delete a task
     */
    public function deleteTask($id)
    {
        $user = Auth::user();

        // Fixed: Use query parameter for filtering
        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json'
        ])->delete(config('services.supabase.url') . '/rest/v1/tasks?id=eq.' . $id . '&user_id=eq.' . $user->id);

        if ($response->successful()) {
            return redirect()->route('dashboard')->with('success', 'Task deleted successfully');
        }

        return redirect()->route('dashboard')->with('error', 'Failed to delete task: ' . $response->body());
    }

    private function updateTaskCounters($userId)
    {
        try {
            // Get all tasks for the user
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'user_id' => 'eq.' . $userId,
                'select' => 'is_completed,deadline'
            ]);

            if (!$response->successful()) {
                \Log::error('Failed to fetch tasks for counter update');
                return;
            }

            $tasks = $response->json();
            $now = now();

            // Calculate counters
            $ongoingTasks = 0;
            $completedTasks = 0;
            $overdueTasks = 0;

            foreach ($tasks as $task) {
                if ($task['is_completed']) {
                    $completedTasks++;
                } else {
                    $deadline = \Carbon\Carbon::parse($task['deadline']);
                    if ($deadline->isPast()) {
                        $overdueTasks++;
                    } else {
                        $ongoingTasks++;
                    }
                }
            }

            // Update user profile with new counters
            $updateResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ])->patch(config('services.supabase.url') . '/rest/v1/user_profiles?id=eq.' . $userId, [
                'ongoing_tasks' => $ongoingTasks,
                'completed_tasks' => $completedTasks,
                'overdue_tasks' => $overdueTasks,
                'updated_at' => $now->toIso8601String()
            ]);

            if (!$updateResponse->successful()) {
                \Log::error('Failed to update task counters in user profile');
            }
        } catch (\Exception $e) {
            \Log::error('Error updating task counters: ' . $e->getMessage());
        }
    }
}
