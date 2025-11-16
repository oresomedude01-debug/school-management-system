// Role-Based Dashboard System
// Detects user role and renders appropriate dashboard

const RoleBasedDashboard = {
    currentRole: null,
    userInfo: null,

    async init() {
        await this.detectRole();
        this.renderDashboard();
    },

    async detectRole() {
        // In a real app, this would come from the authenticated user
        // For now, we'll detect from URL or localStorage
        const storedRole = localStorage.getItem('userRole') || 'admin';
        this.currentRole = storedRole;

        // Mock user info - replace with actual API call
        this.userInfo = {
            name: 'Administrator',
            email: 'admin@school.com',
            role: storedRole,
            avatar: null
        };
    },

    renderDashboard() {
        const content = $('#mainContent');
        if (!content) return;

        switch(this.currentRole) {
            case 'admin':
                this.renderAdminDashboard();
                break;
            case 'teacher':
                this.renderTeacherDashboard();
                break;
            case 'student':
                this.renderStudentDashboard();
                break;
            case 'parent':
                this.renderParentDashboard();
                break;
            default:
                this.renderAdminDashboard();
        }
    },

    renderAdminDashboard() {
        const content = $('#mainContent');
        content.innerHTML = `
            <div class="space-y-6">
                <!-- Welcome Header -->
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl shadow-xl p-8 text-white transform hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2" data-i18n="dashboard.welcome">Welcome back! 👋</h1>
                            <p class="text-blue-100 text-lg">Here's what's happening in your school today</p>
                        </div>
                        <div class="hidden lg:block">
                            <div class="bg-white/20 backdrop-blur-lg rounded-xl p-4">
                                <div class="text-center">
                                    <div class="text-4xl font-bold">${new Date().getDate()}</div>
                                    <div class="text-sm">${new Date().toLocaleDateString('en-US', { month: 'short', year: 'numeric' })}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div id="kpiCards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Loading animation -->
                    <div class="col-span-full text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mx-auto"></div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Attendance Chart -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 p-2 rounded-lg mr-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </span>
                            Weekly Attendance Trend
                        </h3>
                        <canvas id="attendanceChart" height="300"></canvas>
                    </div>

                    <!-- Enrollment Chart -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-purple-100 p-2 rounded-lg mr-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </span>
                            Student Distribution by Grade
                        </h3>
                        <canvas id="enrollmentChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Recent Activities & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Activities -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-green-100 p-2 rounded-lg mr-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            Recent Activities
                        </h3>
                        <div id="recentActivities" class="space-y-3">
                            <div class="animate-pulse flex space-x-4">
                                <div class="h-12 w-12 bg-gray-200 rounded"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-yellow-100 p-2 rounded-lg mr-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </span>
                            Quick Actions
                        </h3>
                        <div class="space-y-2">
                            <button class="w-full text-left px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-200 flex items-center group">
                                <svg class="h-5 w-5 text-blue-600 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="font-medium text-gray-700">Add New Student</span>
                            </button>
                            <button class="w-full text-left px-4 py-3 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition-all duration-200 flex items-center group">
                                <svg class="h-5 w-5 text-green-600 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <span class="font-medium text-gray-700">Mark Attendance</span>
                            </button>
                            <button class="w-full text-left px-4 py-3 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-200 flex items-center group">
                                <svg class="h-5 w-5 text-purple-600 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium text-gray-700">Generate Report</span>
                            </button>
                            <button class="w-full text-left px-4 py-3 bg-gradient-to-r from-pink-50 to-pink-100 hover:from-pink-100 hover:to-pink-200 rounded-xl transition-all duration-200 flex items-center group">
                                <svg class="h-5 w-5 text-pink-600 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium text-gray-700">Schedule Event</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.loadAdminData();
    },

    async loadAdminData() {
        try {
            const stats = await API.get('/api/dashboard/stats');
            this.renderKPICards(stats);
            this.renderCharts(stats);
            this.renderRecentActivities();
        } catch (error) {
            console.error('Failed to load dashboard data:', error);
        }
    },

    renderKPICards(stats) {
        const container = $('#kpiCards');
        if (!container) return;

        const cards = [
            {
                title: 'Total Students',
                value: stats.total_students || 0,
                change: '+12%',
                icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>`,
                color: 'blue',
                gradient: 'from-blue-500 to-blue-600'
            },
            {
                title: 'Total Teachers',
                value: stats.total_teachers || 0,
                change: '+5%',
                icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>`,
                color: 'green',
                gradient: 'from-green-500 to-green-600'
            },
            {
                title: 'Attendance Rate',
                value: (stats.attendance_rate || 0) + '%',
                change: '+2%',
                icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>`,
                color: 'purple',
                gradient: 'from-purple-500 to-purple-600'
            },
            {
                title: 'Average Grade',
                value: (stats.average_grade || 0) + '%',
                change: '+4%',
                icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>`,
                color: 'pink',
                gradient: 'from-pink-500 to-pink-600'
            }
        ];

        container.innerHTML = cards.map(card => `
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-br ${card.gradient} p-3 rounded-xl shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${card.icon}
                            </svg>
                        </div>
                    </div>
                    <span class="text-green-600 text-sm font-semibold flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        ${card.change}
                    </span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">${card.title}</p>
                    <p class="text-3xl font-bold text-gray-800">${card.value}</p>
                </div>
            </div>
        `).join('');
    },

    renderCharts(stats) {
        // Attendance Chart
        if (typeof Chart !== 'undefined') {
            const attendanceCtx = document.getElementById('attendanceChart');
            if (attendanceCtx) {
                new Chart(attendanceCtx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Attendance %',
                            data: [92, 95, 88, 94, 96, 85, 90],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true, max: 100 }
                        }
                    }
                });
            }

            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart');
            if (enrollmentCtx) {
                new Chart(enrollmentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5'],
                        datasets: [{
                            data: [120, 150, 180, 90, 200],
                            backgroundColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(139, 92, 246)',
                                'rgb(251, 146, 60)',
                                'rgb(236, 72, 153)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        }
    },

    renderRecentActivities() {
        const container = $('#recentActivities');
        if (!container) return;

        const activities = [
            { icon: '👤', text: 'New student enrolled: John Doe', time: '5 minutes ago', color: 'blue' },
            { icon: '📝', text: 'Grade submitted for Math 101', time: '15 minutes ago', color: 'green' },
            { icon: '📅', text: 'Parent-teacher meeting scheduled', time: '1 hour ago', color: 'purple' },
            { icon: '✅', text: 'Attendance marked for Class 5-A', time: '2 hours ago', color: 'yellow' }
        ];

        container.innerHTML = activities.map(activity => `
            <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <div class="flex-shrink-0 w-10 h-10 bg-${activity.color}-100 rounded-lg flex items-center justify-center text-xl">
                    ${activity.icon}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">${activity.text}</p>
                    <p class="text-xs text-gray-500">${activity.time}</p>
                </div>
            </div>
        `).join('');
    },

    renderTeacherDashboard() {
        // Will implement next
    },

    renderStudentDashboard() {
        // Will implement next
    },

    renderParentDashboard() {
        // Will implement next
    }
};

// Export for global access
window.RoleBasedDashboard = RoleBasedDashboard;
