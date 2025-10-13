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
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
