<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\STBC4866Entry;
use App\Models\User;
use App\Notifications\NewEntrySubmitted;

class STBC4866EntryController extends Controller
{
    public function __construct()
    {
        $this ->middleware('auth');
    }

    public function index()
    {
        $STBC4866Entries = STBC4866Entry::where('user_id', auth()->id())
                             ->orderBy('date', 'desc')
                             ->paginate(10);
        
        return view('STBC4866.index', compact('STBC4866Entries'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', STBC4866Entry::class);
        return view('STBC4866.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);

        if (auth()->user()->isStudent()) {
            $existingEntry = STBC4866Entry::where('user_id', auth()->id())
                                   ->whereDate('date', $data['date'])
                                   ->first();

            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only create one log entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('entry-images', 'public');
        }
        unset($data['image']);

        $data['user_id'] = auth()->id();
        $entry = STBC4866Entry::create($data);
    
        $supervisors = User::where('role', 'supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new NewEntrySubmitted($entry, auth()->user(), 'log'));
        }

        return redirect()->route('STBC4866.index')->with('success', 'Entry saved successfully.');
    }

    public function show(STBC4866Entry $stbc4866Entry)
    {
        $this->authorize('view', $stbc4866Entry);
        return view('STBC4866.show', compact('stbc4866Entry'));
    }

    public function edit(STBC4866Entry $stbc4866Entry)
    {
        $this->authorize('update', $stbc4866Entry);
        return view('STBC4866.edit', compact('stbc4866Entry'));
    }

    public function update(Request $request, STBC4866Entry $stbc4866Entry)
    {
        $this->authorize('update', $stbc4866Entry);
        
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);

        if (auth()->user()->isStudent() && $stbc4866Entry->date->format('Y-m-d') !== $data['date']) {
            $existingEntry = STBC4866Entry::where('user_id', auth()->id())
                                   ->whereDate('date', $data['date'])
                                   ->where('id', '!=', $stbc4866Entry->id)
                                   ->first();

            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only have one log entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }

        if ($request->hasFile('image')) {
            if ($stbc4866Entry->image_path) {
                Storage::disk('public')->delete($stbc4866Entry->image_path);
            }
            $data['image_path'] = $request->file('image')->store('entry-images', 'public');
        }
        unset($data['image']);

        $stbc4866Entry->update($data);
        
        return redirect()->route('STBC4866.index')
                         ->with('success', 'Entry updated successfully.');
    }

    public function delete(STBC4866Entry $stbc4866Entry)
    {
        $this->authorize('delete', $stbc4866Entry);
        return view('STBC4866.delete', compact('stbc4866Entry'));
    }
    
    public function destroy(STBC4866Entry $stbc4866Entry)
    {
        $this->authorize('delete', $stbc4866Entry);
        $stbc4866Entry->delete();
        
        return redirect()->route('STBC4866.index')
                         ->with('success', 'Entry deleted successfully.');
    }
}
