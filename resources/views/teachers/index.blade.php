@extends('dashboard')

@section('title', 'Teachers - School Management System')

@section('page-content')
<div id="teachersContent">
    <div class="text-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-green-600 mx-auto"></div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof TeachersComponent !== 'undefined') {
        document.getElementById('teachersContent').innerHTML = '';
        TeachersComponent.render();
    }
});
</script>
@endsection
