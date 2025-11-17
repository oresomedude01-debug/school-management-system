<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'admission_number',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'photo_path',
        'enrollment_date',
        'admission_status',
        'previous_school_name',
        'previous_school_address',
        'previous_class',
        'transfer_reason',
        'previous_result_path',
        'allergies',
        'medical_conditions',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_medical_consent',
        'guardian2_name',
        'guardian2_relationship',
        'guardian2_phone',
        'guardian2_email',
        'guardian2_occupation',
        'preferred_contact_method',
        'registration_token_id',
        'parent_name',
        'parent_phone',
        'parent_email',
        'medical_info',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'emergency_medical_consent' => 'boolean',
    ];

    /**
     * Generate admission number in format: YYYYMMPPTTTT
     * YYYY = Year (e.g., 2025)
     * MM = Month (01-12)
     * PP = Position in month (01-99, zero-padded)
     * TTTT = Overall position (0001-9999, zero-padded)
     */
    public static function generateAdmissionNumber(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');

        // Get position in current month
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $countInMonth = self::whereBetween('enrollment_date', [$monthStart, $monthEnd])->count();
        $positionInMonth = str_pad($countInMonth + 1, 2, '0', STR_PAD_LEFT);

        // Get overall position
        $overallCount = self::count();
        $overallPosition = str_pad($overallCount + 1, 4, '0', STR_PAD_LEFT);

        return $year . $month . $positionInMonth . $overallPosition;
    }

    /**
     * Get full name accessor
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts) ?: ($this->user->name ?? '');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registrationToken(): BelongsTo
    {
        return $this->belongsTo(RegistrationToken::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'enrollments', 'student_id', 'class_id')
            ->withPivot('academic_year', 'enrollment_date', 'status')
            ->withTimestamps();
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
