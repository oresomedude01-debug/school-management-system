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
        'admission_number',
        'date_of_birth',
        'gender',
        'parent_name',
        'parent_phone',
        'parent_email',
        'medical_info',
        'status',
        // New enrollment fields
        'first_name',
        'middle_name',
        'last_name',
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
        // Legacy fields for backwards compatibility
        'parent_address',
        'relationship_to_parent',
        'emergency_contact_relationship',
        'previous_school',
        'previous_grade',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'emergency_medical_consent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function registrationToken()
    {
        return $this->belongsTo(RegistrationToken::class);
    }
}
