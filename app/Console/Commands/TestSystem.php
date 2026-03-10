<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\STBC4866Entry;
use App\Models\STBC4966Entry;
use App\Models\WeeklyReflection;

class TestSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Logbook System...');
        
        // Test database connections
        $this->info('📊 Database Status:');
        $this->line('  Users: ' . User::count());
        $this->line('  Log Entries: ' . STBC4866Entry::count());
        $this->line('  Project Entries: ' . STBC4966Entry::count());
        $this->line('  Weekly Reflections: ' . WeeklyReflection::count());
        
        // Test relationships
        $this->info('🔗 Testing Relationships:');
        $student = User::where('role', 'student')->first();
        if ($student) {
            $this->line('  Student: ' . $student->name);
            $this->line('  Log Entries: ' . $student->STBC4866Entries->count());
            $this->line('  Project Entries: ' . $student->STBC4966Entries->count());
            $this->line('  Weekly Reflections: ' . $student->weeklyReflections->count());
        }
        
        $supervisor = User::where('role', 'supervisor')->first();
        if ($supervisor) {
            $this->line('  Supervisor: ' . $supervisor->name);
        }
        
        // Test model functionality
        $this->info('⚙️  Testing Model Functions:');
        if ($student) {
            $this->line('  Student role check: ' . ($student->isStudent() ? '✅' : '❌'));
        }
        if ($supervisor) {
            $this->line('  Supervisor role check: ' . ($supervisor->isSupervisor() ? '✅' : '❌'));
        }
        
        // Test recent entries
        $stbc4866Entry = STBC4866Entry::first();
        if ($stbc4866Entry) {
            $this->line('  STBC4866 entry owner: ' . $stbc4866Entry->student->name);
            $this->line('  STBC4866 entry approved: ' . ($stbc4866Entry->supervisor_approved ? '✅' : '⏳'));
        }
        
        $stbc4966Entry = STBC4966Entry::first();
        if ($stbc4966Entry) {
            $this->line('  STBC4966 entry owner: ' . $stbc4966Entry->student->name);
            $this->line('  STBC4966 entry approved: ' . ($stbc4966Entry->supervisor_approved ? '✅' : '⏳'));
        }
        
        $reflection = WeeklyReflection::first();
        if ($reflection) {
            $this->line('  Reflection owner: ' . $reflection->student->name);
            $this->line('  Reflection signed: ' . ($reflection->supervisor_signed ? '✅' : '⏳'));
        }
        
        $this->info('✅ System test completed!');
        $this->line('');
        $this->info('🚀 Your logbook system is ready!');
        $this->line('   Student login: student@ukm.edu.my / password');
        $this->line('   Supervisor login: supervisor@ukm.edu.my / password');
        
        return 0;
    }
}
