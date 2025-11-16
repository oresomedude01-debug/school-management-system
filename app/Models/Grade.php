<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'class_id',
        'exam_type',
        'marks_obtained',
        'total_marks',
        'grade',
        'remarks',
        'academic_year',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'total_marks' => 'decimal:2',
    ];

    protected $appends = ['percentage'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function getPercentageAttribute(): float
    {
        return $this->total_marks > 0 ? ($this->marks_obtained / $this->total_marks) * 100 : 0;
    }
}
