<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\STBC4996Entry;
use App\Models\User;
use App\Notifications\NewEntrySubmitted;

class STBC4996EntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $STBC4996Entries = STBC4996Entry::where('user_id', auth()->id())
                                     ->orderBy('date', 'desc')
                                     ->paginate(10);

        return view('STBC4996.index', compact('STBC4996Entries'));
    }

    public function create()
    {
        $this->authorize('create', STBC4996Entry::class);
        return view('STBC4996.create');
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
            $existingEntry = STBC4996Entry::where('user_id', auth()->id())
                                       ->whereDate('date', $data['date'])
                                       ->first();

            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only create one project entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('entry-images', 'public');
        }
        unset($data['image']);

        $data['user_id'] = auth()->id();
        $entry = STBC4996Entry::create($data);

        $supervisors = User::where('role', 'supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new NewEntrySubmitted($entry, auth()->user(), 'project'));
        }

        return redirect()->route('STBC4996.index')
                         ->with('success', 'Project entry saved successfully.');
    }

    public function show(STBC4996Entry $stbc4996Entry)
    {
        $this->authorize('view', $stbc4996Entry);
        return view('STBC4996.show', compact('stbc4996Entry'));
    }

    public function edit(STBC4996Entry $stbc4996Entry)
    {
        $this->authorize('update', $stbc4996Entry);
        return view('STBC4996.edit', compact('stbc4996Entry'));
    }

    public function update(Request $request, STBC4996Entry $stbc4996Entry)
    {
        $this->authorize('update', $stbc4996Entry);

        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);

        if (auth()->user()->isStudent() && $stbc4996Entry->date->format('Y-m-d') !== $data['date']) {
            $existingEntry = STBC4996Entry::where('user_id', auth()->id())
                                       ->whereDate('date', $data['date'])
                                       ->where('id', '!=', $stbc4996Entry->id)
                                       ->first();

            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only have one project entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }

        if ($request->hasFile('image')) {
            if ($stbc4996Entry->image_path) {
                Storage::disk('public')->delete($stbc4996Entry->image_path);
            }
            $data['image_path'] = $request->file('image')->store('entry-images', 'public');
        }
        unset($data['image']);

        $stbc4996Entry->update($data);

        return redirect()->route('STBC4996.index')
                         ->with('success', 'Project entry updated successfully.');
    }

    public function delete(STBC4996Entry $stbc4996Entry)
    {
        $this->authorize('delete', $stbc4996Entry);
        return view('STBC4996.delete', compact('stbc4996Entry'));
    }

    public function destroy(STBC4996Entry $stbc4996Entry)
    {
        $this->authorize('delete', $stbc4996Entry);
        $stbc4996Entry->delete();

        return redirect()->route('STBC4996.index')
                         ->with('success', 'Project entry deleted successfully.');
    }
}
