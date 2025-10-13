<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PDF;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $user = auth()->user();
        $query = User::query();
        
        // Role-based filtering
        if ($user->isStudent()) {
            // Students can only see themselves
            $query->where('id', $user->id);
        } elseif ($user->isSupervisor()) {
            // Supervisors can see students only
            $query->where('role', 'student');
        }
        // Admins can see all users (no additional filtering)
        
        $users = $query->withCount(['logEntries', 'projectEntries'])
                      ->orderBy('name')
                      ->paginate(15);
                      
        return view('users.index', compact('users'));
    }
    
    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $user->loadCount(['logEntries', 'projectEntries']);
        $recentEntries = $user->logEntries()->latest()->limit(5)->get();
        $recentProjectEntries = $user->projectEntries()->latest()->limit(3)->get();
        
        return view('users.show', compact('user', 'recentEntries', 'recentProjectEntries'));
    }
    
    public function profile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'matric_no' => 'required|string|unique:users,matric_no,' . $user->id,
            'workplace' => 'nullable|string|max:255',
        ]);
        
        $user->update($data);
        
        return redirect()->route('users.profile')
                         ->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Admin-only methods for user management
     */
    
    public function create()
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }
    
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'matric_no' => 'required|string|unique:users,matric_no',
            'workplace' => 'nullable|string|max:255',
            'role' => 'required|in:student,supervisor,admin',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        
        return redirect()->route('users.index')
                         ->with('success', 'User created successfully.');
    }
    
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'matric_no' => 'required|string|unique:users,matric_no,' . $user->id,
            'workplace' => 'nullable|string|max:255',
            'role' => 'required|in:student,supervisor,admin',
        ]);
        
        // Only allow password update if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $data['password'] = bcrypt($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully.');
    }
    
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        $user->delete(); // Soft delete
        
        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully.');
    }
    
    public function trashed()
    {
        $this->authorize('viewTrashed', User::class);
        
        $users = User::onlyTrashed()
                    ->withCount(['logEntries', 'projectEntries'])
                    ->orderBy('deleted_at', 'desc')
                    ->paginate(15);
                    
        return view('users.trashed', compact('users'));
    }
    
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $user);
        
        $user->restore();
        
        return redirect()->route('users.trashed')
                         ->with('success', 'User restored successfully.');
    }
    
    public function forceDestroy($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $user);
        
        $user->forceDelete(); // Permanent delete
        
        return redirect()->route('users.trashed')
                         ->with('success', 'User permanently deleted.');
    }
    
    /**
     * Export student logbook to PDF
     */
    public function exportLogbook(Request $request)
    {
        $user = auth()->user();
        
        // Only students can export their own logbook
        if (!$user->isStudent()) {
            abort(403, 'Only students can export their logbook.');
        }
        
        // Get date range from request or default to all entries
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Get log entries with optional date filtering
        $logEntriesQuery = $user->logEntries()->orderBy('date', 'desc');
        if ($startDate) {
            $logEntriesQuery->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $logEntriesQuery->whereDate('date', '<=', $endDate);
        }
        $logEntries = $logEntriesQuery->get();
        
        // Get project entries with optional date filtering
        $projectEntriesQuery = $user->projectEntries()->orderBy('date', 'desc');
        if ($startDate) {
            $projectEntriesQuery->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $projectEntriesQuery->whereDate('date', '<=', $endDate);
        }
        $projectEntries = $projectEntriesQuery->get();
        
        // Get weekly reflections with optional date filtering
        $reflectionsQuery = $user->weeklyReflections()->orderBy('week_start', 'desc');
        if ($startDate) {
            $reflectionsQuery->whereDate('week_start', '>=', $startDate);
        }
        if ($endDate) {
            $reflectionsQuery->whereDate('week_start', '<=', $endDate);
        }
        $weeklyReflections = $reflectionsQuery->get();
        
        // Prepare data for PDF
        $data = [
            'user' => $user,
            'logEntries' => $logEntries,
            'projectEntries' => $projectEntries,
            'weeklyReflections' => $weeklyReflections,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()->format('F d, Y g:i A')
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('exports.logbook', $data)
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'defaultFont' => 'sans-serif'
                  ]);
        
        $filename = 'logbook_' . $user->matric_no . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Show export form for student logbook
     */
    public function showExportForm()
    {
        $user = auth()->user();
        
        // Only students can export their own logbook
        if (!$user->isStudent()) {
            abort(403, 'Only students can export their logbook.');
        }
        
        return view('users.export', compact('user'));
    }
}
