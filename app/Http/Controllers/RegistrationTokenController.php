<?php

namespace App\Http\Controllers;

use App\Models\RegistrationToken;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegistrationTokenController extends Controller
{
    /**
     * Display a listing of registration tokens
     */
    public function index(Request $request)
    {
        $query = RegistrationToken::query()->with('student');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        // Search by token code
        if ($request->has('search') && $request->search) {
            $query->where('token_code', 'like', '%' . $request->search . '%');
        }

        // Sort by latest first
        $tokens = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'tokens' => $tokens,
            'statistics' => $this->getStatistics()
        ]);
    }

    /**
     * Generate a single registration token
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|string|max:20',
            'intended_class' => 'nullable|string|max:50',
            'expiry_date' => 'nullable|date|after:today',
            'note' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $token = RegistrationToken::create([
            'token_code' => RegistrationToken::generateTokenCode(),
            'status' => 'active',
            'academic_year' => $request->academic_year,
            'intended_class' => $request->intended_class,
            'expiry_date' => $request->expiry_date,
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration token generated successfully',
            'token' => $token
        ], 201);
    }

    /**
     * Generate multiple registration tokens (bulk)
     */
    public function bulkGenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:100',
            'academic_year' => 'required|string|max:20',
            'intended_class' => 'nullable|string|max:50',
            'expiry_date' => 'nullable|date|after:today',
            'note' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $tokens = [];
        $quantity = min($request->quantity, 100); // Max 100 tokens at once

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $quantity; $i++) {
                $tokens[] = RegistrationToken::create([
                    'token_code' => RegistrationToken::generateTokenCode(),
                    'status' => 'active',
                    'academic_year' => $request->academic_year,
                    'intended_class' => $request->intended_class,
                    'expiry_date' => $request->expiry_date,
                    'note' => $request->note
                ]);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$quantity} registration tokens generated successfully",
                'tokens' => $tokens
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate tokens',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified token
     */
    public function show($id)
    {
        $token = RegistrationToken::with('student.user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }

    /**
     * Update token status (enable/disable)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,disabled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $token = RegistrationToken::findOrFail($id);

        // Don't allow changing status of consumed tokens
        if ($token->status === 'consumed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify status of consumed tokens'
            ], 400);
        }

        $token->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Token status updated successfully',
            'token' => $token
        ]);
    }

    /**
     * Delete an unused token
     */
    public function destroy($id)
    {
        $token = RegistrationToken::findOrFail($id);

        // Don't allow deleting consumed tokens
        if ($token->status === 'consumed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete consumed tokens'
            ], 400);
        }

        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token deleted successfully'
        ]);
    }

    /**
     * Get token statistics
     */
    public function getStatistics()
    {
        $total = RegistrationToken::count();
        $active = RegistrationToken::where('status', 'active')->count();
        $consumed = RegistrationToken::where('status', 'consumed')->count();
        $disabled = RegistrationToken::where('status', 'disabled')->count();
        $expired = RegistrationToken::where('status', 'expired')
            ->orWhere(function ($q) {
                $q->where('expiry_date', '<', now())
                  ->where('status', 'active');
            })
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'consumed' => $consumed,
            'disabled' => $disabled,
            'expired' => $expired,
            'available' => $active - $expired
        ];
    }

    /**
     * Export tokens to CSV
     */
    public function export(Request $request)
    {
        $query = RegistrationToken::query();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        $tokens = $query->orderBy('created_at', 'desc')->get();

        $csv = "Token Code,Status,Academic Year,Intended Class,Expiry Date,Created At,Consumed At\n";

        foreach ($tokens as $token) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $token->token_code,
                $token->status,
                $token->academic_year ?? '',
                $token->intended_class ?? '',
                $token->expiry_date ? $token->expiry_date->format('Y-m-d') : '',
                $token->created_at->format('Y-m-d H:i:s'),
                $token->consumed_at ? $token->consumed_at->format('Y-m-d H:i:s') : ''
            );
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="registration-tokens-' . date('Y-m-d') . '.csv"');
    }
}
