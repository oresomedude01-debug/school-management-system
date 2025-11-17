<?php

namespace App\Http\Controllers;

use App\Models\RegistrationToken;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PublicEnrollmentController extends Controller
{
    /**
     * Display the enrollment form
     */
    public function index()
    {
        return view('enrollment.index');
    }

    /**
     * Validate registration token
     */
    public function validateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token_code' => 'required|string|size:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid 8-character token code',
                'errors' => $validator->errors()
            ], 422);
        }

        $token = RegistrationToken::where('token_code', strtoupper($request->token_code))->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token code. Please check and try again.'
            ], 404);
        }

        if (!$token->isValid()) {
            $message = $token->status === 'consumed'
                ? 'This token has already been used.'
                : ($token->status === 'disabled'
                    ? 'This token has been disabled.'
                    : 'This token has expired.');

            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token is valid! You can proceed with enrollment.',
            'token' => [
                'id' => $token->id,
                'code' => $token->token_code,
                'academic_year' => $token->academic_year,
                'intended_class' => $token->intended_class
            ]
        ]);
    }

    /**
     * Submit enrollment application
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Token
            'token_id' => 'required|exists:registration_tokens,id',

            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',

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
            'parent_password' => 'required|string|min:8|confirmed',

            // Guardian 2 Information (Optional)
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

        // Verify token is still valid
        $token = RegistrationToken::findOrFail($request->token_id);
        if (!$token->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Token is no longer valid'
            ], 400);
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
                'admission_status' => 'provisional',

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
                'registration_token_id' => $token->id,
                'status' => 'active'
            ]);

            // Consume the token
            $token->consume($student->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Enrollment submitted successfully!',
                'data' => [
                    'admission_number' => $admissionNumber,
                    'student_name' => $student->full_name,
                    'parent_email' => $user->email,
                    'enrollment_date' => $student->enrollment_date->format('Y-m-d')
                ]
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
                'message' => 'Failed to process enrollment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check enrollment status (optional - for parents to check)
     */
    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admission_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $student = Student::where('admission_number', $request->admission_number)
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'No enrollment found with the provided details'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'student' => [
                'name' => $student->full_name,
                'admission_number' => $student->admission_number,
                'admission_status' => $student->admission_status,
                'enrollment_date' => $student->enrollment_date->format('Y-m-d'),
                'status' => $student->status
            ]
        ]);
    }
}
