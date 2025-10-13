<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyReflection;

class WeeklyReflectionController extends Controller
{
    public function __construct()
    {
        $this ->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reflections = WeeklyReflection::where('user_id', auth()->id())
                                     ->orderBy('week_start', 'desc')
                                     ->paginate(10);
        
        return view('reflections.index', compact('reflections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reflections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'week_start' => 'required|date',
            'content' => 'required|string|min:10',
        ]);
        
        $data['user_id'] = auth()->id();
        WeeklyReflection::create($data);
    
        return redirect()->route('reflections.index')
                         ->with('success', 'Weekly reflection saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WeeklyReflection $reflection)
    {
        $this->authorize('view', $reflection);
        return view('reflections.show', compact('reflection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeeklyReflection $reflection)
    {
        $this->authorize('update', $reflection);
        return view('reflections.edit', compact('reflection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeeklyReflection $reflection)
    {
        $this->authorize('update', $reflection);
        
        $data = $request->validate([
            'week_start' => 'required|date',
            'content' => 'required|string|min:10',
        ]);
        
        $reflection->update($data);
        
        return redirect()->route('reflections.index')
                         ->with('success', 'Weekly reflection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(WeeklyReflection $reflection)
    {
        $this->authorize('delete', $reflection);
        return view('reflections.delete', compact('reflection'));
    }
    
    public function destroy(WeeklyReflection $reflection)
    {
        $this->authorize('delete', $reflection);
        $reflection->delete();
        
        return redirect()->route('reflections.index')
                         ->with('success', 'Weekly reflection deleted successfully.');
    }
}
