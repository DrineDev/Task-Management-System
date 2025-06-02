<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Get all projects for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/projects', [
            'user_id' => 'eq.' . $user->id
        ]);

        return $response->successful() ? $response->json() : [];
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        return view('dashboard.add-projects');
    }

    /**
     * Store a new project
     */
    public function store(Request $request)
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
            return redirect()->route('dashboard')->with('success', 'Project created successfully!');
        }

        return redirect()->back()->with('error', 'Failed to create project: ' . $response->body());
    }

    /**
     * Update a project
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();

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

        return redirect()->back()->with('error', 'Failed to update project: ' . $response->body());
    }

    /**
     * Delete a project
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $response = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json'
        ])->delete(config('services.supabase.url') . '/rest/v1/projects?id=eq.' . $id . '&user_id=eq.' . $user->id);

        if ($response->successful()) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete project: ' . $response->body()
        ], $response->status());
    }

    /**
     * Toggle project completion status and complete all associated tasks
     */
    public function toggleComplete($id)
    {
        try {
            $user = Auth::user();
            $now = now()->toIso8601String();

            // First, complete all tasks in the project
            $tasksResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch(config('services.supabase.url') . '/rest/v1/tasks?project_id=eq.' . $id . '&user_id=eq.' . $user->id, [
                'is_completed' => true,
                'updated_at' => $now
            ]);

            if (!$tasksResponse->successful()) {
                return response()->json(['success' => false, 'message' => 'Failed to complete project tasks'], 500);
            }

            // Then mark the project as complete
            $projectResponse = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch(config('services.supabase.url') . '/rest/v1/projects?id=eq.' . $id . '&user_id=eq.' . $user->id, [
                'is_complete' => true,
                'updated_at' => $now
            ]);

            if ($projectResponse->successful()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to update project status'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing a project
     */
    public function edit($id)
    {
        try {
            $user = Auth::user();

            // Get project data
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.key'),
                'Authorization' => 'Bearer ' . config('services.supabase.key'),
                'Content-Type' => 'application/json',
            ])->get(config('services.supabase.url') . '/rest/v1/projects', [
                'id' => 'eq.' . $id,
                'user_id' => 'eq.' . $user->id
            ]);

            if (!$response->successful() || empty($response->json())) {
                return redirect()->route('dashboard')->with('error', 'Project not found or access denied');
            }

            $project = $response->json()[0];

            return view('dashboard.edit-project', [
                'project' => $project
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in edit project', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Error loading project: ' . $e->getMessage());
        }
    }
} 