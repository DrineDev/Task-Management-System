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
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Get user profile
            $profileResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/profiles', [
                'user_id' => 'eq.' . $user->id
            ]);

            $profile = $profileResponse->successful() && !empty($profileResponse->json()) ? $profileResponse->json()[0] : null;

            // Get tasks
            $tasksResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'user_id' => 'eq.' . $user->id,
                'order' => 'deadline.asc'
            ]);

            $tasks = $tasksResponse->successful() ? $tasksResponse->json() : [];

            // Get all projects for dashboard
            $projectsResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/projects', [
                'user_id' => 'eq.' . $user->id,
                'order' => 'created_at.desc'
            ]);

            $projects = $projectsResponse->successful() ? $projectsResponse->json() : [];

            // Get incomplete projects for task modals
            $incompleteProjectsResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/projects', [
                'user_id' => 'eq.' . $user->id,
                'is_completed' => 'eq.false',
                'order' => 'created_at.desc'
            ]);

            $incompleteProjects = $incompleteProjectsResponse->successful() ? $incompleteProjectsResponse->json() : [];

            // Calculate stats
            $stats = [
                'ongoing' => count(array_filter($tasks, function($task) {
                    return !($task['is_completed'] ?? false);
                })),
                'completed' => count(array_filter($tasks, function($task) {
                    return $task['is_completed'] ?? false;
                })),
                'overdue' => count(array_filter($tasks, function($task) {
                    return !($task['is_completed'] ?? false) && 
                           strtotime($task['deadline']) < strtotime('today');
                }))
            ];

            // Support for edit modal
            $editTask = null;
            $editProject = null;
            if ($request->has('edit_task')) {
                $editTaskId = $request->get('edit_task');
                $editTaskResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    'Content-Type' => 'application/json',
                ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                    'id' => 'eq.' . $editTaskId,
                    'user_id' => 'eq.' . $user->id,
                    'select' => 'id,title,description,deadline,priority,project_id,progress,is_completed'
                ]);
                $editTaskData = $editTaskResponse->successful() ? $editTaskResponse->json() : [];
                if (!empty($editTaskData)) {
                    $editTask = $editTaskData[0];
                    $editTask['deadline'] = date('Y-m-d', strtotime($editTask['deadline']));
                }
            }

            // Support for edit project modal
            if ($request->has('edit_project')) {
                $editProjectId = $request->get('edit_project');
                $editProjectResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    'Content-Type' => 'application/json',
                ])->get(config('services.supabase.url') . '/rest/v1/projects', [
                    'id' => 'eq.' . $editProjectId,
                    'user_id' => 'eq.' . $user->id
                ]);
                $editProjectData = $editProjectResponse->successful() ? $editProjectResponse->json() : [];
                if (!empty($editProjectData)) {
                    $editProject = $editProjectData[0];
                }
            }

            // Support for delete modal
            $deleteTask = null;
            $deleteProject = null;
            if ($request->has('delete_task')) {
                $deleteTaskId = $request->get('delete_task');
                $deleteTaskResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    'Content-Type' => 'application/json',
                ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                    'id' => 'eq.' . $deleteTaskId,
                    'user_id' => 'eq.' . $user->id,
                    'select' => 'id,title'
                ]);
                $deleteTaskData = $deleteTaskResponse->successful() ? $deleteTaskResponse->json() : [];
                if (!empty($deleteTaskData)) {
                    $deleteTask = $deleteTaskData[0];
                }
            }

            // Support for delete project modal
            if ($request->has('delete_project')) {
                $deleteProjectId = $request->get('delete_project');
                $deleteProjectResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.key'),
                    'Content-Type' => 'application/json',
                ])->get(config('services.supabase.url') . '/rest/v1/projects', [
                    'id' => 'eq.' . $deleteProjectId,
                    'user_id' => 'eq.' . $user->id
                ]);
                $deleteProjectData = $deleteProjectResponse->successful() ? $deleteProjectResponse->json() : [];
                if (!empty($deleteProjectData)) {
                    $deleteProject = $deleteProjectData[0];
                }
            }

            // Support for add task modal
            $addTask = $request->has('add_task');
            // Support for add project modal
            $addProject = $request->has('add_project');

            // Get projects for the add task modal (only incomplete ones)
            $projectsForTask = $incompleteProjects;

            return view('dashboard.dashboard', [
                'user' => $user,
                'profile' => $profile,
                'tasks' => $tasks,
                'projects' => $projects,
                'stats' => $stats,
                'editTask' => $editTask,
                'editProject' => $editProject,
                'deleteTask' => $deleteTask,
                'deleteProject' => $deleteProject,
                'addTask' => $addTask,
                'addProject' => $addProject,
                'projectsForTask' => $projectsForTask
            ]);

        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
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

    /**
     * Update task counters for a user
     */
    public function updateTaskCounters($userId)
    {
        try {
            // Get all tasks for the user
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'user_id' => 'eq.' . $userId
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch tasks');
            }

            $tasks = $response->json();

            // Calculate stats
            $stats = [
                'ongoing' => count(array_filter($tasks, function($task) {
                    return !($task['is_completed'] ?? false);
                })),
                'completed' => count(array_filter($tasks, function($task) {
                    return $task['is_completed'] ?? false;
                })),
                'overdue' => count(array_filter($tasks, function($task) {
                    return !($task['is_completed'] ?? false) && 
                           strtotime($task['deadline']) < strtotime('today');
                }))
            ];

            return $stats;

        } catch (\Exception $e) {
            \Log::error('Error updating task counters: ' . $e->getMessage());
            throw $e;
        }
    }
}
