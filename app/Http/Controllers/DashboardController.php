<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function stats()
    {
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            'total_teachers' => Teacher::where('status', 'active')->count(),
            'total_classes' => SchoolClass::where('status', 'active')->count(),
            'total_subjects' => Subject::where('status', 'active')->count(),
            'present_today' => Attendance::whereDate('date', today())
                ->where('status', 'present')
                ->count(),
            'absent_today' => Attendance::whereDate('date', today())
                ->where('status', 'absent')
                ->count(),
            'recent_students' => Student::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'attendance_rate' => $this->getAttendanceRate(),
        ];

        return response()->json($stats);
    }

    private function getAttendanceRate()
    {
        $totalRecords = Attendance::whereDate('date', today())->count();
        if ($totalRecords === 0) return 0;

        $present = Attendance::whereDate('date', today())
            ->where('status', 'present')
            ->count();

        return round(($present / $totalRecords) * 100, 2);
    }
}
