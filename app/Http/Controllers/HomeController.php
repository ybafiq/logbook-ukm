<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogEntry;
use App\Models\ProjectEntry;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSupervisor()) {
            return redirect()->route('supervisor.dashboard');
        }
        
        // Calculate reflection statistics from both log entries and project entries
        $logReflections = $user->logEntries()->whereNotNull('weekly_reflection_content');
        $projectReflections = $user->projectEntries()->whereNotNull('weekly_reflection_content');
        
        $stats = [
            'total_entries' => $user->logEntries()->count(),
            'approved_entries' => $user->logEntries()->where('supervisor_approved', true)->count(),
            'pending_entries' => $user->logEntries()->where('supervisor_approved', false)->count(),
            'total_project_entries' => $user->projectEntries()->count(),
            'approved_project_entries' => $user->projectEntries()->where('supervisor_approved', true)->count(),
            'pending_project_entries' => $user->projectEntries()->where('supervisor_approved', false)->count(),
            'total_reflections' => $logReflections->count() + $projectReflections->count(),
            'signed_reflections' => $logReflections->where('reflection_supervisor_signed', true)->count() + 
                                   $projectReflections->where('reflection_supervisor_signed', true)->count(),
            'pending_reflections' => $logReflections->where('reflection_supervisor_signed', false)->count() + 
                                    $projectReflections->where('reflection_supervisor_signed', false)->count(),
        ];
        
        $recentEntries = $user->logEntries()->latest()->limit(5)->get();
        $recentProjectEntries = $user->projectEntries()->latest()->limit(3)->get();
        
        // Get recent reflections from both log entries and project entries
        $recentLogReflections = $user->logEntries()
            ->whereNotNull('weekly_reflection_content')
            ->latest()
            ->limit(2)
            ->get()
            ->map(function($entry) {
                return (object) [
                    'id' => $entry->id,
                    'type' => 'log',
                    'content' => $entry->weekly_reflection_content,
                    'week_start' => $entry->reflection_week_start,
                    'signed' => $entry->reflection_supervisor_signed,
                    'created_at' => $entry->created_at,
                    'date' => $entry->date
                ];
            });
            
        $recentProjectReflections = $user->projectEntries()
            ->whereNotNull('weekly_reflection_content')
            ->latest()
            ->limit(2)
            ->get()
            ->map(function($entry) {
                return (object) [
                    'id' => $entry->id,
                    'type' => 'project',
                    'content' => $entry->weekly_reflection_content,
                    'week_start' => $entry->reflection_week_start,
                    'signed' => $entry->reflection_supervisor_signed,
                    'created_at' => $entry->created_at,
                    'activity' => $entry->activity
                ];
            });
            
        $recentReflections = $recentLogReflections->concat($recentProjectReflections)
            ->sortByDesc('created_at')
            ->take(3);
        
        return view('home', compact('stats', 'recentEntries', 'recentProjectEntries', 'recentReflections'));
    }
}
