<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('admission_number', 'like', "%{$search}%");
        }

        $students = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'admission_number' => 'required|string|unique:students,admission_number',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'nullable|email',
            'medical_info' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'admission_number' => $validated['admission_number'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
                'parent_email' => $validated['parent_email'] ?? null,
                'medical_info' => $validated['medical_info'] ?? null,
                'status' => 'active',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'student' => $student->load('user')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Student $student)
    {
        return response()->json($student->load(['user', 'enrollments.schoolClass', 'grades', 'attendance']));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $student->user_id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'admission_number' => 'sometimes|string|unique:students,admission_number,' . $student->id,
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'parent_name' => 'sometimes|string',
            'parent_phone' => 'sometimes|string',
            'parent_email' => 'nullable|email',
            'medical_info' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive,graduated',
        ]);

        DB::beginTransaction();
        try {
            if (isset($validated['name']) || isset($validated['email']) || isset($validated['phone']) || isset($validated['address'])) {
                $student->user->update(array_filter([
                    'name' => $validated['name'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]));
            }

            $student->update(array_filter($validated, function($key) {
                return !in_array($key, ['name', 'email', 'phone', 'address']);
            }, ARRAY_FILTER_USE_KEY));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'student' => $student->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Student $student)
    {
        try {
            $student->user->delete(); // This will cascade delete the student
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student: ' . $e->getMessage()
            ], 500);
        }
    }
}
