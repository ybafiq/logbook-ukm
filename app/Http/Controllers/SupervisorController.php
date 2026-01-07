<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogEntry;
use App\Models\ProjectEntry;
use App\Models\User;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function dashboard()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }
        
        $pendingEntries = LogEntry::where('supervisor_approved', false)
                                 ->with('student')
                                 ->latest()
                                 ->paginate(10, ['*'], 'entries_page');
        
        $pendingProjectEntries = ProjectEntry::where('supervisor_approved', false)
                                           ->with('student')
                                           ->latest()
                                           ->paginate(10, ['*'], 'project_entries_page');
        
        $stats = [
            'pending_entries' => LogEntry::where('supervisor_approved', false)->count(),
            'pending_project_entries' => ProjectEntry::where('supervisor_approved', false)->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];
        
        return view('supervisor.dashboard', compact('pendingEntries', 'pendingProjectEntries', 'stats'));
    }
    
    public function approveEntry(Request $request, LogEntry $entry)
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }

        $request->validate([
            'supervisor_comment' => 'nullable|string',
            'signature' => 'nullable|string',
        ]);

        $data = [
            'supervisor_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'supervisor_comment' => $request->input('supervisor_comment')
        ];

        // Handle signature upload (optional - validate base64 + PNG magic header)
        if ($request->filled('signature')) {
            $signatureData = $request->input('signature');

            if (!preg_match('/^data:image\/png;base64,/', $signatureData)) {
                return redirect()->back()->with('error', 'Signature must be a PNG data URL.');
            }

            $image = preg_replace('/^data:image\/png;base64,/', '', $signatureData);
            $image = str_replace(' ', '+', $image);
            $decoded = base64_decode($image, true);

            if ($decoded === false) {
                return redirect()->back()->with('error', 'Invalid base64 signature data.');
            }

            // PNG magic bytes: \x89 P N G \r \n \x1a \n
            if (substr($decoded, 0, 8) !== "\x89PNG\x0D\x0A\x1A\x0A") {
                return redirect()->back()->with('error', 'Signature is not a valid PNG image.');
            }

            $imageName = 'signature_' . time() . '_' . $entry->id . '.png';
            $path = 'signatures/' . $imageName;

            $disk = \Storage::disk('public');

            if (!$disk->exists('signatures')) {
                $disk->makeDirectory('signatures');
            }

            $stored = $disk->put($path, $decoded);

            if (!$stored) {
                return redirect()->back()->with('error', 'Failed to save signature file.');
            }

            $data['supervisor_signature'] = $path;
        }

        $entry->update($data);

        $message = $request->filled('signature') 
            ? 'Entry approved and signed successfully.' 
            : 'Entry approved successfully.';

        return redirect()->back()->with('success', $message);
    }
    
    public function approveProjectEntry(Request $request, ProjectEntry $projectEntry)
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }

        $request->validate([
            'supervisor_comment' => 'nullable|string',
            'signature' => 'nullable|string',
        ]);

        $data = [
            'supervisor_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'supervisor_comment' => $request->input('supervisor_comment')
        ];

        // Handle signature upload (optional - validate base64 + PNG magic header)
        if ($request->filled('signature')) {
            $signatureData = $request->input('signature');

            if (!preg_match('/^data:image\/png;base64,/', $signatureData)) {
                return redirect()->back()->with('error', 'Signature must be a PNG data URL.');
            }

            $image = preg_replace('/^data:image\/png;base64,/', '', $signatureData);
            $image = str_replace(' ', '+', $image);
            $decoded = base64_decode($image, true);

            if ($decoded === false) {
                return redirect()->back()->with('error', 'Invalid base64 signature data.');
            }

            // PNG magic bytes: \x89 P N G \r \n \x1a \n
            if (substr($decoded, 0, 8) !== "\x89PNG\x0D\x0A\x1A\x0A") {
                return redirect()->back()->with('error', 'Signature is not a valid PNG image.');
            }

            $imageName = 'signature_' . time() . '_' . $projectEntry->id . '.png';
            $path = 'signatures/' . $imageName;

            $disk = \Storage::disk('public');

            if (!$disk->exists('signatures')) {
                $disk->makeDirectory('signatures');
            }

            $stored = $disk->put($path, $decoded);

            if (!$stored) {
                return redirect()->back()->with('error', 'Failed to save signature file.');
            }

            $data['supervisor_signature'] = $path;
        }

        $projectEntry->update($data);

        $message = $request->filled('signature') 
            ? 'Project entry approved and signed successfully.' 
            : 'Project entry approved successfully.';

        return redirect()->back()->with('success', $message);
    }
    
    
    public function pendingEntries()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }
        
        $entries = LogEntry::where('supervisor_approved', false)
                          ->with('student')
                          ->orderBy('date', 'desc')
                          ->paginate(15);
        
        return view('supervisor.pending-entries', compact('entries'));
    }
    
    public function pendingProjectEntries()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }
        
        $projectEntries = ProjectEntry::where('supervisor_approved', false)
                                    ->with('student')
                                    ->orderBy('date', 'desc')
                                    ->paginate(15);
        
        return view('supervisor.pending-project-entries', compact('projectEntries'));
    }
    
    public function markAllRead()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Access denied. Supervisor role required.');
        }
        
        auth()->user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
    
}
