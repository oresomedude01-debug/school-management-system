<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StudentManagementController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = Student::query()->with(['user', 'registrationToken']);

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('admission_number', 'like', "%{$search}%")
                  ->orWhere('parent_email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by admission status
        if ($request->has('admission_status') && $request->admission_status !== 'all') {
            $query->where('admission_status', $request->admission_status);
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $students = $query->paginate(20);

        return response()->json([
            'success' => true,
            'students' => $students,
            'statistics' => $this->getStatistics()
        ]);
    }

    /**
     * Store a newly created student (without token)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',

            // Admission Status
            'admission_status' => 'required|in:provisional,fully_admitted',

            // Previous School Information
            'previous_school_name' => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string|max:500',
            'previous_class' => 'nullable|string|max:100',
            'transfer_reason' => 'nullable|string|max:500',
            'previous_result' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // Health & Allergy Information
            'allergies' => 'nullable|string|max:500',
            'medical_conditions' => 'nullable|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_medical_consent' => 'required|boolean',

            // Parent/Guardian 1 Information
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email|max:255|unique:users,email',
            'parent_phone' => 'required|string|max:20',
            'parent_password' => 'required|string|min:8',

            // Guardian 2 Information
            'guardian2_name' => 'nullable|string|max:255',
            'guardian2_relationship' => 'nullable|string|max:100',
            'guardian2_phone' => 'nullable|string|max:20',
            'guardian2_email' => 'nullable|email|max:255',
            'guardian2_occupation' => 'nullable|string|max:255',

            // Preferred Contact Method
            'preferred_contact_method' => 'required|in:email,phone,sms',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create parent/guardian user account
            $user = User::create([
                'name' => $request->parent_name,
                'email' => $request->parent_email,
                'password' => Hash::make($request->parent_password),
                'role' => 'parent'
            ]);

            // Handle file uploads
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            $previousResultPath = null;
            if ($request->hasFile('previous_result')) {
                $previousResultPath = $request->file('previous_result')->store('students/documents', 'public');
            }

            // Generate admission number
            $admissionNumber = Student::generateAdmissionNumber();

            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'admission_number' => $admissionNumber,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'address' => $request->address,
                'photo_path' => $photoPath,
                'enrollment_date' => now(),
                'admission_status' => $request->admission_status,

                // Previous School
                'previous_school_name' => $request->previous_school_name,
                'previous_school_address' => $request->previous_school_address,
                'previous_class' => $request->previous_class,
                'transfer_reason' => $request->transfer_reason,
                'previous_result_path' => $previousResultPath,

                // Health & Emergency
                'allergies' => $request->allergies,
                'medical_conditions' => $request->medical_conditions,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_medical_consent' => $request->emergency_medical_consent,

                // Parent/Guardian Info
                'parent_name' => $request->parent_name,
                'parent_email' => $request->parent_email,
                'parent_phone' => $request->parent_phone,

                // Guardian 2
                'guardian2_name' => $request->guardian2_name,
                'guardian2_relationship' => $request->guardian2_relationship,
                'guardian2_phone' => $request->guardian2_phone,
                'guardian2_email' => $request->guardian2_email,
                'guardian2_occupation' => $request->guardian2_occupation,

                // Additional
                'preferred_contact_method' => $request->preferred_contact_method,
                'status' => 'active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'student' => $student->load('user')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files if transaction failed
            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            if (isset($previousResultPath)) {
                Storage::disk('public')->delete($previousResultPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified student
     */
    public function show($id)
    {
        $student = Student::with(['user', 'registrationToken', 'enrollments.schoolClass'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'student' => $student
        ]);
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'admission_status' => 'required|in:provisional,fully_admitted',
            'status' => 'required|in:active,inactive,graduated,transferred',

            // Other fields
            'previous_school_name' => 'nullable|string|max:255',
            'previous_school_address' => 'nullable|string|max:500',
            'previous_class' => 'nullable|string|max:100',
            'transfer_reason' => 'nullable|string|max:500',
            'allergies' => 'nullable|string|max:500',
            'medical_conditions' => 'nullable|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_medical_consent' => 'required|boolean',

            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',

            'guardian2_name' => 'nullable|string|max:255',
            'guardian2_relationship' => 'nullable|string|max:100',
            'guardian2_phone' => 'nullable|string|max:20',
            'guardian2_email' => 'nullable|email|max:255',
            'guardian2_occupation' => 'nullable|string|max:255',

            'preferred_contact_method' => 'required|in:email,phone,sms',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check the form for errors',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($student->photo_path) {
                    Storage::disk('public')->delete($student->photo_path);
                }
                $student->photo_path = $request->file('photo')->store('students/photos', 'public');
            }

            // Handle previous result upload
            if ($request->hasFile('previous_result')) {
                if ($student->previous_result_path) {
                    Storage::disk('public')->delete($student->previous_result_path);
                }
                $student->previous_result_path = $request->file('previous_result')->store('students/documents', 'public');
            }

            // Update student
            $student->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'address' => $request->address,
                'admission_status' => $request->admission_status,
                'status' => $request->status,

                'previous_school_name' => $request->previous_school_name,
                'previous_school_address' => $request->previous_school_address,
                'previous_class' => $request->previous_class,
                'transfer_reason' => $request->transfer_reason,

                'allergies' => $request->allergies,
                'medical_conditions' => $request->medical_conditions,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_medical_consent' => $request->emergency_medical_consent,

                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,

                'guardian2_name' => $request->guardian2_name,
                'guardian2_relationship' => $request->guardian2_relationship,
                'guardian2_phone' => $request->guardian2_phone,
                'guardian2_email' => $request->guardian2_email,
                'guardian2_occupation' => $request->guardian2_occupation,

                'preferred_contact_method' => $request->preferred_contact_method,
            ]);

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
                'message' => 'Failed to update student',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Delete associated files
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }
        if ($student->previous_result_path) {
            Storage::disk('public')->delete($student->previous_result_path);
        }

        // Soft delete or hard delete based on your preference
        $student->update(['status' => 'inactive']);

        return response()->json([
            'success' => true,
            'message' => 'Student deactivated successfully'
        ]);
    }

    /**
     * Get student statistics
     */
    public function getStatistics()
    {
        return [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'provisional' => Student::where('admission_status', 'provisional')->count(),
            'fully_admitted' => Student::where('admission_status', 'fully_admitted')->count(),
            'male' => Student::where('gender', 'male')->count(),
            'female' => Student::where('gender', 'female')->count(),
            'enrolled_this_month' => Student::whereMonth('enrollment_date', now()->month)
                ->whereYear('enrollment_date', now()->year)
                ->count()
        ];
    }
}
