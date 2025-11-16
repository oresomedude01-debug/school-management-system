@extends('dashboard')

@section('title', 'Grades - School Management System')

@section('page-content')
<div id="gradesContent">
    <div class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-pink-600 mx-auto"></div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof GradesComponent !== 'undefined') {
        document.getElementById('gradesContent').innerHTML = '';
        GradesComponent.render();
    }
});
</script>
@endsection
