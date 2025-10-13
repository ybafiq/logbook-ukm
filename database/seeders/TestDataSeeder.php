<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LogEntry;
use App\Models\ProjectEntry;
use App\Models\WeeklyReflection;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test student
        $student = User::updateOrCreate(
            ['email' => 'student@ukm.edu.my'],
            [
                'name' => 'Test Student',
                'matric_no' => 'A12345678',
                'email' => 'student@ukm.edu.my',
                'password' => Hash::make('password'),
                'role' => 'student',
                'workplace' => 'UKM Hospital',
            ]
        );

        // Create a test supervisor
        $supervisor = User::updateOrCreate(
            ['email' => 'supervisor@ukm.edu.my'],
            [
                'name' => 'Dr. Test Supervisor',
                'matric_no' => 'S12345678',
                'email' => 'supervisor@ukm.edu.my',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'workplace' => 'UKM Hospital',
            ]
        );

        // Create some test log entries
        LogEntry::create([
            'user_id' => $student->id,
            'date' => now()->subDays(2),
            'activity' => 'Attended morning rounds with Dr. Smith. Observed patient consultations and learned about treatment protocols for diabetes management.',
            'comment' => 'Very educational session. Learned about insulin dosage calculations.',
        ]);

        LogEntry::create([
            'user_id' => $student->id,
            'date' => now()->subDays(1),
            'activity' => 'Assisted in emergency department. Observed trauma cases and emergency procedures.',
            'comment' => 'Challenging but rewarding experience. Need to improve my clinical assessment skills.',
        ]);

        // Create some test project entries
        ProjectEntry::create([
            'user_id' => $student->id,
            'date' => now()->subDays(7),
            'activity' => 'Conducted research on diabetes management practices in rural healthcare settings. Collected data from 50 patients and analyzed treatment outcomes.',
            'comment' => 'Found that 60% of patients had better glucose control with community-based interventions.',
        ]);

        ProjectEntry::create([
            'user_id' => $student->id,
            'date' => now()->subDays(3),
            'activity' => 'Developed a mobile application prototype to help elderly patients remember their medication schedules. Integrated with calendar and notification systems.',
            'comment' => 'Successfully created a working prototype with basic reminder functionality.',
        ]);

        // Create a test weekly reflection
        WeeklyReflection::create([
            'user_id' => $student->id,
            'week_start' => now()->startOfWeek()->subWeek(),
            'content' => 'This week has been incredibly insightful. I have gained valuable experience in patient care and clinical decision-making. The medical team has been very supportive in my learning journey. I particularly enjoyed the cardiology rotation where I learned about ECG interpretation and cardiac catheterization procedures. Moving forward, I plan to focus more on developing my diagnostic skills and improving my patient communication abilities.',
        ]);

        echo "Test data created successfully!\n";
        echo "Student login: student@ukm.edu.my / password\n";
        echo "Supervisor login: supervisor@ukm.edu.my / password\n";
    }
}
