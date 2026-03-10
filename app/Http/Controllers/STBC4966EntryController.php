<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\STBC4966Entry;
use App\Models\User;
use App\Notifications\NewEntrySubmitted;

class STBC4966EntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $STBC4966Entries = STBC4966Entry::where('user_id', auth()->id())
                                     ->orderBy('date', 'desc')
                                     ->paginate(10);
        
        return view('STBC4966.index', compact('STBC4966Entries'));
    }

    public function create()
    {
        $this->authorize('create', STBC4966Entry::class);
        return view('STBC4966.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);
        
        if (auth()->user()->isStudent()) {
            $existingEntry = STBC4966Entry::where('user_id', auth()->id())
                                       ->whereDate('date', $data['date'])
                                       ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only create one project entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $data['user_id'] = auth()->id();
        $entry = STBC4966Entry::create($data);
    
        $supervisors = User::where('role', 'supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new NewEntrySubmitted($entry, auth()->user(), 'project'));
        }

        return redirect()->route('STBC4966.index')
                         ->with('success', 'Project entry saved successfully.');
    }

    public function show(STBC4966Entry $stbc4966Entry)
    {
        $this->authorize('view', $stbc4966Entry);
        return view('STBC4966.show', compact('stbc4966Entry'));
    }

    public function edit(STBC4966Entry $stbc4966Entry)
    {
        $this->authorize('update', $stbc4966Entry);
        return view('STBC4966.edit', compact('stbc4966Entry'));
    }

    public function update(Request $request, STBC4966Entry $stbc4966Entry)
    {
        $this->authorize('update', $stbc4966Entry);
        
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);
        
        if (auth()->user()->isStudent() && $stbc4966Entry->date->format('Y-m-d') !== $data['date']) {
            $existingEntry = STBC4966Entry::where('user_id', auth()->id())
                                       ->whereDate('date', $data['date'])
                                       ->where('id', '!=', $stbc4966Entry->id)
                                       ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only have one project entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $stbc4966Entry->update($data);
        
        return redirect()->route('STBC4966.index')
                         ->with('success', 'Project entry updated successfully.');
    }

    public function delete(STBC4966Entry $stbc4966Entry)
    {
        $this->authorize('delete', $stbc4966Entry);
        return view('STBC4966.delete', compact('stbc4966Entry'));
    }
    
    public function destroy(STBC4966Entry $stbc4966Entry)
    {
        $this->authorize('delete', $stbc4966Entry);
        $stbc4966Entry->delete();
        
        return redirect()->route('STBC4966.index')
                         ->with('success', 'Project entry deleted successfully.');
    }
}
