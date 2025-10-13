<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleBasedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@logbook.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'matric_no' => 'ADM001',
            'workplace' => 'UKM Administration',
        ]);

        // Create Supervisor User
        User::create([
            'name' => 'Dr. Ahmad Supervisor',
            'email' => 'supervisor@logbook.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'matric_no' => 'SUP001',
            'workplace' => 'UKM Faculty',
        ]);

        // Create Student Users
        User::create([
            'name' => 'Ali Student',
            'email' => 'student1@logbook.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'matric_no' => 'P123456',
            'workplace' => 'Tech Company A',
        ]);

        User::create([
            'name' => 'Siti Student',
            'email' => 'student2@logbook.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'matric_no' => 'P123457',
            'workplace' => 'Tech Company B',
        ]);

        User::create([
            'name' => 'Ahmad Student',
            'email' => 'student3@logbook.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'matric_no' => 'P123458',
            'workplace' => 'Tech Company C',
        ]);

        echo "Created users with different roles:\n";
        echo "- Admin: admin@logbook.com / password\n";
        echo "- Supervisor: supervisor@logbook.com / password\n";
        echo "- Students: student1@logbook.com, student2@logbook.com, student3@logbook.com / password\n";
    }
}
