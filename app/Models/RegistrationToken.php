<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrationToken extends Model
{
    protected $fillable = [
        'token_code',
        'status',
        'academic_year',
        'intended_class',
        'expiry_date',
        'note',
        'student_id',
        'consumed_at'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    /**
     * Generate a unique token code
     */
    public static function generateTokenCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('token_code', $code)->exists());

        return $code;
    }

    /**
     * Check if token is valid for use
     */
    public function isValid(): bool
    {
        // Check if token is active
        if ($this->status !== 'active') {
            return false;
        }

        // Check if token has expired
        if ($this->expiry_date && Carbon::now()->gt($this->expiry_date)) {
            $this->update(['status' => 'expired']);
            return false;
        }

        return true;
    }

    /**
     * Consume the token
     */
    public function consume(int $studentId): void
    {
        $this->update([
            'status' => 'consumed',
            'student_id' => $studentId,
            'consumed_at' => now(),
        ]);
    }

    /**
     * Relationship: Token belongs to a Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Scope: Get active tokens
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get expired tokens
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere(function ($q) {
                $q->where('expiry_date', '<', now())
                  ->where('status', 'active');
            });
    }

    /**
     * Scope: Get consumed tokens
     */
    public function scopeConsumed($query)
    {
        return $query->where('status', 'consumed');
    }
}
