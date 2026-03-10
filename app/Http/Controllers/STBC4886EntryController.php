<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\STBC4886Entry;
use App\Models\User;
use App\Notifications\NewEntrySubmitted;

class STBC4886EntryController extends Controller
{
    public function __construct()
    {
        $this ->middleware('auth');
    }

    public function index()
    {
        $STBC4886Entries = STBC4886Entry::where('user_id', auth()->id())
                             ->orderBy('date', 'desc')
                             ->paginate(10);
        
        return view('STBC4886.index', compact('STBC4886Entries'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', STBC4886Entry::class);
        return view('STBC4886.create');
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
            $existingEntry = STBC4886Entry::where('user_id', auth()->id())
                                   ->whereDate('date', $data['date'])
                                   ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only create one log entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $data['user_id'] = auth()->id();
        $entry = STBC4886Entry::create($data);
    
        $supervisors = User::where('role', 'supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new NewEntrySubmitted($entry, auth()->user(), 'log'));
        }

        return redirect()->route('STBC4886.index')->with('success', 'Entry saved successfully.');
    }

    public function show(STBC4886Entry $stbc4886Entry)
    {
        $this->authorize('view', $stbc4886Entry);
        return view('STBC4886.show', compact('stbc4886Entry'));
    }

    public function edit(STBC4886Entry $stbc4886Entry)
    {
        $this->authorize('update', $stbc4886Entry);
        return view('STBC4886.edit', compact('stbc4886Entry'));
    }

    public function update(Request $request, STBC4886Entry $stbc4886Entry)
    {
        $this->authorize('update', $stbc4886Entry);
        
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);
        
        if (auth()->user()->isStudent() && $stbc4886Entry->date->format('Y-m-d') !== $data['date']) {
            $existingEntry = STBC4886Entry::where('user_id', auth()->id())
                                   ->whereDate('date', $data['date'])
                                   ->where('id', '!=', $stbc4886Entry->id)
                                   ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only have one log entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $stbc4886Entry->update($data);
        
        return redirect()->route('STBC4886.index')
                         ->with('success', 'Entry updated successfully.');
    }

    public function delete(STBC4886Entry $stbc4886Entry)
    {
        $this->authorize('delete', $stbc4886Entry);
        return view('STBC4886.delete', compact('stbc4886Entry'));
    }
    
    public function destroy(STBC4886Entry $stbc4886Entry)
    {
        $this->authorize('delete', $stbc4886Entry);
        $stbc4886Entry->delete();
        
        return redirect()->route('STBC4886.index')
                         ->with('success', 'Entry deleted successfully.');
    }
}
