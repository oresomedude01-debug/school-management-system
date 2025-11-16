@extends('dashboard')

@section('title', 'Students - School Management System')

@section('page-content')
<div id="studentsContent">
    <div class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mx-auto"></div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof StudentsComponent !== 'undefined') {
        document.getElementById('studentsContent').innerHTML = '';
        StudentsComponent.render();
    }
});
</script>
@endsection
