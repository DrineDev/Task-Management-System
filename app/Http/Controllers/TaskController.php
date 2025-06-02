<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Get all tasks for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
            'user_id' => 'eq.' . $user->id,
            'order' => 'deadline.asc'
        ]);

        return $response->successful() ? $response->json() : [];
    }

    /**
     * Store a new task
     */
    public function store(Request $request)
    {
        try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
                'deadline' => 'required|date',
                'priority' => 'required|integer|in:1,2,3',
                'project_id' => 'nullable|string',
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
                'project_id' => $validated['project_id'] ?: null,
                'user_id' => $user->id,
                'progress' => 0,
                'is_completed' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            if ($response->successful()) {
                return redirect()->route('dashboard')->with('success', 'Task created successfully');
            }

            return redirect()->route('dashboard')->with('error', 'Failed to create task: ' . $response->body());

        } catch (\Exception $e) {
            \Log::error('Task creation error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error creating task: ' . $e->getMessage());
        }
    }

    /**
     * Update a task
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'deadline' => 'required|date',
                'priority' => 'required|integer|in:1,2,3',
                'project_id' => 'nullable|string',
            ]);

            $user = Auth::user();

            // First, verify the task belongs to the user
            $checkResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id
            ]);

            if (!$checkResponse->successful() || empty($checkResponse->json())) {
                return redirect()->route('dashboard')->with('error', 'Task not found or access denied');
            }

            // Update the task
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch(config('services.supabase.url') . '/rest/v1/tasks?id=eq.' . $id . '&user_id=eq.' . $user->id, [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'deadline' => $validated['deadline'],
                'priority' => $validated['priority'],
                'project_id' => $validated['project_id'] ?: null,
                'updated_at' => now()->toIso8601String()
            ]);

            if ($response->successful()) {
                return redirect()->route('dashboard')->with('success', 'Task updated successfully');
            }

            return redirect()->route('dashboard')->with('error', 'Failed to update task: ' . $response->body());

        } catch (\Exception $e) {
            \Log::error('Task update error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error updating task: ' . $e->getMessage());
        }
    }

    /**
     * Delete a task
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();

            // First, verify the task belongs to the user
            $checkResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id
            ]);

            if (!$checkResponse->successful() || empty($checkResponse->json())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            // Delete the task
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json'
            ])->delete(config('services.supabase.url') . '/rest/v1/tasks?id=eq.' . $id . '&user_id=eq.' . $user->id);

            if ($response->successful()) {
                // Update task counters
                $dashboardController = new DashboardController();
                $dashboardController->updateTaskCounters($user->id);
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task: ' . $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            \Log::error('Task deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update task progress
     */
    public function updateProgress(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'progress' => 'required|integer|min:0|max:100'
            ]);

            $user = Auth::user();

            // First, verify the task belongs to the user
            $checkResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id
            ]);

            if (!$checkResponse->successful() || empty($checkResponse->json())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            // Update the task progress and completion status
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch(config('services.supabase.url') . '/rest/v1/tasks?id=eq.' . $id . '&user_id=eq.' . $user->id, [
                'progress' => $validated['progress'],
                'is_completed' => $validated['progress'] === 100,
                'updated_at' => now()->toIso8601String()
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'task' => $response->json()[0]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task progress: ' . $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            \Log::error('Task progress update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating task progress: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle task completion status
     */
    public function toggleComplete($id)
    {
        try {
            $user = Auth::user();

            // First, verify the task belongs to the user
            $checkResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id
            ]);

            if (!$checkResponse->successful() || empty($checkResponse->json())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found or access denied'
                ], 404);
            }

            $task = $checkResponse->json()[0];
            $newStatus = $task['is_completed'] === true ? false : true;
            $newProgress = $newStatus === true ? 100 : 0;

            // Update the task status
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch(config('services.supabase.url') . '/rest/v1/tasks?id=eq.' . $id . '&user_id=eq.' . $user->id, [
                'is_completed' => $newStatus,
                'progress' => $newProgress,
                'updated_at' => now()->toIso8601String()
            ]);

            if ($response->successful()) {
                // Update task counters
                $dashboardController = new DashboardController();
                $dashboardController->updateTaskCounters($user->id);
                return response()->json([
                    'success' => true,
                    'task' => $response->json()[0]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle task completion: ' . $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            \Log::error('Task completion toggle error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error toggling task completion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single task
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            \Log::info('Fetching task', ['id' => $id, 'user_id' => $user->id]);

            $url = config('services.supabase.url') . '/rest/v1/tasks';
            $params = [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id,
                'select' => 'id,title,description,deadline,priority,project_id,progress,is_completed'
            ];

            \Log::info('Supabase request', ['url' => $url, 'params' => $params]);

            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get($url, $params);

            \Log::info('Supabase response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                \Log::error('Supabase request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch task: ' . $response->body()
                ], $response->status());
            }

            $data = $response->json();
            if (empty($data)) {
                \Log::warning('No task found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], 404);
            }

            $task = $data[0];
            // Format the deadline to YYYY-MM-DD for the date input
            $task['deadline'] = date('Y-m-d', strtotime($task['deadline']));
            
            \Log::info('Task found', ['task' => $task]);
            return response()->json($task);

        } catch (\Exception $e) {
            \Log::error('Task fetch error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing a task
     */
    public function edit($id)
    {
        try {
            $user = Auth::user();
            \Log::info('Fetching task for edit', ['id' => $id, 'user_id' => $user->id]);

            // Get user profile
            $profileResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/profiles', [
                'user_id' => 'eq.' . $user->id
            ]);

            $profile = $profileResponse->successful() && !empty($profileResponse->json()) ? $profileResponse->json()[0] : null;

            $url = config('services.supabase.url') . '/rest/v1/tasks';
            $params = [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id,
                'select' => 'id,title,description,deadline,priority,project_id,progress,is_completed'
            ];

            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get($url, $params);

            if (!$response->successful()) {
                \Log::error('Failed to fetch task for edit', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->route('dashboard')->with('error', 'Failed to fetch task data');
            }

            $data = $response->json();
            if (empty($data)) {
                return redirect()->route('dashboard')->with('error', 'Task not found');
            }

            $task = $data[0];
            // Format the deadline to YYYY-MM-DD for the date input
            $task['deadline'] = date('Y-m-d', strtotime($task['deadline']));
            
            // Get projects for the dropdown
            $projectsResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/projects', [
                'user_id' => 'eq.' . $user->id,
                'order' => 'created_at.desc'
            ]);

            $projects = $projectsResponse->successful() ? $projectsResponse->json() : [];

            return view('dashboard.edit-task', [
                'task' => $task,
                'projects' => $projects,
                'profile' => $profile,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in edit task', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Error loading task: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new task
     */
    public function create()
    {
        try {
            $user = Auth::user();
            
            // Get projects for the dropdown
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/projects', [
                'user_id' => 'eq.' . $user->id,
                'order' => 'created_at.desc'
            ]);

            $projects = $response->successful() ? $response->json() : [];

            return view('dashboard.add-task', [
                'projects' => $projects
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in create task form', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Error loading create task form: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for deleting a task
     */
    public function delete($id)
    {
        try {
            $user = Auth::user();

            // Get task data
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id
            ]);

            if (!$response->successful() || empty($response->json())) {
                return redirect()->route('dashboard')->with('error', 'Task not found or access denied');
            }

            $task = $response->json()[0];

            return view('dashboard.delete-task', [
                'task' => $task
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in delete task form', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Error loading delete task form: ' . $e->getMessage());
        }
    }

    /**
     * Mark a task as complete
     */
    public function complete($id)
    {
        try {
            $user = Auth::user();
            
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal'
            ])->patch(config('services.supabase.url') . '/rest/v1/tasks?id=eq.' . $id . '&user_id=eq.' . $user->id, [
                'is_completed' => true,
                'progress' => 100
            ]);

            if ($response->successful()) {
                // Update task counters
                $dashboardController = new DashboardController();
                $dashboardController->updateTaskCounters($user->id);
                return response()->json(['success' => true]);
            } else {
                throw new \Exception('Failed to complete task');
            }
        } catch (\Exception $e) {
            \Log::error('Error completing task', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
