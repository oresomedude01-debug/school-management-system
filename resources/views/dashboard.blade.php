@extends('layout')

@section('title', 'Dashboard - School Management System')

@section('content')
<div id="app" class="min-h-screen bg-gray-100">
    <!-- Loading Screen -->
    <div id="loadingScreen" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-2xl font-bold text-blue-600">School MS</h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8" id="mainNav">
                        <!-- Navigation will be rendered here -->
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <button id="userMenuButton" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                                <span id="userInitial">A</span>
                            </div>
                        </button>
                        <div id="userMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-10">
                            <a href="#" id="logoutBtn" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div id="mainContent">
            <!-- Content will be rendered here -->
        </div>
    </main>
</div>

<!-- Modal Container -->
<div id="modalContainer"></div>
@endsection

@section('scripts')
<script src="/js/charts.js"></script>
<script src="/js/app.js"></script>
<script>
    // Initialize the application
    window.addEventListener('DOMContentLoaded', () => {
        SchoolApp.init();
    });
</script>
@endsection
