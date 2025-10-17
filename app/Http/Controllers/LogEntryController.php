<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogEntry;

class LogEntryController extends Controller
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
        $logEntries = LogEntry::where('user_id', auth()->id())
                             ->orderBy('date', 'asc')
                             ->paginate(10);
        
        return view('log-entries.index', compact('logEntries'));
    }

    /**
     * $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // student
            $table->date('date');
            $table->text('activity');
            $table->text('comment')->nullable();
            $table->boolean('supervisor_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users'); // supervisor
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        /*$data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
        ]);
        $data['user_id'] = auth()->id();
        LogEntry::create($data);
    
        return redirect()->back()->with('success','Entry saved.');*/

        $this->authorize('create', LogEntry::class);
        return view('log-entries.create');
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
            $existingEntry = LogEntry::where('user_id', auth()->id())
                                   ->whereDate('date', $data['date'])
                                   ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only create one log entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $data['user_id'] = auth()->id();
        LogEntry::create($data);
    
        return redirect()->route('log-entries.index')->with('success', 'Entry saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LogEntry $logEntry)
    {
        $this->authorize('view', $logEntry);
        return view('log-entries.show', compact('logEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogEntry $logEntry)
    {
        $this->authorize('update', $logEntry);
        return view('log-entries.edit', compact('logEntry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogEntry $logEntry)
    {
        $this->authorize('update', $logEntry);
        
        $data = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'comment' => 'nullable|string',
            'weekly_reflection_content' => 'nullable|string',
            'reflection_week_start' => 'nullable|date',
        ]);
        
        // Check daily limit for students when changing date
        if (auth()->user()->isStudent() && $logEntry->date->format('Y-m-d') !== $data['date']) {
            $existingEntry = LogEntry::where('user_id', auth()->id())
                                   ->whereDate('date', $data['date'])
                                   ->where('id', '!=', $logEntry->id)
                                   ->first();
            
            if ($existingEntry) {
                return redirect()->back()
                                 ->withInput()
                                 ->withErrors(['date' => 'You can only have one log entry per day. You already have an entry for ' . $data['date'] . '.']);
            }
        }
        
        $logEntry->update($data);
        
        return redirect()->route('log-entries.index')
                         ->with('success', 'Entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(LogEntry $logEntry)
    {
        $this->authorize('delete', $logEntry);
        return view('log-entries.delete', compact('logEntry'));
    }
    
    public function destroy(LogEntry $logEntry)
    {
        $this->authorize('delete', $logEntry);
        $logEntry->delete();
        
        return redirect()->route('log-entries.index')
                         ->with('success', 'Entry deleted successfully.');
    }
}
