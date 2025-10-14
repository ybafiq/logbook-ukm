<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogEntry;
use App\Models\ProjectEntry;
use App\Models\User;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function dashboard()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }
        
        $pendingEntries = LogEntry::where('supervisor_approved', false)
                                 ->with('student')
                                 ->latest()
                                 ->paginate(10, ['*'], 'entries_page');
        
        $pendingProjectEntries = ProjectEntry::where('supervisor_approved', false)
                                           ->with('student')
                                           ->latest()
                                           ->paginate(10, ['*'], 'project_entries_page');
        
        $stats = [
            'pending_entries' => LogEntry::where('supervisor_approved', false)->count(),
            'pending_project_entries' => ProjectEntry::where('supervisor_approved', false)->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];
        
        return view('supervisor.dashboard', compact('pendingEntries', 'pendingProjectEntries', 'stats'));
    }
    
    public function approveEntry(LogEntry $entry)
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }

        $entry->update([
            'supervisor_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Entry approved successfully.');
    }
    
    public function approveProjectEntry(ProjectEntry $projectEntry)
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }

        $projectEntry->update([
            'supervisor_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Project entry approved successfully.');
    }
    
    
    public function pendingEntries()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }
        
        $entries = LogEntry::where('supervisor_approved', false)
                          ->with('student')
                          ->orderBy('date', 'desc')
                          ->paginate(15);
        
        return view('supervisor.pending-entries', compact('entries'));
    }
    
    public function pendingProjectEntries()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }
        
        $projectEntries = ProjectEntry::where('supervisor_approved', false)
                                    ->with('student')
                                    ->orderBy('date', 'desc')
                                    ->paginate(15);
        
        return view('supervisor.pending-project-entries', compact('projectEntries'));
    }
    
}
