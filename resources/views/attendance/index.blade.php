@extends('dashboard')

@section('title', 'Attendance - School Management System')

@section('page-content')
<div id="attendanceContent">
    <div class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-indigo-600 mx-auto"></div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof AttendanceComponent !== 'undefined') {
        document.getElementById('attendanceContent').innerHTML = '';
        AttendanceComponent.render();
    }
});
</script>
@endsection
