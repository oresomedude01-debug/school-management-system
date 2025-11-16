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

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
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

    @keyframes fadeInDown {
        from {
            transform: translateY(-20px);
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

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-8px);
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

    /* Mobile Specific Animations */
    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }

    .mobile-nav-item {
        animation: scaleIn 0.3s ease-out backwards;
    }

    .mobile-nav-item:nth-child(1) { animation-delay: 0.05s; }
    .mobile-nav-item:nth-child(2) { animation-delay: 0.1s; }
    .mobile-nav-item:nth-child(3) { animation-delay: 0.15s; }
    .mobile-nav-item:nth-child(4) { animation-delay: 0.2s; }
    .mobile-nav-item:nth-child(5) { animation-delay: 0.25s; }

    /* Bottom Navigation for Mobile */
    .bottom-nav {
        animation: slideUp 0.4s ease-out;
    }

    .bottom-nav-item {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .bottom-nav-item.active {
        transform: translateY(-8px);
    }

    .bottom-nav-item.active .nav-icon {
        animation: bounce 0.6s ease-in-out;
    }

    /* Backdrop */
    .backdrop {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    /* Responsive utilities */
    @media (max-width: 1024px) {
        .sidebar-animation {
            animation: none;
        }

        .nav-item:hover {
            transform: none;
        }
    }

    /* Floating action button */
    .fab {
        animation: float 3s ease-in-out infinite;
    }

    /* Mobile search */
    .mobile-search-active {
        animation: fadeInDown 0.3s ease-out;
    }

    /* RTL Support */
    [dir="rtl"] #sidebar {
        left: auto;
        right: 0;
    }

    [dir="rtl"] .sidebar-animation {
        animation: slideInRight 0.5s ease-out;
    }

    [dir="rtl"] #sidebar.-translate-x-full {
        transform: translateX(100%);
    }

    [dir="rtl"] .lg\:ml-64 {
        margin-left: 0;
        margin-right: 16rem;
    }

    [dir="rtl"] .nav-item:hover {
        transform: translateX(-8px);
    }

    [dir="rtl"] .space-x-2 > * + *,
    [dir="rtl"] .space-x-3 > * + *,
    [dir="rtl"] .space-x-4 > * + * {
        margin-left: 0;
    }

    [dir="rtl"] .space-x-2 > * + * {
        margin-right: 0.5rem;
    }

    [dir="rtl"] .space-x-3 > * + * {
        margin-right: 0.75rem;
    }

    [dir="rtl"] .space-x-4 > * + * {
        margin-right: 1rem;
    }

    /* RTL Typography */
    [dir="rtl"] body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, 'Traditional Arabic', 'Arabic Typesetting';
    }

    [dir="rtl"] .text-left {
        text-align: right;
    }

    [dir="rtl"] .text-right {
        text-align: left;
    }

    /* RTL Animations - Reverse directional animations */
    @keyframes slideInRTL {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    [dir="rtl"] .sidebar-animation {
        animation: slideInRTL 0.5s ease-out;
    }

    /* Language Switcher Styling */
    .lang-switcher {
        position: relative;
    }

    .lang-dropdown {
        min-width: 180px;
    }

    .lang-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
    }

    .lang-option:hover {
        background-color: #f3f4f6;
    }

    .lang-option.active {
        background-color: #eef2ff;
        color: #667eea;
        font-weight: 600;
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
            <p class="mt-6 text-white text-lg font-semibold" data-i18n="dashboard.loading">Loading Excellence...</p>
        </div>
    </div>

    <!-- Mobile Backdrop -->
    <div id="mobileBackdrop" class="fixed inset-0 backdrop bg-black/50 z-30 hidden lg:hidden transition-opacity duration-300"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 lg:sidebar-animation custom-scrollbar overflow-y-auto">
        <!-- Logo Section -->
        <div class="h-16 lg:h-20 flex items-center justify-between px-4 lg:justify-center border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 lg:h-12 lg:w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform">
                    <svg class="h-6 w-6 lg:h-7 lg:w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg lg:text-xl font-bold sidebar-logo" data-i18n="app_name">Excellence</h1>
                    <p class="text-xs text-gray-500" data-i18n="app_tagline">Academy</p>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button id="sidebarClose" class="lg:hidden p-2 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="px-4 py-6 pb-24 lg:pb-6 space-y-2">
            <!-- Dashboard -->
            <a href="/dashboard" class="nav-item {{ Request::is('dashboard') ? 'active text-white' : 'text-gray-700 hover:bg-blue-50' }} flex items-center space-x-3 px-4 py-3 rounded-xl font-medium">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span data-i18n="nav.dashboard">Dashboard</span>
            </a>

            <!-- Students -->
            <a href="/students" class="nav-item {{ Request::is('students*') ? 'active text-white' : 'text-gray-700 hover:bg-blue-50' }} flex items-center space-x-3 px-4 py-3 rounded-xl font-medium">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span data-i18n="nav.students">Students</span>
            </a>

            <!-- Teachers -->
            <a href="/teachers" class="nav-item {{ Request::is('teachers*') ? 'active text-white' : 'text-gray-700 hover:bg-green-50' }} flex items-center space-x-3 px-4 py-3 rounded-xl font-medium">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span data-i18n="nav.teachers">Teachers</span>
            </a>

            <!-- Classes -->
            <a href="/classes" class="nav-item {{ Request::is('classes*') ? 'active text-white' : 'text-gray-700 hover:bg-purple-50' }} flex items-center space-x-3 px-4 py-3 rounded-xl font-medium">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span data-i18n="nav.classes">Classes</span>
            </a>

            <!-- Subjects -->
            <a href="/subjects" class="nav-item {{ Request::is('subjects*') ? 'active text-white' : 'text-gray-700 hover:bg-yellow-50' }} flex items-center space-x-3 px-4 py-3 rounded-xl font-medium">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span data-i18n="nav.subjects">Subjects</span>
            </a>

            <!-- Attendance -->
            <a href="/attendance" class="nav-item {{ Request::is('attendance*') ? 'active text-white' : 'text-gray-700 hover:bg-indigo-50' }} flex items-center space-x-3 px-4 py-3 rounded-xl font-medium">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span data-i18n="nav.attendance">Attendance</span>
            </a>

            <!-- Grades -->
            <a href="/grades" class="nav-item {{ Request::is('grades*') ? 'active text-white' : 'text-gray-700 hover:bg-pink-50' }} flex items-center space-x-3 px-4 py-3 rounded-xl font-medium">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span data-i18n="nav.grades">Grades</span>
            </a>
        </nav>

        <!-- User Profile Section in Sidebar (Desktop only) -->
        <div class="hidden lg:block absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
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
    <div class="lg:ml-64 min-h-screen pb-20 lg:pb-0">
        <!-- Top Bar -->
        <div class="topbar-gradient shadow-lg border-b border-gray-200 sticky top-0 z-30 glassmorphism">
            <div class="h-16 lg:h-20 px-4 lg:px-8 flex items-center justify-between">
                <!-- Mobile Menu Button & Logo -->
                <div class="flex items-center space-x-3">
                    <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-white/50 transition-all transform active:scale-95">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="lg:hidden flex items-center space-x-2">
                        <div class="h-8 w-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Excellence</span>
                    </div>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:block flex-1 max-w-2xl mx-4">
                    <div class="relative">
                        <input type="text" placeholder="Search students, teachers, classes..."
                               class="w-full pl-12 pr-4 py-2.5 lg:py-3 text-sm lg:text-base rounded-2xl border-2 border-gray-200 focus:border-indigo-500 focus:outline-none transition-all shadow-sm">
                        <svg class="absolute left-4 top-2.5 lg:top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-2 lg:space-x-4">
                    <!-- Mobile Search Toggle -->
                    <button id="mobileSearchToggle" class="md:hidden p-2 rounded-xl hover:bg-white transition-all shadow-sm transform active:scale-95">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- Notifications -->
                    <button class="relative p-2 rounded-xl hover:bg-white transition-all shadow-sm transform active:scale-95">
                        <svg class="h-5 w-5 lg:h-6 lg:w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full notification-badge"></span>
                    </button>

                    <!-- Messages (Hidden on mobile) -->
                    <button class="hidden sm:block relative p-2 rounded-xl hover:bg-white transition-all shadow-sm transform active:scale-95">
                        <svg class="h-5 w-5 lg:h-6 lg:w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <span class="absolute top-1 right-1 h-2 w-2 bg-blue-500 rounded-full notification-badge"></span>
                    </button>

                    <!-- Language Switcher -->
                    <div class="relative lang-switcher">
                        <button id="langMenuButton" class="p-2 rounded-xl hover:bg-white transition-all shadow-sm transform active:scale-95" title="Change Language">
                            <svg class="h-5 w-5 lg:h-6 lg:w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                        </button>
                        <div id="langMenu" class="hidden absolute right-0 mt-2 lang-dropdown rounded-2xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 z-10 overflow-hidden">
                            <div class="px-4 py-2 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                                <p class="text-xs font-semibold text-gray-600" data-i18n="language.select">Select Language</p>
                            </div>
                            <div class="py-1">
                                <button class="lang-option w-full px-4 py-3 text-sm text-left" data-lang="en">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-xl">🇬🇧</span>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">English</p>
                                        </div>
                                        <svg class="h-5 w-5 text-indigo-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                <button class="lang-option w-full px-4 py-3 text-sm text-left" data-lang="ar">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-xl">🇸🇦</span>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">العربية</p>
                                        </div>
                                        <svg class="h-5 w-5 text-indigo-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center space-x-2 lg:space-x-3 px-2 lg:px-4 py-2 rounded-2xl hover:bg-white transition-all shadow-sm transform active:scale-95">
                            <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg text-sm lg:text-base">
                                A
                            </div>
                            <span class="font-medium text-gray-700 hidden lg:block text-sm">Admin</span>
                            <svg class="h-4 w-4 text-gray-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <!-- Mobile Search Bar (Expandable) -->
            <div id="mobileSearchBar" class="hidden md:hidden px-4 pb-3">
                <div class="relative mobile-search-active">
                    <input type="text" placeholder="Search anything..."
                           class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border-2 border-indigo-300 focus:border-indigo-500 focus:outline-none transition-all shadow-sm">
                    <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="p-4 lg:p-8 content-animation">
            <div id="mainContent">
                @yield('page-content')
                @if(!View::hasSection('page-content'))
                <!-- SPA content will be rendered here by JavaScript -->
                @endif
            </div>
        </main>
    </div>

    <!-- Bottom Navigation (Mobile Only) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-2xl z-30 bottom-nav">
        <div class="grid grid-cols-5 h-16 px-2">
            <!-- Dashboard -->
            <button data-mobile-route="dashboard" class="bottom-nav-item active flex flex-col items-center justify-center space-y-1 transition-all">
                <div class="nav-icon p-2 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-indigo-600">Home</span>
            </button>

            <!-- Students -->
            <button data-mobile-route="students" class="bottom-nav-item flex flex-col items-center justify-center space-y-1 transition-all">
                <div class="nav-icon p-2 rounded-xl">
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-500">Students</span>
            </button>

            <!-- Classes -->
            <button data-mobile-route="classes" class="bottom-nav-item flex flex-col items-center justify-center space-y-1 transition-all">
                <div class="nav-icon p-2 rounded-xl">
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-500">Classes</span>
            </button>

            <!-- Attendance -->
            <button data-mobile-route="attendance" class="bottom-nav-item flex flex-col items-center justify-center space-y-1 transition-all">
                <div class="nav-icon p-2 rounded-xl">
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-500">Attend</span>
            </button>

            <!-- More Menu -->
            <button id="mobileMoreBtn" class="bottom-nav-item flex flex-col items-center justify-center space-y-1 transition-all">
                <div class="nav-icon p-2 rounded-xl">
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-500">More</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Container -->
<div id="modalContainer"></div>
@endsection

@section('scripts')
<script src="/js/i18n.js"></script>
<script src="/js/charts.js"></script>
<script src="/js/dashboard-roles.js"></script>
<script src="/js/app.js"></script>
<script>
    // Initialize the application
    window.addEventListener('DOMContentLoaded', async () => {
        // Initialize i18n first
        await i18n.init();
        i18n.translatePage();

        // Then initialize the app
        SchoolApp.init();
        initializeSidebarNavigation();
        initializeMobileFeatures();
        initializeLanguageSwitcher();

        // Initialize role-based dashboard if on dashboard page
        @if(Request::is('dashboard'))
            if (typeof RoleBasedDashboard !== 'undefined') {
                RoleBasedDashboard.init();
            }
        @endif
    });

    function initializeSidebarNavigation() {
        const navItems = document.querySelectorAll('.nav-item');
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const mobileBackdrop = document.getElementById('mobileBackdrop');

        // Close sidebar on navigation for mobile
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                // Close sidebar on mobile after navigation
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Sidebar toggle for mobile
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                openSidebar();
            });
        }

        // Sidebar close button
        if (sidebarClose) {
            sidebarClose.addEventListener('click', () => {
                closeSidebar();
            });
        }

        // Backdrop click to close sidebar
        if (mobileBackdrop) {
            mobileBackdrop.addEventListener('click', () => {
                closeSidebar();
            });
        }

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            mobileBackdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            mobileBackdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // User menu toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', (e) => {
                e.stopPropagation();
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

    function initializeMobileFeatures() {
        // Mobile bottom navigation
        const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
        const mobileMoreBtn = document.getElementById('mobileMoreBtn');
        const sidebar = document.getElementById('sidebar');
        const mobileBackdrop = document.getElementById('mobileBackdrop');

        bottomNavItems.forEach(item => {
            const route = item.dataset.mobileRoute;
            if (route) {
                item.addEventListener('click', function() {
                    // Remove active from all
                    bottomNavItems.forEach(btn => {
                        btn.classList.remove('active');
                        const icon = btn.querySelector('.nav-icon');
                        const text = btn.querySelector('span');
                        icon.classList.remove('bg-gradient-to-br', 'from-indigo-500', 'to-purple-600', 'shadow-lg');
                        icon.querySelector('svg').classList.remove('text-white');
                        icon.querySelector('svg').classList.add('text-gray-500');
                        text.classList.remove('text-indigo-600');
                        text.classList.add('text-gray-500');
                    });

                    // Add active to clicked
                    this.classList.add('active');
                    const icon = this.querySelector('.nav-icon');
                    const text = this.querySelector('span');
                    icon.classList.add('bg-gradient-to-br', 'from-indigo-500', 'to-purple-600', 'shadow-lg');
                    icon.querySelector('svg').classList.remove('text-gray-500');
                    icon.querySelector('svg').classList.add('text-white');
                    text.classList.remove('text-gray-500');
                    text.classList.add('text-indigo-600');

                    // Navigate to the page
                    window.location.href = '/' + route;
                });
            }
        });

        // More button opens sidebar
        if (mobileMoreBtn) {
            mobileMoreBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                mobileBackdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }

        // Mobile search toggle
        const mobileSearchToggle = document.getElementById('mobileSearchToggle');
        const mobileSearchBar = document.getElementById('mobileSearchBar');

        if (mobileSearchToggle && mobileSearchBar) {
            mobileSearchToggle.addEventListener('click', () => {
                mobileSearchBar.classList.toggle('hidden');
                if (!mobileSearchBar.classList.contains('hidden')) {
                    const input = mobileSearchBar.querySelector('input');
                    setTimeout(() => input.focus(), 100);
                }
            });
        }

        // Swipe gestures for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const swipeThreshold = 100;
            const diff = touchEndX - touchStartX;

            // Swipe right to open sidebar (from left edge)
            if (diff > swipeThreshold && touchStartX < 50) {
                sidebar.classList.remove('-translate-x-full');
                mobileBackdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Swipe left to close sidebar
            if (diff < -swipeThreshold && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                mobileBackdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    }

    function initializeLanguageSwitcher() {
        const langMenuButton = document.getElementById('langMenuButton');
        const langMenu = document.getElementById('langMenu');
        const langOptions = document.querySelectorAll('.lang-option');

        // Toggle language menu
        if (langMenuButton && langMenu) {
            langMenuButton.addEventListener('click', (e) => {
                e.stopPropagation();
                langMenu.classList.toggle('hidden');
                // Close user menu if open
                const userMenu = document.getElementById('userMenu');
                if (userMenu && !userMenu.classList.contains('hidden')) {
                    userMenu.classList.add('hidden');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!langMenuButton.contains(e.target) && !langMenu.contains(e.target)) {
                    langMenu.classList.add('hidden');
                }
            });
        }

        // Handle language selection
        langOptions.forEach(option => {
            const lang = option.dataset.lang;

            // Mark current language as active
            if (lang === i18n.getLocale()) {
                option.classList.add('active');
                option.querySelector('svg').classList.remove('hidden');
            }

            option.addEventListener('click', async () => {
                const selectedLang = option.dataset.lang;

                // Update active state
                langOptions.forEach(opt => {
                    opt.classList.remove('active');
                    opt.querySelector('svg').classList.add('hidden');
                });
                option.classList.add('active');
                option.querySelector('svg').classList.remove('hidden');

                // Set new locale
                await i18n.setLocale(selectedLang);

                // Translate the page
                i18n.translatePage();

                // Close menu
                langMenu.classList.add('hidden');

                // Show notification (optional)
                console.log(`Language changed to: ${selectedLang}`);
            });
        });

        // Listen for locale changes to update UI
        document.addEventListener('localeChanged', (e) => {
            const newLocale = e.detail.locale;
            console.log('Locale changed to:', newLocale);

            // Layout remains LTR for all languages
            // Only text content changes based on selected language
        });
    }
</script>
@endsection
