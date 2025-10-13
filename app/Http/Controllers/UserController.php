<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this ->middleware('auth');
    }
    
    public function index()
    {
        $users = User::withCount(['logEntries', 'projectEntries'])
                    ->orderBy('name')
                    ->paginate(15);
        return view('users.index', compact('users'));
    }
    
    public function show(User $user)
    {
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
}
