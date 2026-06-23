<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Campus;
use App\Models\Department;
use App\Models\JobCategory;
use App\Models\StaffMember;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Campuses
        $campuses = Campus::factory(3)->create();

        // 2. Seed Departments for each campus
        $departments = collect();
        foreach ($campuses as $campus) {
            $departments = $departments->merge(
                Department::factory(2)->create(['campus_id' => $campus->campus_id])
            );
        }

        // 3. Seed Job Categories
        JobCategory::factory(5)->create();

        // 4. Create Specific Test Users from DUMMY_CREDENTIALS.md
        // Staff Accounts
        $staffMembers = [
            ['name' => 'Admin User', 'email' => 'admin@university.ac.za', 'password' => 'Admin@123', 'role' => 'Admin'],
            ['name' => 'HOD User', 'email' => 'hod@university.ac.za', 'password' => 'HOD@123', 'role' => 'HOD'],
            ['name' => 'Dean Assistant', 'email' => 'dean@university.ac.za', 'password' => 'Dean@123', 'role' => 'Dean_Assistant'],
            ['name' => 'Coordinator User', 'email' => 'coord@university.ac.za', 'password' => 'Coord@123', 'role' => 'Coordinator'],
        ];

        foreach ($staffMembers as $index => $staffData) {
            $user = User::factory()->create([
                'name' => $staffData['name'],
                'email' => $staffData['email'],
                'password' => \Hash::make($staffData['password']),
            ]);

            StaffMember::factory()->create([
                'user_id' => $user->id,
                'full_name' => $staffData['name'],
                'role' => $staffData['role'],
                'department_id' => $departments->random()->department_id,
            ]);
        }

        // Student Accounts
        $students = [
            ['name' => 'Student One', 'email' => 'student1@university.ac.za', 'student_number' => 'ST001'],
            ['name' => 'Student Two', 'email' => 'student2@university.ac.za', 'student_number' => 'ST002'],
            ['name' => 'Student Three', 'email' => 'student3@university.ac.za', 'student_number' => 'ST003'],
        ];

        foreach ($students as $studentData) {
            $user = User::factory()->create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => \Hash::make('Student@123'),
            ]);

            Student::factory()->create([
                'user_id' => $user->id,
                'student_number' => $studentData['student_number'],
                'first_names' => explode(' ', $studentData['name'])[0],
                'surname' => explode(' ', $studentData['name'])[1] ?? 'User',
            ]);
        }

        // 5. Seed some random staff and students
        foreach ($departments as $dept) {
            // 2 staff per department
            StaffMember::factory(2)->create([
                'department_id' => $dept->department_id
            ]);
        }

        // Seed 20 random students
        Student::factory(20)->create();
    }
}
