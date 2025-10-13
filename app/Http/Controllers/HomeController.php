<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogEntry;
use App\Models\ProjectEntry;
use App\Models\WeeklyReflection;

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
        
        $stats = [
            'total_entries' => $user->logEntries()->count(),
            'approved_entries' => $user->logEntries()->where('supervisor_approved', true)->count(),
            'pending_entries' => $user->logEntries()->where('supervisor_approved', false)->count(),
            'total_project_entries' => $user->projectEntries()->count(),
            'approved_project_entries' => $user->projectEntries()->where('supervisor_approved', true)->count(),
            'pending_project_entries' => $user->projectEntries()->where('supervisor_approved', false)->count(),
            'total_reflections' => $user->weeklyReflections()->count(),
            'signed_reflections' => $user->weeklyReflections()->where('supervisor_signed', true)->count(),
            'pending_reflections' => $user->weeklyReflections()->where('supervisor_signed', false)->count(),
        ];
        
        $recentEntries = $user->logEntries()->latest()->limit(5)->get();
        $recentProjectEntries = $user->projectEntries()->latest()->limit(3)->get();
        $recentReflections = $user->weeklyReflections()->latest()->limit(3)->get();
        
        return view('home', compact('stats', 'recentEntries', 'recentProjectEntries', 'recentReflections'));
    }
}
