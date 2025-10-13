<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
