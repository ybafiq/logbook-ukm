<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function approveEntry(LogEntry $entry)
{
    $this->authorize('approve', $entry); // authorization policy to ensure user is supervisor

    $entry->update([
        'supervisor_approved' => true,
        'approved_by' => auth()->id(),
        'approved_at' => now()
    ]);

    return redirect()->back()->with('success','Entry approved.');
}

}
