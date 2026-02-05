<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogEntry;
use App\Models\ProjectEntry;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show signature management dashboard
     */
    public function manageSignatures()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin role required.');
        }
        
        // Get all entries with signatures
        $logEntriesWithSignatures = LogEntry::whereNotNull('supervisor_signature')
            ->with(['student', 'approver'])
            ->orderBy('approved_at', 'desc')
            ->paginate(10, ['*'], 'log_page');
            
        $projectEntriesWithSignatures = ProjectEntry::whereNotNull('supervisor_signature')
            ->with(['student', 'approver'])
            ->orderBy('approved_at', 'desc')
            ->paginate(10, ['*'], 'project_page');
        
        return view('admin.signatures', compact('logEntriesWithSignatures', 'projectEntriesWithSignatures'));
    }
    
    /**
     * Delete a signature from log entry
     */
    public function deleteLogSignature(LogEntry $entry)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin role required.');
        }
        
        // Delete the signature file if it exists
        if ($entry->supervisor_signature) {
            \Storage::disk('public')->delete($entry->supervisor_signature);
        }
        
        $entry->update([
            'supervisor_signature' => null,
            'supervisor_comment' => null
        ]);
        
        return redirect()->back()->with('success', 'Signature deleted successfully.');
    }
    
    /**
     * Delete a signature from project entry
     */
    public function deleteProjectSignature(ProjectEntry $entry)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin role required.');
        }
        
        // Delete the signature file if it exists
        if ($entry->supervisor_signature) {
            \Storage::disk('public')->delete($entry->supervisor_signature);
        }
        
        $entry->update([
            'supervisor_signature' => null,
            'supervisor_comment' => null
        ]);
        
        return redirect()->back()->with('success', 'Signature deleted successfully.');
    }
}
