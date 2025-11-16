<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excellence Academy - Shaping Future Leaders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'fade-in-down': 'fadeInDown 0.6s ease-out forwards',
                        'fade-in': 'fadeIn 0.6s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.6s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'bounce-slow': 'bounce 3s infinite',
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInDown { 0% { opacity: 0; transform: translateY(-30px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
        @keyframes slideInRight { 0% { opacity: 0; transform: translateX(50px); } 100% { opacity: 1; transform: translateX(0); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        
        .animated { opacity: 0; }
        .animated.show { animation-play-state: running; }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .blob { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        @keyframes blob { 0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; } 25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; } 50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; } 75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; } }
        .blob { animation: blob 8s ease-in-out infinite; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -5px; left: 50%; background: #667eea; transition: all 0.3s ease; transform: translateX(-50%); }
        .nav-link:hover::after { width: 100%; }
    </style>
</head>
<body class="bg-white">

<!-- Navigation -->
<nav id="navbar" class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/80 backdrop-blur-md shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center transform hover:rotate-12 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold gradient-text">Excellence Academy</h1>
                    <p class="text-xs text-gray-500">Shaping Future Leaders</p>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="nav-link text-gray-700 hover:text-blue-600 font-medium">Home</a>
                <a href="#about" class="nav-link text-gray-700 hover:text-blue-600 font-medium">About</a>
                <a href="#programs" class="nav-link text-gray-700 hover:text-blue-600 font-medium">Programs</a>
                <a href="#testimonials" class="nav-link text-gray-700 hover:text-blue-600 font-medium">Testimonials</a>
                <a href="#contact" class="nav-link text-gray-700 hover:text-blue-600 font-medium">Contact</a>
                <a href="/login" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2.5 rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-medium">Portal Login</a>
            </div>
            <button id="mobileMenuBtn" class="md:hidden p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
        <div class="px-4 py-6 space-y-3">
            <a href="#home" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">Home</a>
            <a href="#about" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">About</a>
            <a href="#programs" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">Programs</a>
            <a href="#testimonials" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">Testimonials</a>
            <a href="#contact" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">Contact</a>
            <a href="/login" class="block text-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-full">Portal Login</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
    <div class="absolute inset-0 overflow-hidden">
        <div class="blob absolute top-20 -left-20 w-96 h-96 opacity-20"></div>
        <div class="blob absolute bottom-20 -right-20 w-96 h-96 opacity-20" style="animation-delay: 2s;"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="text-center md:text-left">
                <div class="inline-block mb-4 px-4 py-2 bg-blue-50 rounded-full animated animate-fade-in-down">
                    <span class="text-blue-600 font-semibold text-sm">🎓 Welcome to Excellence Academy</span>
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight animated animate-fade-in-up">
                    Empowering <span class="gradient-text block">Future Leaders</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 animated animate-fade-in-up" style="animation-delay: 0.2s;">
                    Join our world-class institution where innovation meets education. We nurture young minds to become tomorrow's changemakers.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start animated animate-fade-in-up" style="animation-delay: 0.4s;">
                    <a href="#programs" class="group bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 font-semibold inline-flex items-center justify-center">
                        Explore Programs
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#contact" class="bg-white border-2 border-blue-500 text-blue-600 px-8 py-4 rounded-full hover:bg-blue-50 transform hover:scale-105 transition-all duration-300 font-semibold">Contact Us</a>
                </div>
                <div class="grid grid-cols-3 gap-6 mt-12 animated animate-fade-in" style="animation-delay: 0.6s;">
                    <div class="text-center"><div class="text-3xl font-bold text-blue-600 mb-1" data-count="5000">0</div><div class="text-sm text-gray-600">Students</div></div>
                    <div class="text-center"><div class="text-3xl font-bold text-purple-600 mb-1" data-count="200">0</div><div class="text-sm text-gray-600">Teachers</div></div>
                    <div class="text-center"><div class="text-3xl font-bold text-indigo-600 mb-1" data-count="50">0</div><div class="text-sm text-gray-600">Programs</div></div>
                </div>
            </div>
            <div class="relative animated animate-slide-in-right" style="animation-delay: 0.3s;">
                <div class="relative animate-float">
                    <svg viewBox="0 0 500 500" class="w-full h-auto">
                        <circle cx="250" cy="250" r="200" fill="url(#grad1)" opacity="0.1"/>
                        <defs><linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#667eea"/><stop offset="100%" style="stop-color:#764ba2"/></linearGradient></defs>
                        <rect x="150" y="280" width="80" height="15" rx="2" fill="#3B82F6" opacity="0.8"/>
                        <rect x="160" y="265" width="80" height="15" rx="2" fill="#8B5CF6" opacity="0.8"/>
                        <rect x="140" y="250" width="80" height="15" rx="2" fill="#F59E0B" opacity="0.8"/>
                        <polygon points="250,150 200,180 300,180" fill="#3B82F6"/>
                        <rect x="245" y="180" width="10" height="30" fill="#3B82F6"/>
                        <circle cx="250" cy="210" r="3" fill="#F59E0B"/>
                        <line x1="250" y1="210" x2="265" y2="225" stroke="#F59E0B" stroke-width="2"/>
                        <circle cx="250" cy="220" r="25" fill="#E5E7EB"/>
                        <path d="M 225 245 Q 250 270 275 245" fill="#3B82F6" opacity="0.6"/>
                        <circle cx="100" cy="150" r="8" fill="#F59E0B" opacity="0.6" class="animate-bounce-slow"/>
                        <circle cx="380" cy="200" r="6" fill="#8B5CF6" opacity="0.6" class="animate-bounce-slow"/>
                        <text x="120" y="100" font-size="20" fill="#F59E0B">★</text>
                        <text x="360" y="120" font-size="16" fill="#8B5CF6">★</text>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#about" class="flex flex-col items-center text-gray-400 hover:text-blue-600">
            <span class="text-sm mb-2">Scroll Down</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-24 bg-gradient-to-b from-white to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 animated animate-fade-in-up">About <span class="gradient-text">Excellence Academy</span></h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto animated animate-fade-in-up" style="animation-delay: 0.1s;">Pioneering excellence in education for over two decades</p>
        </div>
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animated animate-fade-in" style="animation-delay: 0.2s;">
                <div class="relative">
                    <svg viewBox="0 0 400 400" class="w-full h-auto">
                        <defs><linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#3B82F6"/><stop offset="100%" style="stop-color:#8B5CF6"/></linearGradient></defs>
                        <circle cx="200" cy="200" r="150" fill="url(#grad2)" opacity="0.1"/>
                        <rect x="120" y="150" width="160" height="200" rx="10" fill="#3B82F6" opacity="0.2"/>
                        <rect x="140" y="170" width="120" height="15" rx="3" fill="#fff"/>
                        <rect x="140" y="200" width="120" height="15" rx="3" fill="#fff"/>
                        <rect x="140" y="230" width="120" height="15" rx="3" fill="#fff"/>
                        <circle cx="200" cy="100" r="30" fill="#8B5CF6" opacity="0.8"/>
                        <path d="M 200 100 L 220 120 L 200 140 L 180 120 Z" fill="#fff"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-6 animated animate-fade-in" style="animation-delay: 0.3s;">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-bold text-gray-900 mb-2">Innovative Learning</h3><p class="text-gray-600">Cutting-edge teaching methods that inspire creativity and critical thinking</p></div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div><h3 class="text-xl font-bold text-gray-900 mb-2">Expert Faculty</h3><p class="text-gray-600">Dedicated educators with years of experience and passion for teaching</p></div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div><h3 class="text-xl font-bold text-gray-900 mb-2">Modern Facilities</h3><p class="text-gray-600">State-of-the-art infrastructure designed for holistic development</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section id="programs" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 animated animate-fade-in-up">Our <span class="gradient-text">Programs</span></h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto animated animate-fade-in-up" style="animation-delay: 0.1s;">Comprehensive education tailored to every student's needs</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl border border-blue-100 animated animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Primary Education</h3>
                <p class="text-gray-600 mb-6">Building strong foundations in core subjects with interactive learning methods</p>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Grades 1-5</li>
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Activity-based learning</li>
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Smart classrooms</li>
                </ul>
                <a href="#contact" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
            </div>
            <div class="card-hover bg-gradient-to-br from-purple-50 to-white p-8 rounded-2xl border border-purple-100 animated animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Secondary Education</h3>
                <p class="text-gray-600 mb-6">Advanced curriculum preparing students for higher education and beyond</p>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Grades 6-10</li>
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>STEM focus</li>
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Career guidance</li>
                </ul>
                <a href="#contact" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold">Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
            </div>
            <div class="card-hover bg-gradient-to-br from-yellow-50 to-white p-8 rounded-2xl border border-yellow-100 animated animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="w-16 h-16 bg-yellow-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Higher Secondary</h3>
                <p class="text-gray-600 mb-6">Specialized streams for university preparation and competitive exams</p>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Grades 11-12</li>
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Science/Commerce/Arts</li>
                    <li class="flex items-center text-gray-700"><svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Exam preparation</li>
                </ul>
                <a href="#contact" class="inline-flex items-center text-yellow-600 hover:text-yellow-700 font-semibold">Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-24 bg-gradient-to-b from-white to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 animated animate-fade-in-up">What Our <span class="gradient-text">Community Says</span></h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto animated animate-fade-in-up" style="animation-delay: 0.1s;">Hear from our students, parents, and alumni</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-hover bg-white p-8 rounded-2xl shadow-lg animated animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">S</div>
                    <div class="ml-4"><div class="font-bold text-gray-900">Sarah Johnson</div><div class="text-sm text-gray-500">Parent</div></div>
                </div>
                <div class="mb-4"><svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></div>
                <p class="text-gray-600 italic">"Excellence Academy has transformed my child's education. The teachers are dedicated, and the facilities are world-class. Best decision we ever made!"</p>
            </div>
            <div class="card-hover bg-white p-8 rounded-2xl shadow-lg animated animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">M</div>
                    <div class="ml-4"><div class="font-bold text-gray-900">Michael Chen</div><div class="text-sm text-gray-500">Student, Grade 11</div></div>
                </div>
                <div class="mb-4"><svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></div>
                <p class="text-gray-600 italic">"The STEM program here is incredible! I've learned so much and I'm now ready for university. The teachers really care about our success."</p>
            </div>
            <div class="card-hover bg-white p-8 rounded-2xl shadow-lg animated animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">E</div>
                    <div class="ml-4"><div class="font-bold text-gray-900">Emily Rodriguez</div><div class="text-sm text-gray-500">Alumni, 2020</div></div>
                </div>
                <div class="mb-4"><svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></div>
                <p class="text-gray-600 italic">"Excellence Academy prepared me for life! Now studying at MIT, I owe my success to the excellent foundation I received here."</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12">
            <div class="animated animate-fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Get in <span class="gradient-text">Touch</span></h2>
                <p class="text-xl text-gray-600 mb-8">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div><h3 class="font-bold text-gray-900 mb-1">Visit Us</h3><p class="text-gray-600">123 Education Street, Knowledge City, ED 12345</p></div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div><h3 class="font-bold text-gray-900 mb-1">Email Us</h3><p class="text-gray-600">info@excellenceacademy.edu</p></div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div><h3 class="font-bold text-gray-900 mb-1">Call Us</h3><p class="text-gray-600">+1 (555) 123-4567</p></div>
                    </div>
                </div>
            </div>
            <div class="animated animate-fade-in-up" style="animation-delay: 0.2s;">
                <form id="contactForm" class="space-y-6">
                    <div><input type="text" placeholder="Your Name" class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></div>
                    <div><input type="email" placeholder="Your Email" class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></div>
                    <div><input type="tel" placeholder="Phone Number" class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></div>
                    <div><textarea rows="5" placeholder="Your Message" class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" required></textarea></div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 font-semibold">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-xl font-bold">Excellence Academy</span>
                </div>
                <p class="text-gray-400 text-sm">Shaping future leaders through quality education and innovation.</p>
            </div>
            <div><h3 class="font-bold mb-4">Quick Links</h3><ul class="space-y-2 text-gray-400 text-sm"><li><a href="#about" class="hover:text-white transition-colors">About Us</a></li><li><a href="#programs" class="hover:text-white transition-colors">Programs</a></li><li><a href="/login" class="hover:text-white transition-colors">Portal Login</a></li></ul></div>
            <div><h3 class="font-bold mb-4">Contact</h3><ul class="space-y-2 text-gray-400 text-sm"><li>123 Education Street</li><li>Knowledge City, ED 12345</li><li>info@excellenceacademy.edu</li><li>+1 (555) 123-4567</li></ul></div>
            <div><h3 class="font-bold mb-4">Follow Us</h3><div class="flex space-x-4"><a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a><a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-400 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a><a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-pink-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/><path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a></div></div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; 2025 Excellence Academy. All rights reserved. | Built with ❤️ for Education</p>
        </div>
    </div>
</footer>

<script src="/js/landing.js"></script>
</body>
</html>
