<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Grade;
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

        // Create subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH101', 'description' => 'Basic Mathematics', 'credits' => 4, 'is_compulsory' => true],
            ['name' => 'English', 'code' => 'ENG101', 'description' => 'English Language', 'credits' => 3, 'is_compulsory' => true],
            ['name' => 'Science', 'code' => 'SCI101', 'description' => 'General Science', 'credits' => 4, 'is_compulsory' => true],
            ['name' => 'History', 'code' => 'HIST101', 'description' => 'World History', 'credits' => 3, 'is_compulsory' => true],
            ['name' => 'Geography', 'code' => 'GEO101', 'description' => 'Physical and Human Geography', 'credits' => 3, 'is_compulsory' => true],
            ['name' => 'Physical Education', 'code' => 'PE101', 'description' => 'Physical Education', 'credits' => 2, 'is_compulsory' => false],
            ['name' => 'Art', 'code' => 'ART101', 'description' => 'Visual Arts', 'credits' => 2, 'is_compulsory' => false],
            ['name' => 'Computer Science', 'code' => 'CS101', 'description' => 'Introduction to Computing', 'credits' => 3, 'is_compulsory' => false],
        ];

        $createdSubjects = [];
        foreach ($subjects as $subject) {
            $createdSubjects[] = Subject::create($subject);
        }

        // Create teachers
        $teacherNames = [
            'John Smith' => 'Mathematics',
            'Sarah Johnson' => 'English',
            'Michael Brown' => 'Science',
            'Emily Davis' => 'History',
            'David Wilson' => 'Geography',
            'Lisa Anderson' => 'Physical Education',
            'James Taylor' => 'Art',
            'Jennifer Martinez' => 'Computer Science',
        ];

        $teachers = [];
        foreach ($teacherNames as $name => $specialization) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@school.com';
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'phone' => '555-' . rand(1000, 9999),
                'address' => rand(100, 999) . ' Teacher Street',
            ]);

            $teachers[] = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'TCH' . str_pad(count($teachers) + 1, 4, '0', STR_PAD_LEFT),
                'date_of_joining' => now()->subYears(rand(1, 10))->format('Y-m-d'),
                'qualification' => ['B.Ed', 'M.Ed', 'Ph.D'][rand(0, 2)],
                'specialization' => $specialization,
                'status' => 'active',
            ]);
        }

        // Create classes
        $classes = [
            ['name' => 'Grade 1-A', 'grade_level' => 1, 'section' => 'A', 'academic_year' => '2024-2025', 'max_capacity' => 40],
            ['name' => 'Grade 1-B', 'grade_level' => 1, 'section' => 'B', 'academic_year' => '2024-2025', 'max_capacity' => 40],
            ['name' => 'Grade 2-A', 'grade_level' => 2, 'section' => 'A', 'academic_year' => '2024-2025', 'max_capacity' => 40],
            ['name' => 'Grade 3-A', 'grade_level' => 3, 'section' => 'A', 'academic_year' => '2024-2025', 'max_capacity' => 40],
            ['name' => 'Grade 4-A', 'grade_level' => 4, 'section' => 'A', 'academic_year' => '2024-2025', 'max_capacity' => 40],
            ['name' => 'Grade 5-A', 'grade_level' => 5, 'section' => 'A', 'academic_year' => '2024-2025', 'max_capacity' => 40],
        ];

        $createdClasses = [];
        foreach ($classes as $class) {
            $createdClasses[] = SchoolClass::create($class);
        }

        // Create students
        $firstNames = ['John', 'Emma', 'Michael', 'Olivia', 'William', 'Ava', 'James', 'Sophia', 'Benjamin', 'Isabella',
                       'Lucas', 'Mia', 'Henry', 'Charlotte', 'Alexander', 'Amelia', 'Daniel', 'Harper', 'Matthew', 'Evelyn'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];

        $students = [];
        $studentCount = 0;
        foreach ($createdClasses as $class) {
            // Create 30-35 students per class
            $numStudents = rand(30, 35);
            for ($i = 0; $i < $numStudents; $i++) {
                $studentCount++;
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $name = $firstName . ' ' . $lastName;

                $user = User::create([
                    'name' => $name,
                    'email' => strtolower($firstName . '.' . $lastName . $studentCount) . '@student.school.com',
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                    'phone' => '555-' . rand(1000, 9999),
                    'address' => rand(100, 999) . ' Student Avenue',
                ]);

                $student = Student::create([
                    'user_id' => $user->id,
                    'admission_number' => 'STU' . str_pad($studentCount, 5, '0', STR_PAD_LEFT),
                    'date_of_birth' => now()->subYears(5 + $class->grade_level)->subMonths(rand(0, 11))->format('Y-m-d'),
                    'gender' => ['male', 'female'][rand(0, 1)],
                    'parent_name' => 'Parent of ' . $name,
                    'parent_phone' => '555-' . rand(1000, 9999),
                    'parent_email' => strtolower('parent.' . $firstName . '.' . $lastName) . '@gmail.com',
                    'medical_info' => null,
                    'status' => 'active',
                ]);

                $students[] = $student;

                // Enroll student in the class
                Enrollment::create([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'academic_year' => '2024-2025',
                    'enrollment_date' => now()->subMonths(rand(1, 6))->format('Y-m-d'),
                    'status' => 'active',
                ]);

                // Create attendance records for the last 30 days
                for ($day = 0; $day < 30; $day++) {
                    $date = now()->subDays($day)->format('Y-m-d');
                    // 90% attendance rate
                    $status = (rand(1, 100) <= 90) ? 'present' : 'absent';

                    Attendance::create([
                        'student_id' => $student->id,
                        'class_id' => $class->id,
                        'date' => $date,
                        'status' => $status,
                        'remarks' => $status === 'absent' ? ['Sick', 'Family Emergency', null][rand(0, 2)] : null,
                    ]);
                }

                // Create grades for each subject
                foreach ($createdSubjects as $subject) {
                    // Midterm exam
                    $midtermMarks = rand(60, 100);
                    Grade::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'class_id' => $class->id,
                        'exam_type' => 'midterm',
                        'marks_obtained' => $midtermMarks,
                        'total_marks' => 100,
                        'grade' => $this->calculateGrade($midtermMarks),
                        'remarks' => $midtermMarks >= 80 ? 'Excellent' : ($midtermMarks >= 60 ? 'Good' : 'Needs Improvement'),
                    ]);

                    // Final exam
                    $finalMarks = rand(65, 100);
                    Grade::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'class_id' => $class->id,
                        'exam_type' => 'final',
                        'marks_obtained' => $finalMarks,
                        'total_marks' => 100,
                        'grade' => $this->calculateGrade($finalMarks),
                        'remarks' => $finalMarks >= 80 ? 'Excellent' : ($finalMarks >= 60 ? 'Good' : 'Needs Improvement'),
                    ]);
                }
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@school.com / password123');
        $this->command->info('Teachers: ' . count($teachers) . ' created');
        $this->command->info('Classes: ' . count($createdClasses) . ' created');
        $this->command->info('Students: ' . count($students) . ' created');
        $this->command->info('Subjects: ' . count($createdSubjects) . ' created');
    }

    private function calculateGrade($marks)
    {
        if ($marks >= 90) return 'A+';
        if ($marks >= 85) return 'A';
        if ($marks >= 80) return 'A-';
        if ($marks >= 75) return 'B+';
        if ($marks >= 70) return 'B';
        if ($marks >= 65) return 'B-';
        if ($marks >= 60) return 'C+';
        if ($marks >= 55) return 'C';
        if ($marks >= 50) return 'C-';
        if ($marks >= 45) return 'D';
        return 'F';
    }
}
