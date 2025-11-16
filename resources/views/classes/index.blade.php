@extends('dashboard')

@section('title', 'Classes - School Management System')

@section('page-content')
<div id="classesContent">
    <div class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-purple-600 mx-auto"></div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof ClassesComponent !== 'undefined') {
        document.getElementById('classesContent').innerHTML = '';
        ClassesComponent.render();
    }
});
</script>
@endsection
