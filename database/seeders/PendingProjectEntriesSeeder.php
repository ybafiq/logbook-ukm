<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProjectEntry;
use Carbon\Carbon;

class PendingProjectEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first student user
        $student = User::where('role', 'student')->first();
        
        if (!$student) {
            echo "No student user found. Creating one...\n";
            $student = User::create([
                'name' => 'Test Student',
                'email' => 'student@example.com',
                'password' => bcrypt('password'),
                'role' => 'student',
                'matric_no' => 'ST001',
                'workplace' => 'Test Workplace'
            ]);
        }

        // Create some pending project entries
        ProjectEntry::create([
            'user_id' => $student->id,
            'date' => Carbon::today(),
            'activity' => 'Developed user authentication system with Laravel Breeze',
            'comment' => 'Implemented login, registration, and password reset functionality',
            'supervisor_approved' => false
        ]);

        ProjectEntry::create([
            'user_id' => $student->id,
            'date' => Carbon::yesterday(),
            'activity' => 'Created database migrations for project management system',
            'comment' => 'Set up tables for projects, tasks, and user relationships',
            'supervisor_approved' => false
        ]);

        ProjectEntry::create([
            'user_id' => $student->id,
            'date' => Carbon::today()->subDays(2),
            'activity' => 'Implemented responsive UI components using Bootstrap 5',
            'comment' => 'Created dashboard layout and navigation components',
            'supervisor_approved' => false
        ]);

        echo "Created 3 pending project entries for testing.\n";
    }
}