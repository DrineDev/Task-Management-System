<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Services\SupabaseService;

class TaskController extends Controller
{

    protected $supabase;

    public function __construct()
    {
        $this->supabase = app('supabase');
    }

    public function index()
    {
        $response = $this->supabase->from('tasks')
            ->select('*')
            ->eq('user_id', Auth::id())
            ->order('is_completed')
            ->order('deadline')
            ->get();

        $tasks = $response->json();

        return view('dashboard', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'priority' => 'integer|between:0,2',
        ]);

        $this->supabase->from('tasks')
            ->insert([
                ...$validated,
                'user_id' => Auth::id(),
                'is_completed' => false,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString()
            ]);

        return redirect()->route('dashboard')->with('success', 'Task added successfully!');
    }

    public function complete($taskId)
    {
        $this->supabase->from('tasks')
            ->update(['is_completed' => true])
            ->eq('id', $taskId)
            ->eq('user_id', Auth::id())
            ->patch();

        return redirect()->route('dashboard')->with('success', 'Task marked as complete!');
    }

    public function destroy($taskId)
    {
        $this->supabase->from('tasks')
            ->eq('id', $taskId)
            ->eq('user_id', Auth::id())
            ->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted successfully');
    }
}
