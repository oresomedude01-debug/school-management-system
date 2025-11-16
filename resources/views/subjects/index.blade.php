@extends('dashboard')

@section('title', 'Subjects - School Management System')

@section('page-content')
<div id="subjectsContent">
    <div class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-yellow-600 mx-auto"></div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof SubjectsComponent !== 'undefined') {
        document.getElementById('subjectsContent').innerHTML = '';
        SubjectsComponent.render();
    }
});
</script>
@endsection
