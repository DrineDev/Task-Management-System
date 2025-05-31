<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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
        
        // Get user's profile
        $profileResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/user_profiles', [
            'id' => 'eq.' . $user->id
        ]);

        $profile = $profileResponse->successful() && !empty($profileResponse->json()) 
            ? $profileResponse->json()[0] 
            : null;

        // Get tasks count by status
        $tasksResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
            'user_id' => 'eq.' . $user->id
        ]);

        $tasks = $tasksResponse->successful() ? $tasksResponse->json() : [];
        
        $stats = [
            'ongoing' => collect($tasks)->where('status', 'ongoing')->count(),
            'completed' => collect($tasks)->where('status', 'completed')->count(),
            'overdue' => collect($tasks)->where('status', 'overdue')->count(),
        ];

        // Get projects
        $projectsResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/projects', [
            'user_id' => 'eq.' . $user->id
        ]);

        $projects = $projectsResponse->successful() ? $projectsResponse->json() : [];

        return view('dashboard.dashboard', compact('user', 'profile', 'stats', 'projects', 'tasks'));
    }

    /**
     * Search tasks and projects
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->input('query');

        // Search tasks
        $tasksResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
            'user_id' => 'eq.' . $user->id,
            'or' => '(title.ilike.%' . $query . '%,description.ilike.%' . $query . '%)'
        ]);

        // Search projects
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
     * Get calendar data for tasks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarData()
    {
        $user = Auth::user();

        $tasksResponse = Http::withHeaders([
            'apikey' => config('services.supabase.key'),
            'Authorization' => 'Bearer ' . config('services.supabase.key'),
            'Content-Type' => 'application/json',
        ])->get(config('services.supabase.url') . '/rest/v1/tasks', [
            'user_id' => 'eq.' . $user->id,
            'select' => 'id,title,due_date,status'
        ]);

        $tasks = $tasksResponse->successful() ? $tasksResponse->json() : [];

        return response()->json($tasks);
    }
}
