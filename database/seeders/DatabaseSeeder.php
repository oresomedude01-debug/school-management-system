<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@school.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '1234567890',
            'address' => '123 School Street',
        ]);

        // Create some sample subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH101', 'description' => 'Basic Mathematics', 'credits' => 4],
            ['name' => 'English', 'code' => 'ENG101', 'description' => 'English Language', 'credits' => 3],
            ['name' => 'Science', 'code' => 'SCI101', 'description' => 'General Science', 'credits' => 4],
            ['name' => 'History', 'code' => 'HIST101', 'description' => 'World History', 'credits' => 3],
            ['name' => 'Physical Education', 'code' => 'PE101', 'description' => 'Physical Education', 'credits' => 2],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
