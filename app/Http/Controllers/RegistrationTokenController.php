<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationToken;
use Illuminate\Support\Facades\DB;

class RegistrationTokenController extends Controller
{
    /**
     * Display a listing of registration tokens
     */
    public function index(Request $request)
    {
        $query = RegistrationToken::query()->with('student');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by token code
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('token_code', 'like', "%{$search}%")
                  ->orWhere('academic_year', 'like', "%{$search}%")
                  ->orWhere('intended_class', 'like', "%{$search}%");
        }

        $tokens = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($tokens);
    }

    /**
     * Store a newly created registration token
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'intended_class' => 'required|string|max:100',
            'expiry_date' => 'required|date|after:today',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $token = RegistrationToken::create([
                'token_code' => RegistrationToken::generateTokenCode(),
                'status' => 'active',
                'academic_year' => $validated['academic_year'],
                'intended_class' => $validated['intended_class'],
                'expiry_date' => $validated['expiry_date'],
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration token created successfully',
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified registration token
     */
    public function show(RegistrationToken $token)
    {
        return response()->json($token->load('student'));
    }

    /**
     * Update the specified registration token
     */
    public function update(Request $request, RegistrationToken $token)
    {
        $validated = $request->validate([
            'academic_year' => 'sometimes|string|max:20',
            'intended_class' => 'sometimes|string|max:100',
            'expiry_date' => 'sometimes|date',
            'note' => 'nullable|string|max:500',
            'status' => 'sometimes|in:active,expired,consumed,cancelled',
        ]);

        DB::beginTransaction();
        try {
            $token->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Token updated successfully',
                'token' => $token
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified registration token
     */
    public function destroy(RegistrationToken $token)
    {
        try {
            $token->delete();
            return response()->json([
                'success' => true,
                'message' => 'Token deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify if a token is valid
     */
    public function verify(Request $request)
    {
        $request->validate([
            'token_code' => 'required|string'
        ]);

        $token = RegistrationToken::where('token_code', $request->token_code)->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token code'
            ], 404);
        }

        if (!$token->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired or is no longer valid'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token is valid',
            'token' => $token
        ]);
    }
}
