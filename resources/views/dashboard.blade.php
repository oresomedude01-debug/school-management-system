@extends('layout')

@section('title', 'Dashboard - School Management System')

@section('content')
<style>
    /* Custom Animations */
    @keyframes slideIn {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .sidebar-animation {
        animation: slideIn 0.5s ease-out;
    }

    .content-animation {
        animation: fadeInUp 0.6s ease-out;
    }

    .nav-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-item:hover {
        transform: translateX(8px);
    }

    .nav-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .glassmorphism {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .notification-badge {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .sidebar-logo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .topbar-gradient {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
    }

    /* Scrollbar Styling */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
</style>

<div id="app" class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50">
    <!-- Loading Screen with Animation -->
    <div id="loadingScreen" class="fixed inset-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="relative">
                <div class="animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-white mx-auto"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <p class="mt-6 text-white text-lg font-semibold">Loading Excellence...</p>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl transform transition-transform duration-300 ease-in-out z-40 sidebar-animation custom-scrollbar overflow-y-auto">
        <!-- Logo Section -->
        <div class="h-20 flex items-center justify-center border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center space-x-3">
                <div class="h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold sidebar-logo">Excellence</h1>
                    <p class="text-xs text-gray-500">Academy</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="px-4 py-6 space-y-2">
            <!-- Dashboard -->
            <a href="#" data-route="dashboard" class="nav-item active flex items-center space-x-3 px-4 py-3 rounded-xl text-white font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Students -->
            <a href="#" data-route="students" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>Students</span>
            </a>

            <!-- Teachers -->
            <a href="#" data-route="teachers" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Teachers</span>
            </a>

            <!-- Classes -->
            <a href="#" data-route="classes" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-purple-50 font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span>Classes</span>
            </a>

            <!-- Subjects -->
            <a href="#" data-route="subjects" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-yellow-50 font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span>Subjects</span>
            </a>

            <!-- Attendance -->
            <a href="#" data-route="attendance" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-indigo-50 font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span>Attendance</span>
            </a>

            <!-- Grades -->
            <a href="#" data-route="grades" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-pink-50 font-medium">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Grades</span>
            </a>
        </nav>

        <!-- User Profile Section in Sidebar -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center space-x-3 px-3 py-2">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                    A
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">Administrator</p>
                    <p class="text-xs text-gray-500">admin@school.com</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="ml-64 min-h-screen">
        <!-- Top Bar -->
        <div class="topbar-gradient shadow-lg border-b border-gray-200 sticky top-0 z-30 glassmorphism">
            <div class="h-20 px-8 flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-4">
                    <div class="relative">
                        <input type="text" placeholder="Search students, teachers, classes..."
                               class="w-full pl-12 pr-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-indigo-500 focus:outline-none transition-all shadow-sm">
                        <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 rounded-xl hover:bg-white transition-all shadow-sm">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full notification-badge"></span>
                    </button>

                    <!-- Messages -->
                    <button class="relative p-2 rounded-xl hover:bg-white transition-all shadow-sm">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <span class="absolute top-1 right-1 h-2 w-2 bg-blue-500 rounded-full notification-badge"></span>
                    </button>

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center space-x-3 px-4 py-2 rounded-2xl hover:bg-white transition-all shadow-sm">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                A
                            </div>
                            <span class="font-medium text-gray-700 hidden lg:block">Admin</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-56 rounded-2xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 z-10 overflow-hidden">
                            <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50">
                                <p class="text-sm font-semibold text-gray-900">Administrator</p>
                                <p class="text-xs text-gray-500">admin@school.com</p>
                            </div>
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Profile Settings</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Preferences</a>
                                <hr class="my-1">
                                <a href="#" id="logoutBtn" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">Sign out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="p-8 content-animation">
            <div id="mainContent">
                <!-- Content will be rendered here -->
            </div>
        </main>
    </div>
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
        initializeSidebarNavigation();
    });

    function initializeSidebarNavigation() {
        const navItems = document.querySelectorAll('.nav-item');
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        // Handle navigation
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all items
                navItems.forEach(nav => {
                    nav.classList.remove('active', 'text-white');
                    nav.classList.add('text-gray-700');
                });

                // Add active class to clicked item
                this.classList.add('active', 'text-white');
                this.classList.remove('text-gray-700');

                // Navigate to route
                const route = this.dataset.route;
                SchoolApp.router.navigate(route);
            });
        });

        // Sidebar toggle for mobile
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // User menu toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    }
</script>
@endsection
