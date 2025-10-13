<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LogEntry;
use App\Models\ProjectEntry;
use App\Models\WeeklyReflection;
use Carbon\Carbon;

class TestLogEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first student user
        $student = User::where('role', 'student')->first();
        
        if (!$student) {
            echo "No student user found. Run RoleBasedUserSeeder first.\n";
            return;
        }
        
        // Create some log entries (ensuring daily limit)
        $dates = [
            Carbon::today()->subDays(5),
            Carbon::today()->subDays(4),
            Carbon::today()->subDays(3),
            Carbon::today()->subDays(2),
            Carbon::today()->subDay(),
        ];
        
        foreach ($dates as $date) {
            LogEntry::create([
                'user_id' => $student->id,
                'date' => $date,
                'activity' => 'Daily work activity on ' . $date->format('M d, Y'),
                'comment' => 'Completed various tasks and learned new technologies.',
                'supervisor_approved' => $date->lt(Carbon::today()->subDays(2))
            ]);
        }
        
        // Create some project entries
        foreach ($dates as $index => $date) {
            ProjectEntry::create([
                'user_id' => $student->id,
                'date' => $date->copy()->addDay(), // Different dates to avoid conflicts
                'activity' => 'Project work ' . ($index + 1) . ': Development phase',
                'comment' => 'Made progress on the main project deliverables.',
                'supervisor_approved' => $date->lt(Carbon::today()->subDays(2))
            ]);
        }
        
        // Create some weekly reflections
        WeeklyReflection::create([
            'user_id' => $student->id,
            'week_start' => Carbon::today()->subWeek(),
            'content' => 'This week I learned about Laravel development and worked on various features. I improved my understanding of MVC architecture and database relationships.',
            'supervisor_signed' => true,
        ]);
        
        WeeklyReflection::create([
            'user_id' => $student->id,
            'week_start' => Carbon::today()->subWeeks(2),
            'content' => 'Focused on frontend development using Bootstrap and JavaScript. Created responsive user interfaces and improved user experience.',
            'supervisor_signed' => false,
        ]);
        
        echo "Created test data for student: {$student->name}\n";
        echo "- Log entries: 5\n";
        echo "- Project entries: 5\n";
        echo "- Weekly reflections: 2\n";
    }
}
