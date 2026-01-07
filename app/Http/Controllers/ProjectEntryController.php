<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectEntry;
use App\Models\User;
use App\Notifications\NewEntrySubmitted;

class ProjectEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectEntries = ProjectEntry::where('user_id', auth()->id())
                                     ->orderBy('date', 'desc')
                                     ->paginate(10);
        
        return view('project-entries.index', compact('projectEntries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', ProjectEntry::class);
        return view('project-entries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);
        
        // Check daily limit for students
        if (auth()->user()->isStudent()) {
            $existingEntry = ProjectEntry::where('user_id', auth()->id())
                                       ->whereDate('date', $data['date'])
                                       ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only create one project entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $data['user_id'] = auth()->id();
        $entry = ProjectEntry::create($data);
    
        // Notify all supervisors about the new entry
        $supervisors = User::where('role', 'supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new NewEntrySubmitted($entry, auth()->user(), 'project'));
        }

        return redirect()->route('project-entries.index')
                         ->with('success', 'Project entry saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectEntry $projectEntry)
    {
        $this->authorize('view', $projectEntry);
        return view('project-entries.show', compact('projectEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectEntry $projectEntry)
    {
        $this->authorize('update', $projectEntry);
        return view('project-entries.edit', compact('projectEntry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectEntry $projectEntry)
    {
        $this->authorize('update', $projectEntry);
        
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);
        
        // Check daily limit for students when changing date
        if (auth()->user()->isStudent() && $projectEntry->date->format('Y-m-d') !== $data['date']) {
            $existingEntry = ProjectEntry::where('user_id', auth()->id())
                                       ->whereDate('date', $data['date'])
                                       ->where('id', '!=', $projectEntry->id)
                                       ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only have one project entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $projectEntry->update($data);
        
        return redirect()->route('project-entries.index')
                         ->with('success', 'Project entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(ProjectEntry $projectEntry)
    {
        $this->authorize('delete', $projectEntry);
        return view('project-entries.delete', compact('projectEntry'));
    }
    
    public function destroy(ProjectEntry $projectEntry)
    {
        $this->authorize('delete', $projectEntry);
        $projectEntry->delete();
        
        return redirect()->route('project-entries.index')
                         ->with('success', 'Project entry deleted successfully.');
    }
}
