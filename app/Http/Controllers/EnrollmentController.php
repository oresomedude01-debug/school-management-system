<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationToken;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnrollmentController extends Controller
{
    /**
     * Show the public enrollment form
     */
    public function showEnrollmentForm()
    {
        return view('enrollment.form');
    }

    /**
     * Verify a registration token
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token_code' => 'required|string'
        ]);

        $token = RegistrationToken::where('token_code', strtoupper($request->token_code))->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid registration token. Please check and try again.'
            ], 404);
        }

        if (!$token->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This token has expired or is no longer valid.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token verified successfully',
            'token' => [
                'academic_year' => $token->academic_year,
                'intended_class' => $token->intended_class,
                'expiry_date' => $token->expiry_date->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Process student self-enrollment
     */
    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'token_code' => 'required|string|exists:registration_tokens,token_code',

            // Student Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',

            // Parent/Guardian Information
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email',
            'parent_address' => 'nullable|string|max:500',
            'relationship_to_student' => 'required|in:father,mother,guardian,other',

            // Additional Information
            'medical_info' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:100',

            // Previous School Information (optional)
            'previous_school' => 'nullable|string|max:255',
            'previous_grade' => 'nullable|string|max:50',
            'transfer_reason' => 'nullable|string|max:500',
        ]);

        // Find and verify token
        $token = RegistrationToken::where('token_code', strtoupper($validated['token_code']))->first();

        if (!$token || !$token->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired registration token'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            // Generate admission number
            $admissionNumber = 'STU' . date('Y') . str_pad(Student::count() + 1, 5, '0', STR_PAD_LEFT);

            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'admission_number' => $admissionNumber,
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'parent_name' => $validated['parent_name'],
                'parent_phone' => $validated['parent_phone'],
                'parent_email' => $validated['parent_email'] ?? null,
                'parent_address' => $validated['parent_address'] ?? null,
                'relationship_to_parent' => $validated['relationship_to_student'],
                'medical_info' => $validated['medical_info'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'emergency_contact_relationship' => $validated['emergency_contact_relationship'],
                'previous_school' => $validated['previous_school'] ?? null,
                'previous_grade' => $validated['previous_grade'] ?? null,
                'transfer_reason' => $validated['transfer_reason'] ?? null,
                'preferred_contact_method' => 'email',
                'registration_token_id' => $token->id,
                'status' => 'active',
            ]);

            // Consume the token
            $token->consume($student->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Enrollment completed successfully! Your admission number is: ' . $admissionNumber,
                'admission_number' => $admissionNumber,
                'student' => $student->load('user')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Enrollment failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
