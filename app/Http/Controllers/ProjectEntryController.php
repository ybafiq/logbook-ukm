<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectEntry;

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
        ]);
        
        $data['user_id'] = auth()->id();
        ProjectEntry::create($data);
    
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
        ]);
        
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
