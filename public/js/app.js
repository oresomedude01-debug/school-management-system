// School Management System - Vanilla JS with React-like Components
// No NPM required - Pure JavaScript

// ===== UTILITY FUNCTIONS =====
const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => document.querySelectorAll(selector);

const createElement = (tag, props = {}, ...children) => {
    const element = document.createElement(tag);
    Object.entries(props).forEach(([key, value]) => {
        if (key === 'className') element.className = value;
        else if (key.startsWith('on') && typeof value === 'function') {
            element.addEventListener(key.substring(2).toLowerCase(), value);
        } else if (key === 'style' && typeof value === 'object') {
            Object.assign(element.style, value);
        } else {
            element.setAttribute(key, value);
        }
    });
    children.forEach(child => {
        if (typeof child === 'string') {
            element.appendChild(document.createTextNode(child));
        } else if (child instanceof Node) {
            element.appendChild(child);
        }
    });
    return element;
};

// ===== API HELPER =====
const API = {
    async request(url, options = {}) {
        const defaultOptions = {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        };

        try {
            const response = await fetch(url, { ...defaultOptions, ...options });
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    get(url) {
        return this.request(url, { method: 'GET' });
    },

    post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },

    put(url, data) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    delete(url) {
        return this.request(url, { method: 'DELETE' });
    }
};

// ===== STATE MANAGEMENT =====
class Store {
    constructor(initialState = {}) {
        this.state = initialState;
        this.listeners = [];
    }

    getState() {
        return this.state;
    }

    setState(newState) {
        this.state = { ...this.state, ...newState };
        this.notify();
    }

    subscribe(listener) {
        this.listeners.push(listener);
        return () => {
            this.listeners = this.listeners.filter(l => l !== listener);
        };
    }

    notify() {
        this.listeners.forEach(listener => listener(this.state));
    }
}

// ===== ROUTER =====
class Router {
    constructor() {
        this.routes = {};
        this.currentRoute = 'dashboard';
    }

    register(name, component) {
        this.routes[name] = component;
    }

    navigate(routeName) {
        this.currentRoute = routeName;
        if (this.routes[routeName]) {
            this.routes[routeName]();
        }
    }
}

// ===== COMPONENTS =====

// Dashboard Component
const DashboardComponent = {
    async render() {
        const content = $('#mainContent');
        content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mx-auto"></div></div>';

        try {
            const stats = await API.get('/api/dashboard/stats');

            content.innerHTML = `
                <div class="px-4 sm:px-0">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Dashboard Overview</h2>
                        <div class="text-sm text-gray-500">Welcome back! 👋</div>
                    </div>

                    <!-- Stats Grid with Animations -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                        ${this.renderStatCard('Students', stats.total_students, 'bg-gradient-to-br from-blue-500 to-blue-600', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', '+12%')}
                        ${this.renderStatCard('Teachers', stats.total_teachers, 'bg-gradient-to-br from-green-500 to-green-600', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', '+5%')}
                        ${this.renderStatCard('Classes', stats.total_classes, 'bg-gradient-to-br from-purple-500 to-purple-600', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', '+3%')}
                        ${this.renderStatCard('Subjects', stats.total_subjects, 'bg-gradient-to-br from-yellow-500 to-orange-500', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', '+8%')}
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Weekly Attendance Chart -->
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Weekly Attendance</h3>
                                <p class="mt-1 text-sm text-gray-500">Last 7 days attendance overview</p>
                            </div>
                            <div class="p-6">
                                <canvas id="weeklyAttendanceChart" width="400" height="250"></canvas>
                            </div>
                        </div>

                        <!-- Student Enrollment Trend -->
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Enrollment Trend</h3>
                                <p class="mt-1 text-sm text-gray-500">Monthly student enrollments</p>
                            </div>
                            <div class="p-6">
                                <canvas id="enrollmentTrendChart" width="400" height="250"></canvas>
                            </div>
                        </div>

                        <!-- Class Distribution -->
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Class Distribution</h3>
                                <p class="mt-1 text-sm text-gray-500">Students per class</p>
                            </div>
                            <div class="p-6 flex justify-center">
                                <canvas id="classDistributionChart" width="350" height="280"></canvas>
                            </div>
                        </div>

                        <!-- Performance Overview -->
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Grade Distribution</h3>
                                <p class="mt-1 text-sm text-gray-500">Current academic performance</p>
                            </div>
                            <div class="p-6 flex justify-center">
                                <canvas id="gradeDistributionChart" width="350" height="280"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Section -->
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 overflow-hidden shadow-lg rounded-2xl mb-8 transform hover:scale-105 transition-transform duration-300">
                        <div class="px-6 py-5">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Today's Attendance</h3>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-600">Present</p>
                                            <p class="text-3xl font-bold text-green-700">${stats.present_today || 0}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-600">Absent</p>
                                            <p class="text-3xl font-bold text-red-700">${stats.absent_today || 0}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
                                            <p class="text-3xl font-bold text-blue-700">${stats.attendance_rate || 0}%</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Students -->
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Recently Added Students</h3>
                            </div>
                            <div class="px-6 py-4">
                                <div class="flow-root">
                                    <ul class="divide-y divide-gray-200">
                                        ${stats.recent_students && stats.recent_students.length > 0
                                            ? stats.recent_students.map(student => `
                                                <li class="py-4 hover:bg-gray-50 transition-colors rounded-lg px-2">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold shadow-lg">
                                                                ${student.user.name.charAt(0).toUpperCase()}
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-semibold text-gray-900 truncate">${student.user.name}</p>
                                                            <p class="text-xs text-gray-500 truncate">${student.admission_number}</p>
                                                        </div>
                                                        <div>
                                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">New</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            `).join('')
                                            : '<li class="py-8 text-gray-400 text-center">No recent students</li>'
                                        }
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">Quick Actions</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    ${this.renderQuickAction('Add Student', 'students', 'M12 4v16m8-8H4', 'blue')}
                                    ${this.renderQuickAction('Mark Attendance', 'attendance', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'green')}
                                    ${this.renderQuickAction('Enter Grades', 'grades', 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'purple')}
                                    ${this.renderQuickAction('View Reports', 'dashboard', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'yellow')}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Initialize charts after DOM is ready
            setTimeout(() => this.initCharts(), 100);

        } catch (error) {
            content.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-600">
                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg font-semibold">Error loading dashboard</p>
                        <p class="text-sm">${error.message}</p>
                    </div>
                </div>
            `;
        }
    },

    renderStatCard(title, value, bgColor, iconPath, trend) {
        return `
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="${bgColor} rounded-xl p-4 shadow-lg">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">${title}</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">${value || 0}</div>
                                    ${trend ? `<span class="ml-2 text-sm font-semibold text-green-600">${trend}</span>` : ''}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    renderQuickAction(title, route, iconPath, color) {
        const colorClasses = {
            blue: 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700',
            green: 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700',
            purple: 'from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700',
            yellow: 'from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600'
        };

        return `
            <button onclick="SchoolApp.router.navigate('${route}')"
                    class="bg-gradient-to-br ${colorClasses[color]} text-white p-4 rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center space-y-2">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>
                </svg>
                <span class="text-sm font-semibold">${title}</span>
            </button>
        `;
    },

    initCharts() {
        // Weekly Attendance Chart (Bar Chart)
        new SimpleChart('weeklyAttendanceChart', {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    data: [450, 420, 480, 460, 470, 380, 150],
                    backgroundColor: ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444', '#06B6D4', '#EC4899'],
                    borderColor: ['#2563EB', '#059669', '#7C3AED', '#D97706', '#DC2626', '#0891B2', '#DB2777']
                }]
            }
        });

        // Enrollment Trend Chart (Line Chart)
        new SimpleChart('enrollmentTrendChart', {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    data: [65, 78, 90, 105, 125, 142],
                    backgroundColor: '#3B82F6',
                    borderColor: '#2563EB'
                }]
            }
        });

        // Class Distribution Chart (Doughnut Chart)
        new SimpleChart('classDistributionChart', {
            type: 'doughnut',
            data: {
                labels: ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'],
                datasets: [{
                    data: [45, 52, 48, 50, 43],
                    backgroundColor: ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444']
                }]
            }
        });

        // Grade Distribution Chart (Pie Chart)
        new SimpleChart('gradeDistributionChart', {
            type: 'pie',
            data: {
                labels: ['A+', 'A', 'B', 'C', 'D'],
                datasets: [{
                    data: [28, 45, 67, 32, 15],
                    backgroundColor: ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444']
                }]
            }
        });
    }
};

// Students Component
const StudentsComponent = {
    students: [],
    currentPage: 1,

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadStudents();
        this.attachEventListeners();
    },

    getTemplate() {
        return `
            <div class="px-4 sm:px-0">
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Students</h2>
                    <button id="addStudentBtn" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Student
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <input type="text" id="searchStudents" placeholder="Search students..."
                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2">
                </div>

                <!-- Students Table -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div id="studentsTableContainer">
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-600 mx-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    async loadStudents(search = '') {
        try {
            const url = `/api/students?page=${this.currentPage}${search ? `&search=${search}` : ''}`;
            const data = await API.get(url);
            this.students = data.data;
            this.renderTable(data);
        } catch (error) {
            $('#studentsTableContainer').innerHTML = `<p class="text-red-600 text-center py-4">Error: ${error.message}</p>`;
        }
    },

    renderTable(data) {
        const container = $('#studentsTableContainer');
        if (!this.students.length) {
            container.innerHTML = '<p class="text-gray-500 text-center py-8">No students found</p>';
            return;
        }

        container.innerHTML = `
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${this.students.map(student => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                        ${student.user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${student.user.name}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${student.admission_number}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${student.user.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    ${student.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                    ${student.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="StudentsComponent.viewStudent(${student.id})">View</button>
                                <button class="text-red-600 hover:text-red-900" onclick="StudentsComponent.deleteStudent(${student.id})">Delete</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">${data.from}</span> to <span class="font-medium">${data.to}</span> of{' '}
                        <span class="font-medium">${data.total}</span> results
                    </div>
                    <div>
                        ${data.prev_page_url ? `<button onclick="StudentsComponent.goToPage(${data.current_page - 1})" class="mr-2 px-3 py-1 border rounded bg-white hover:bg-gray-50">Previous</button>` : ''}
                        ${data.next_page_url ? `<button onclick="StudentsComponent.goToPage(${data.current_page + 1})" class="px-3 py-1 border rounded bg-white hover:bg-gray-50">Next</button>` : ''}
                    </div>
                </div>
            </div>
        `;
    },

    attachEventListeners() {
        $('#addStudentBtn').addEventListener('click', () => this.showAddModal());

        let searchTimeout;
        $('#searchStudents').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.loadStudents(e.target.value);
            }, 300);
        });
    },

    showAddModal() {
        const modal = this.createStudentModal();
        $('#modalContainer').innerHTML = modal;
        $('#studentModal').classList.remove('hidden');
        this.attachModalListeners();
    },

    createStudentModal(student = null) {
        const isEdit = student !== null;
        return `
            <div id="studentModal" class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form id="studentForm">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">${isEdit ? 'Edit' : 'Add'} Student</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="${student?.user?.name || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="${student?.user?.email || ''}">
                                    </div>
                                    ${!isEdit ? `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Password</label>
                                        <input type="password" name="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    ` : ''}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Admission Number</label>
                                        <input type="text" name="admission_number" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="${student?.admission_number || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                        <input type="date" name="date_of_birth" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="${student?.date_of_birth || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                                        <select name="gender" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="male" ${student?.gender === 'male' ? 'selected' : ''}>Male</option>
                                            <option value="female" ${student?.gender === 'female' ? 'selected' : ''}>Female</option>
                                            <option value="other" ${student?.gender === 'other' ? 'selected' : ''}>Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Parent Name</label>
                                        <input type="text" name="parent_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="${student?.parent_name || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Parent Phone</label>
                                        <input type="tel" name="parent_phone" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="${student?.parent_phone || ''}">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    ${isEdit ? 'Update' : 'Create'}
                                </button>
                                <button type="button" id="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
    },

    attachModalListeners() {
        $('#closeModal').addEventListener('click', () => {
            $('#modalContainer').innerHTML = '';
        });

        $('#studentForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                await API.post('/api/students', data);
                $('#modalContainer').innerHTML = '';
                await this.loadStudents();
                this.showNotification('Student added successfully', 'success');
            } catch (error) {
                this.showNotification('Error: ' + error.message, 'error');
            }
        });
    },

    async deleteStudent(id) {
        if (!confirm('Are you sure you want to delete this student?')) return;

        try {
            await API.delete(`/api/students/${id}`);
            await this.loadStudents();
            this.showNotification('Student deleted successfully', 'success');
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    async viewStudent(id) {
        // TODO: Implement view student details
        alert('View student details - ID: ' + id);
    },

    goToPage(page) {
        this.currentPage = page;
        this.loadStudents();
    },

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} fade-in z-50`
        }, message);

        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
};

// Teachers Component - Full CRUD
const TeachersComponent = {
    teachers: [],
    currentPage: 1,

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadTeachers();
        this.attachEventListeners();
    },

    getTemplate() {
        return `
            <div class="px-4 sm:px-0">
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Teachers</h2>
                        <p class="mt-1 text-sm text-gray-500">Manage your teaching staff</p>
                    </div>
                    <button id="addTeacherBtn" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 focus:outline-none transform hover:scale-105 transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Teacher
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchTeachers" placeholder="Search teachers by name or employee ID..."
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg px-4 py-3">
                    </div>
                </div>

                <!-- Teachers Table -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <div id="teachersTableContainer">
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-green-600 mx-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    async loadTeachers(search = '') {
        try {
            const url = `/api/teachers?page=${this.currentPage}${search ? `&search=${search}` : ''}`;
            const data = await API.get(url);
            this.teachers = data.data;
            this.renderTable(data);
        } catch (error) {
            $('#teachersTableContainer').innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-600">Error: ${error.message}</p>
                </div>
            `;
        }
    },

    renderTable(data) {
        const container = $('#teachersTableContainer');
        if (!this.teachers.length) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500 mt-2">No teachers found</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Teacher</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Employee ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Specialization</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Qualification</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${this.teachers.map(teacher => `
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-semibold shadow-lg">
                                        ${teacher.user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">${teacher.user.name}</div>
                                        <div class="text-xs text-gray-500">${teacher.user.email}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-900">${teacher.employee_id}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${teacher.specialization || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${teacher.qualification || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    ${teacher.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                    ${teacher.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-green-600 hover:text-green-900 mr-3" onclick="TeachersComponent.viewTeacher(${teacher.id})">View</button>
                                <button class="text-red-600 hover:text-red-900" onclick="TeachersComponent.deleteTeacher(${teacher.id})">Delete</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">${data.from || 0}</span> to <span class="font-medium">${data.to || 0}</span> of
                        <span class="font-medium">${data.total || 0}</span> results
                    </div>
                    <div class="flex space-x-2">
                        ${data.prev_page_url ? `<button onclick="TeachersComponent.goToPage(${data.current_page - 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-green-50 text-green-600 font-medium transition-colors">Previous</button>` : ''}
                        ${data.next_page_url ? `<button onclick="TeachersComponent.goToPage(${data.current_page + 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-green-50 text-green-600 font-medium transition-colors">Next</button>` : ''}
                    </div>
                </div>
            </div>
        `;
    },

    attachEventListeners() {
        $('#addTeacherBtn').addEventListener('click', () => this.showAddModal());

        let searchTimeout;
        $('#searchTeachers').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.loadTeachers(e.target.value);
            }, 300);
        });
    },

    showAddModal() {
        const modal = this.createTeacherModal();
        $('#modalContainer').innerHTML = modal;
        $('#teacherModal').classList.remove('hidden');
        this.attachModalListeners();
    },

    createTeacherModal(teacher = null) {
        const isEdit = teacher !== null;
        return `
            <div id="teacherModal" class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form id="teacherForm">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                                <h3 class="text-xl font-bold text-white">${isEdit ? 'Edit' : 'Add New'} Teacher</h3>
                            </div>
                            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                                        <input type="text" name="name" required
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            value="${teacher?.user?.name || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email *</label>
                                        <input type="email" name="email" required
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            value="${teacher?.user?.email || ''}">
                                    </div>
                                    ${!isEdit ? `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Password *</label>
                                        <input type="password" name="password" required
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    </div>
                                    ` : ''}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Employee ID *</label>
                                        <input type="text" name="employee_id" required
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            value="${teacher?.employee_id || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="tel" name="phone"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            value="${teacher?.user?.phone || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Qualification</label>
                                        <input type="text" name="qualification"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="e.g., M.Ed, B.Ed"
                                            value="${teacher?.qualification || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Specialization</label>
                                        <input type="text" name="specialization"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="e.g., Mathematics, Science"
                                            value="${teacher?.specialization || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date of Joining</label>
                                        <input type="date" name="date_of_joining"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            value="${teacher?.date_of_joining || ''}">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-base font-medium text-white hover:from-green-600 hover:to-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transform hover:scale-105 transition-all">
                                    ${isEdit ? 'Update' : 'Create'}
                                </button>
                                <button type="button" id="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
    },

    attachModalListeners() {
        $('#closeModal').addEventListener('click', () => {
            $('#modalContainer').innerHTML = '';
        });

        $('#teacherForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                await API.post('/api/teachers', data);
                $('#modalContainer').innerHTML = '';
                await this.loadTeachers();
                this.showNotification('Teacher added successfully!', 'success');
            } catch (error) {
                this.showNotification('Error: ' + error.message, 'error');
            }
        });
    },

    async deleteTeacher(id) {
        if (!confirm('Are you sure you want to delete this teacher?')) return;

        try {
            await API.delete(`/api/teachers/${id}`);
            await this.loadTeachers();
            this.showNotification('Teacher deleted successfully', 'success');
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    async viewTeacher(id) {
        alert('View teacher details - ID: ' + id);
    },

    goToPage(page) {
        this.currentPage = page;
        this.loadTeachers();
    },

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-2xl text-white ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-red-600'} fade-in z-50 transform hover:scale-105 transition-all`
        }, message);

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

const ClassesComponent = {
    classes: [],
    currentPage: 1,

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadClasses();
        this.attachEventListeners();
    },

    getTemplate() {
        return `
            <div class="px-4 sm:px-0">
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Classes</h2>
                        <p class="mt-1 text-sm text-gray-500">Manage school classes and grades</p>
                    </div>
                    <button id="addClassBtn" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 focus:outline-none transform hover:scale-105 transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Class
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchClasses" placeholder="Search classes..."
                            class="shadow-sm focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg px-4 py-3">
                    </div>
                </div>

                <!-- Classes Grid -->
                <div id="classesGridContainer">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-purple-600 mx-auto"></div>
                    </div>
                </div>
            </div>
        `;
    },

    async loadClasses(search = '') {
        try {
            const url = `/api/classes?page=${this.currentPage}${search ? `&search=${search}` : ''}`;
            const data = await API.get(url);
            this.classes = data.data;
            this.renderGrid(data);
        } catch (error) {
            $('#classesGridContainer').innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-600">Error: ${error.message}</p>
                </div>
            `;
        }
    },

    renderGrid(data) {
        const container = $('#classesGridContainer');
        if (!this.classes.length) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <p class="text-gray-500 mt-2">No classes found</p>
                </div>
            `;
            return;
        }

        const gradientColors = [
            'from-blue-500 to-blue-600',
            'from-green-500 to-emerald-600',
            'from-purple-500 to-purple-600',
            'from-pink-500 to-rose-600',
            'from-yellow-500 to-orange-500',
            'from-indigo-500 to-indigo-600'
        ];

        container.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                ${this.classes.map((cls, index) => {
                    const gradient = gradientColors[index % gradientColors.length];
                    return `
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                            <div class="bg-gradient-to-br ${gradient} px-6 py-8 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold">${cls.name}</h3>
                                        <p class="text-sm opacity-90 mt-1">Grade ${cls.grade_level || 'N/A'}</p>
                                    </div>
                                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4">
                                <div class="space-y-3">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="font-medium">${cls.students_count || 0}</span>&nbsp;Students
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span>${cls.subjects_count || 0}</span>&nbsp;Subjects
                                    </div>
                                    ${cls.teacher_name ? `
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        ${cls.teacher_name}
                                    </div>
                                    ` : ''}
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                                    <button onclick="ClassesComponent.viewClass(${cls.id})" class="text-purple-600 hover:text-purple-900 text-sm font-medium">View Details</button>
                                    <button onclick="ClassesComponent.deleteClass(${cls.id})" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('')}
            </div>

            <div class="bg-white rounded-lg shadow px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">${data.from || 0}</span> to <span class="font-medium">${data.to || 0}</span> of
                        <span class="font-medium">${data.total || 0}</span> results
                    </div>
                    <div class="flex space-x-2">
                        ${data.prev_page_url ? `<button onclick="ClassesComponent.goToPage(${data.current_page - 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-purple-50 text-purple-600 font-medium transition-colors">Previous</button>` : ''}
                        ${data.next_page_url ? `<button onclick="ClassesComponent.goToPage(${data.current_page + 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-purple-50 text-purple-600 font-medium transition-colors">Next</button>` : ''}
                    </div>
                </div>
            </div>
        `;
    },

    attachEventListeners() {
        $('#addClassBtn').addEventListener('click', () => this.showAddModal());

        let searchTimeout;
        $('#searchClasses').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.loadClasses(e.target.value);
            }, 300);
        });
    },

    showAddModal() {
        const modal = this.createClassModal();
        $('#modalContainer').innerHTML = modal;
        $('#classModal').classList.remove('hidden');
        this.attachModalListeners();
    },

    createClassModal(cls = null) {
        const isEdit = cls !== null;
        return `
            <div id="classModal" class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form id="classForm">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                                <h3 class="text-xl font-bold text-white">${isEdit ? 'Edit' : 'Add New'} Class</h3>
                            </div>
                            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Class Name *</label>
                                        <input type="text" name="name" required
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="e.g., Grade 10-A"
                                            value="${cls?.name || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Grade Level</label>
                                        <input type="number" name="grade_level" min="1" max="12"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="1-12"
                                            value="${cls?.grade_level || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Section</label>
                                        <input type="text" name="section"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="e.g., A, B, C"
                                            value="${cls?.section || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                                        <input type="text" name="academic_year"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="e.g., 2024-2025"
                                            value="${cls?.academic_year || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Max Capacity</label>
                                        <input type="number" name="max_capacity"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="e.g., 40"
                                            value="${cls?.max_capacity || ''}">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-base font-medium text-white hover:from-purple-600 hover:to-pink-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transform hover:scale-105 transition-all">
                                    ${isEdit ? 'Update' : 'Create'}
                                </button>
                                <button type="button" id="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
    },

    attachModalListeners() {
        $('#closeModal').addEventListener('click', () => {
            $('#modalContainer').innerHTML = '';
        });

        $('#classForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                await API.post('/api/classes', data);
                $('#modalContainer').innerHTML = '';
                await this.loadClasses();
                this.showNotification('Class added successfully!', 'success');
            } catch (error) {
                this.showNotification('Error: ' + error.message, 'error');
            }
        });
    },

    async deleteClass(id) {
        if (!confirm('Are you sure you want to delete this class?')) return;

        try {
            await API.delete(`/api/classes/${id}`);
            await this.loadClasses();
            this.showNotification('Class deleted successfully', 'success');
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    async viewClass(id) {
        alert('View class details - ID: ' + id);
    },

    goToPage(page) {
        this.currentPage = page;
        this.loadClasses();
    },

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-2xl text-white ${type === 'success' ? 'bg-gradient-to-r from-purple-500 to-pink-600' : 'bg-gradient-to-r from-red-500 to-red-600'} fade-in z-50 transform hover:scale-105 transition-all`
        }, message);

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

const SubjectsComponent = {
    subjects: [],
    currentPage: 1,

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadSubjects();
        this.attachEventListeners();
    },

    getTemplate() {
        return `
            <div class="px-4 sm:px-0">
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">Subjects</h2>
                        <p class="mt-1 text-sm text-gray-500">Manage curriculum and subjects</p>
                    </div>
                    <button id="addSubjectBtn" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 focus:outline-none transform hover:scale-105 transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Subject
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchSubjects" placeholder="Search subjects..."
                            class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg px-4 py-3">
                    </div>
                </div>

                <!-- Subjects Grid -->
                <div id="subjectsGridContainer">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-yellow-600 mx-auto"></div>
                    </div>
                </div>
            </div>
        `;
    },

    async loadSubjects(search = '') {
        try {
            const url = `/api/subjects?page=${this.currentPage}${search ? `&search=${search}` : ''}`;
            const data = await API.get(url);
            this.subjects = data.data;
            this.renderGrid(data);
        } catch (error) {
            $('#subjectsGridContainer').innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-600">Error: ${error.message}</p>
                </div>
            `;
        }
    },

    renderGrid(data) {
        const container = $('#subjectsGridContainer');
        if (!this.subjects.length) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-gray-500 mt-2">No subjects found</p>
                </div>
            `;
            return;
        }

        const icons = ['📚', '🔬', '🧮', '🌍', '🎨', '💻', '⚛️', '📖', '🎭', '🎵'];

        container.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                ${this.subjects.map((subject, index) => `
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-xl border-l-4 border-yellow-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="text-4xl">${icons[index % icons.length]}</div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${subject.is_compulsory ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600'}">
                                    ${subject.is_compulsory ? 'Required' : 'Elective'}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">${subject.name}</h3>
                            <p class="text-sm text-gray-600 mb-3">${subject.code || 'No code'}</p>
                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                ${subject.credits || 0} Credits
                            </div>
                            <div class="pt-3 border-t border-gray-200 flex justify-between">
                                <button onclick="SubjectsComponent.viewSubject(${subject.id})" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">View</button>
                                <button onclick="SubjectsComponent.deleteSubject(${subject.id})" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>

            <div class="bg-white rounded-lg shadow px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">${data.from || 0}</span> to <span class="font-medium">${data.to || 0}</span> of
                        <span class="font-medium">${data.total || 0}</span> results
                    </div>
                    <div class="flex space-x-2">
                        ${data.prev_page_url ? `<button onclick="SubjectsComponent.goToPage(${data.current_page - 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-yellow-50 text-yellow-600 font-medium transition-colors">Previous</button>` : ''}
                        ${data.next_page_url ? `<button onclick="SubjectsComponent.goToPage(${data.current_page + 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-yellow-50 text-yellow-600 font-medium transition-colors">Next</button>` : ''}
                    </div>
                </div>
            </div>
        `;
    },

    attachEventListeners() {
        $('#addSubjectBtn').addEventListener('click', () => this.showAddModal());

        let searchTimeout;
        $('#searchSubjects').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.loadSubjects(e.target.value);
            }, 300);
        });
    },

    showAddModal() {
        const modal = this.createSubjectModal();
        $('#modalContainer').innerHTML = modal;
        $('#subjectModal').classList.remove('hidden');
        this.attachModalListeners();
    },

    createSubjectModal(subject = null) {
        const isEdit = subject !== null;
        return `
            <div id="subjectModal" class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form id="subjectForm">
                            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                                <h3 class="text-xl font-bold text-white">${isEdit ? 'Edit' : 'Add New'} Subject</h3>
                            </div>
                            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Subject Name *</label>
                                        <input type="text" name="name" required
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                                            placeholder="e.g., Mathematics"
                                            value="${subject?.name || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Subject Code</label>
                                        <input type="text" name="code"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                                            placeholder="e.g., MATH-101"
                                            value="${subject?.code || ''}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" rows="3"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                                            placeholder="Subject description...">${subject?.description || ''}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Credits</label>
                                        <input type="number" name="credits" min="0"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                                            placeholder="e.g., 4"
                                            value="${subject?.credits || ''}">
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_compulsory" id="is_compulsory"
                                            class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded"
                                            ${subject?.is_compulsory ? 'checked' : ''}>
                                        <label for="is_compulsory" class="ml-2 block text-sm text-gray-900">
                                            Compulsory Subject
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-base font-medium text-white hover:from-yellow-600 hover:to-orange-600 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transform hover:scale-105 transition-all">
                                    ${isEdit ? 'Update' : 'Create'}
                                </button>
                                <button type="button" id="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
    },

    attachModalListeners() {
        $('#closeModal').addEventListener('click', () => {
            $('#modalContainer').innerHTML = '';
        });

        $('#subjectForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            data.is_compulsory = $('#is_compulsory').checked ? 1 : 0;

            try {
                await API.post('/api/subjects', data);
                $('#modalContainer').innerHTML = '';
                await this.loadSubjects();
                this.showNotification('Subject added successfully!', 'success');
            } catch (error) {
                this.showNotification('Error: ' + error.message, 'error');
            }
        });
    },

    async deleteSubject(id) {
        if (!confirm('Are you sure you want to delete this subject?')) return;

        try {
            await API.delete(`/api/subjects/${id}`);
            await this.loadSubjects();
            this.showNotification('Subject deleted successfully', 'success');
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    async viewSubject(id) {
        alert('View subject details - ID: ' + id);
    },

    goToPage(page) {
        this.currentPage = page;
        this.loadSubjects();
    },

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-2xl text-white ${type === 'success' ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 'bg-gradient-to-r from-red-500 to-red-600'} fade-in z-50 transform hover:scale-105 transition-all`
        }, message);

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

const AttendanceComponent = {
    currentMonth: new Date(),
    selectedDate: new Date().toISOString().split('T')[0],
    selectedClass: null,
    students: [],

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadClasses();
        this.renderCalendar();
        this.attachEventListeners();
    },

    getTemplate() {
        const today = new Date().toLocaleDateString();
        return `
            <div class="px-4 sm:px-0">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">Attendance Management</h2>
                    <p class="mt-1 text-sm text-gray-500">Track and manage student attendance</p>
                </div>

                <div class="bg-white shadow-lg rounded-2xl p-6 mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Class</label>
                    <select id="classSelect" class="block w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Choose a class...</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2 bg-white shadow-lg rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-6 py-4">
                            <div class="flex justify-between items-center">
                                <button id="prevMonth" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg px-3 py-2 transition-all">
                                    ←
                                </button>
                                <h3 class="text-xl font-bold text-white" id="currentMonthYear"></h3>
                                <button id="nextMonth" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg px-3 py-2 transition-all">
                                    →
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div id="calendarGrid"></div>
                        </div>
                    </div>

                    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white">Quick Mark</h3>
                            <p class="text-sm text-white opacity-90">Selected: <span id="selectedDateDisplay">${today}</span></p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <button id="markAllPresent" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition-all shadow-lg">
                                    ✓ Mark All Present
                                </button>
                                <button id="markAllAbsent" class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all shadow-lg">
                                    ✗ Mark All Absent
                                </button>
                                <button id="viewTodayAttendance" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-indigo-700 transform hover:scale-105 transition-all shadow-lg">
                                    👁 View Attendance
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="studentsAttendanceList" class="bg-white shadow-lg rounded-2xl overflow-hidden hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Mark Attendance</h3>
                        <p class="text-sm text-gray-500">Click on each student to toggle attendance</p>
                    </div>
                    <div id="studentsListContainer" class="p-6">
                        <div class="text-center py-8 text-gray-500">
                            Select a class and date to mark attendance
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    async loadClasses() {
        try {
            const data = await API.get('/api/classes');
            const select = $('#classSelect');
            data.data.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.name;
                select.appendChild(option);
            });
        } catch (error) {
            this.showNotification('Error loading classes: ' + error.message, 'error');
        }
    },

    renderCalendar() {
        const year = this.currentMonth.getFullYear();
        const month = this.currentMonth.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date().toISOString().split('T')[0];

        $('#currentMonthYear').textContent = this.currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

        let html = '<div class="grid grid-cols-7 gap-2 mb-2">';
        ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
            html += `<div class="font-bold text-center p-2 text-gray-600 text-sm">${day}</div>`;
        });
        html += '</div><div class="grid grid-cols-7 gap-2">';

        for (let i = 0; i < firstDay; i++) {
            html += '<div class="p-2"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = date === today;
            const isSelected = date === this.selectedDate;

            html += `
                <div class="border rounded-lg p-3 text-center cursor-pointer transition-all
                    ${isToday ? 'border-indigo-500 bg-indigo-50' : 'hover:bg-gray-50'}
                    ${isSelected ? 'bg-indigo-500 text-white font-bold' : 'hover:border-indigo-300'}
                    transform hover:scale-105"
                    data-date="${date}"
                    onclick="AttendanceComponent.selectDate('${date}')">
                    <div class="text-sm">${day}</div>
                </div>
            `;
        }

        html += '</div>';
        $('#calendarGrid').innerHTML = html;
    },

    selectDate(date) {
        this.selectedDate = date;
        $('#selectedDateDisplay').textContent = new Date(date).toLocaleDateString();
        this.renderCalendar();

        if (this.selectedClass) {
            this.loadStudentsForAttendance();
        }
    },

    async loadStudentsForAttendance() {
        if (!this.selectedClass) {
            this.showNotification('Please select a class first', 'error');
            return;
        }

        try {
            const data = await API.get(`/api/students?class_id=${this.selectedClass}`);
            this.students = data.data || [];
            this.renderStudentsList();
            $('#studentsAttendanceList').classList.remove('hidden');
        } catch (error) {
            this.showNotification('Error loading students: ' + error.message, 'error');
        }
    },

    renderStudentsList() {
        const container = $('#studentsListContainer');

        if (!this.students.length) {
            container.innerHTML = '<div class="text-center py-8 text-gray-500">No students found in this class</div>';
            return;
        }

        container.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                ${this.students.map(student => `
                    <div id="student-${student.id}"
                         onclick="AttendanceComponent.toggleAttendance(${student.id})"
                         class="attendance-card bg-white border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:shadow-lg transition-all transform hover:scale-105"
                         data-status="present">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    ${student.user.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">${student.user.name}</p>
                                    <p class="text-sm text-gray-500">${student.admission_number}</p>
                                </div>
                            </div>
                            <div class="attendance-icon text-4xl">✓</div>
                        </div>
                    </div>
                `).join('')}
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="AttendanceComponent.submitAttendance()"
                        class="bg-gradient-to-r from-indigo-500 to-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-indigo-600 hover:to-blue-700 transform hover:scale-105 transition-all shadow-lg">
                    Save Attendance
                </button>
            </div>
        `;
    },

    toggleAttendance(studentId) {
        const card = $(`#student-${studentId}`);
        const currentStatus = card.dataset.status;
        const newStatus = currentStatus === 'present' ? 'absent' : 'present';

        card.dataset.status = newStatus;

        if (newStatus === 'present') {
            card.classList.remove('border-red-300', 'bg-red-50');
            card.classList.add('border-gray-200', 'bg-white');
            card.querySelector('.attendance-icon').textContent = '✓';
            card.querySelector('.attendance-icon').classList.remove('text-red-500');
            card.querySelector('.attendance-icon').classList.add('text-green-500');
        } else {
            card.classList.remove('border-gray-200', 'bg-white');
            card.classList.add('border-red-300', 'bg-red-50');
            card.querySelector('.attendance-icon').textContent = '✗';
            card.querySelector('.attendance-icon').classList.remove('text-green-500');
            card.querySelector('.attendance-icon').classList.add('text-red-500');
        }
    },

    async submitAttendance() {
        const attendanceData = [];

        $$('.attendance-card').forEach(card => {
            const studentId = card.id.split('-')[1];
            const status = card.dataset.status;
            attendanceData.push({
                student_id: studentId,
                date: this.selectedDate,
                status: status,
                class_id: this.selectedClass
            });
        });

        try {
            await API.post('/api/attendance', { attendance: attendanceData });
            this.showNotification('Attendance saved successfully!', 'success');
        } catch (error) {
            this.showNotification('Error saving attendance: ' + error.message, 'error');
        }
    },

    markAll(status) {
        if (!this.selectedClass) {
            this.showNotification('Please select a class first', 'error');
            return;
        }

        $$('.attendance-card').forEach(card => {
            card.dataset.status = status;

            if (status === 'present') {
                card.classList.remove('border-red-300', 'bg-red-50');
                card.classList.add('border-gray-200', 'bg-white');
                card.querySelector('.attendance-icon').textContent = '✓';
                card.querySelector('.attendance-icon').classList.remove('text-red-500');
                card.querySelector('.attendance-icon').classList.add('text-green-500');
            } else {
                card.classList.remove('border-gray-200', 'bg-white');
                card.classList.add('border-red-300', 'bg-red-50');
                card.querySelector('.attendance-icon').textContent = '✗';
                card.querySelector('.attendance-icon').classList.remove('text-green-500');
                card.querySelector('.attendance-icon').classList.add('text-red-500');
            }
        });
    },

    attachEventListeners() {
        $('#prevMonth').addEventListener('click', () => {
            this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
            this.renderCalendar();
        });

        $('#nextMonth').addEventListener('click', () => {
            this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
            this.renderCalendar();
        });

        $('#classSelect').addEventListener('change', (e) => {
            this.selectedClass = e.target.value;
            if (this.selectedClass) {
                this.loadStudentsForAttendance();
            }
        });

        $('#markAllPresent').addEventListener('click', () => this.markAll('present'));
        $('#markAllAbsent').addEventListener('click', () => this.markAll('absent'));
        $('#viewTodayAttendance').addEventListener('click', () => this.loadStudentsForAttendance());
    },

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-2xl text-white ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-red-600'} fade-in z-50 transform hover:scale-105 transition-all`
        }, message);

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

const GradesComponent = {
    grades: [],
    currentPage: 1,
    selectedClass: null,
    selectedSubject: null,

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadFilters();
        this.attachEventListeners();
    },

    getTemplate() {
        return `
            <div class="px-4 sm:px-0">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">Grades & Report Cards</h2>
                    <p class="mt-1 text-sm text-gray-500">Manage student grades and generate report cards</p>
                </div>

                <!-- Filters -->
                <div class="bg-white shadow-lg rounded-2xl p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                            <select id="classFilterGrades" class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                                <option value="">All Classes</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="subjectFilterGrades" class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                                <option value="">All Subjects</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Exam Type</label>
                            <select id="examTypeFilter" class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                                <option value="">All Exams</option>
                                <option value="midterm">Midterm</option>
                                <option value="final">Final</option>
                                <option value="quiz">Quiz</option>
                                <option value="assignment">Assignment</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button id="addGradeBtn" class="w-full bg-gradient-to-r from-pink-500 to-rose-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-pink-600 hover:to-rose-700 transform hover:scale-105 transition-all shadow-lg">
                                + Add Grade
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grades Table -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Student Grades</h3>
                            <p class="text-sm text-gray-500">View and manage all student grades</p>
                        </div>
                        <button id="generateReportBtn" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-blue-600 hover:to-indigo-700 transform hover:scale-105 transition-all shadow-lg">
                            📄 Generate Reports
                        </button>
                    </div>
                    <div id="gradesTableContainer">
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-pink-600 mx-auto"></div>
                        </div>
                    </div>
                </div>

                <!-- Grade Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="text-3xl font-bold mb-2">A+</div>
                        <div class="text-sm opacity-90">Excellent</div>
                        <div class="text-2xl font-bold mt-2" id="gradeAPlus">0</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="text-3xl font-bold mb-2">A-B</div>
                        <div class="text-sm opacity-90">Good</div>
                        <div class="text-2xl font-bold mt-2" id="gradeAB">0</div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
                        <div class="text-3xl font-bold mb-2">C-D</div>
                        <div class="text-sm opacity-90">Average</div>
                        <div class="text-2xl font-bold mt-2" id="gradeCD">0</div>
                    </div>
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="text-3xl font-bold mb-2">F</div>
                        <div class="text-sm opacity-90">Needs Improvement</div>
                        <div class="text-2xl font-bold mt-2" id="gradeF">0</div>
                    </div>
                </div>
            </div>
        `;
    },

    async loadFilters() {
        try {
            const [classesData, subjectsData] = await Promise.all([
                API.get('/api/classes'),
                API.get('/api/subjects')
            ]);

            const classSelect = $('#classFilterGrades');
            classesData.data.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.name;
                classSelect.appendChild(option);
            });

            const subjectSelect = $('#subjectFilterGrades');
            subjectsData.data.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });

            await this.loadGrades();
        } catch (error) {
            this.showNotification('Error loading filters: ' + error.message, 'error');
        }
    },

    async loadGrades() {
        try {
            let url = `/api/grades?page=${this.currentPage}`;
            if (this.selectedClass) url += `&class_id=${this.selectedClass}`;
            if (this.selectedSubject) url += `&subject_id=${this.selectedSubject}`;

            const data = await API.get(url);
            this.grades = data.data || [];
            this.renderTable(data);
            this.updateStatistics();
        } catch (error) {
            $('#gradesTableContainer').innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-600">Error: ${error.message}</p>
                </div>
            `;
        }
    },

    renderTable(data) {
        const container = $('#gradesTableContainer');

        if (!this.grades.length) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 mt-2">No grades found</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-pink-50 to-rose-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Exam Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Marks</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Percentage</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        ${this.grades.map(grade => {
                            const percentage = (grade.marks_obtained / grade.total_marks * 100).toFixed(2);
                            const gradeClass = this.getGradeColorClass(grade.grade);
                            return `
                                <tr class="hover:bg-pink-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white font-bold shadow-lg">
                                                ${grade.student?.user?.name?.charAt(0).toUpperCase() || '?'}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">${grade.student?.user?.name || 'Unknown'}</div>
                                                <div class="text-xs text-gray-500">${grade.student?.admission_number || 'N/A'}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${grade.subject?.name || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">${grade.exam_type || 'Regular'}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">${grade.marks_obtained}/${grade.total_marks}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold ${gradeClass}">${grade.grade}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${percentage}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="GradesComponent.editGrade(${grade.id})" class="text-pink-600 hover:text-pink-900 mr-3">Edit</button>
                                        <button onclick="GradesComponent.deleteGrade(${grade.id})" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">${data.from || 0}</span> to <span class="font-medium">${data.to || 0}</span> of
                        <span class="font-medium">${data.total || 0}</span> results
                    </div>
                    <div class="flex space-x-2">
                        ${data.prev_page_url ? `<button onclick="GradesComponent.goToPage(${data.current_page - 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-pink-50 text-pink-600 font-medium transition-colors">Previous</button>` : ''}
                        ${data.next_page_url ? `<button onclick="GradesComponent.goToPage(${data.current_page + 1})" class="px-4 py-2 border rounded-lg bg-white hover:bg-pink-50 text-pink-600 font-medium transition-colors">Next</button>` : ''}
                    </div>
                </div>
            </div>
        `;
    },

    getGradeColorClass(grade) {
        const gradeColors = {
            'A+': 'bg-green-100 text-green-800',
            'A': 'bg-green-100 text-green-700',
            'A-': 'bg-blue-100 text-blue-700',
            'B+': 'bg-blue-100 text-blue-600',
            'B': 'bg-blue-100 text-blue-700',
            'B-': 'bg-yellow-100 text-yellow-700',
            'C+': 'bg-yellow-100 text-yellow-700',
            'C': 'bg-yellow-100 text-yellow-700',
            'C-': 'bg-orange-100 text-orange-700',
            'D': 'bg-orange-100 text-orange-700',
            'F': 'bg-red-100 text-red-700'
        };
        return gradeColors[grade] || 'bg-gray-100 text-gray-700';
    },

    updateStatistics() {
        const stats = { 'A+': 0, 'A-B': 0, 'C-D': 0, 'F': 0 };

        this.grades.forEach(grade => {
            if (grade.grade === 'A+') stats['A+']++;
            else if (['A', 'A-', 'B+', 'B', 'B-'].includes(grade.grade)) stats['A-B']++;
            else if (['C+', 'C', 'C-', 'D'].includes(grade.grade)) stats['C-D']++;
            else if (grade.grade === 'F') stats['F']++;
        });

        $('#gradeAPlus').textContent = stats['A+'];
        $('#gradeAB').textContent = stats['A-B'];
        $('#gradeCD').textContent = stats['C-D'];
        $('#gradeF').textContent = stats['F'];
    },

    attachEventListeners() {
        $('#classFilterGrades').addEventListener('change', (e) => {
            this.selectedClass = e.target.value;
            this.loadGrades();
        });

        $('#subjectFilterGrades').addEventListener('change', (e) => {
            this.selectedSubject = e.target.value;
            this.loadGrades();
        });

        $('#examTypeFilter').addEventListener('change', () => {
            this.loadGrades();
        });

        $('#addGradeBtn').addEventListener('click', () => {
            alert('Add grade modal (implement similar to Students)');
        });

        $('#generateReportBtn').addEventListener('click', () => {
            this.generateReports();
        });
    },

    async deleteGrade(id) {
        if (!confirm('Are you sure you want to delete this grade?')) return;

        try {
            await API.delete(`/api/grades/${id}`);
            await this.loadGrades();
            this.showNotification('Grade deleted successfully', 'success');
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    editGrade(id) {
        alert('Edit grade modal - ID: ' + id);
    },

    goToPage(page) {
        this.currentPage = page;
        this.loadGrades();
    },

    generateReports() {
        this.showNotification('Generating report cards... (Feature coming soon!)', 'success');
    },

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-2xl text-white ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-red-600'} fade-in z-50 transform hover:scale-105 transition-all`
        }, message);

        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

// ===== MAIN APPLICATION =====
const SchoolApp = {
    store: new Store({
        currentUser: null,
        currentRoute: 'dashboard'
    }),

    router: new Router(),

    init() {
        this.setupRouter();
        this.setupEventListeners();
        this.loadInitialData();
    },

    setupRouter() {
        this.router.register('dashboard', () => DashboardComponent.render());
        this.router.register('students', () => StudentsComponent.render());
        this.router.register('teachers', () => TeachersComponent.render());
        this.router.register('classes', () => ClassesComponent.render());
        this.router.register('subjects', () => SubjectsComponent.render());
        this.router.register('attendance', () => AttendanceComponent.render());
        this.router.register('grades', () => GradesComponent.render());
        this.router.register('tokens', () => TokensComponent.render());
    },

    setupEventListeners() {
        // User menu toggle
        const userMenuButton = $('#userMenuButton');
        const userMenu = $('#userMenu');

        if (userMenuButton) {
            userMenuButton.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }

        // Logout
        const logoutBtn = $('#logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                try {
                    const response = await API.post('/logout', {});
                    if (response.success) {
                        window.location.href = response.redirect;
                    }
                } catch (error) {
                    console.error('Logout failed:', error);
                }
            });
        }
    },

    async loadInitialData() {
        try {
            // Load navigation only if mainNav element exists (backward compatibility)
            const mainNav = $('#mainNav');
            if (mainNav) {
                this.renderNavigation();
            }

            // Hide loading screen
            setTimeout(() => {
                const loadingScreen = $('#loadingScreen');
                if (loadingScreen) {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => loadingScreen.remove(), 300);
                }
            }, 500);

            // Load initial route
            this.router.navigate('dashboard');
        } catch (error) {
            console.error('Failed to load initial data:', error);
        }
    },

    renderNavigation() {
        const nav = $('#mainNav');
        const routes = [
            { name: 'Dashboard', route: 'dashboard' },
            { name: 'Students', route: 'students' },
            { name: 'Teachers', route: 'teachers' },
            { name: 'Classes', route: 'classes' },
            { name: 'Subjects', route: 'subjects' },
            { name: 'Attendance', route: 'attendance' },
            { name: 'Grades', route: 'grades' }
        ];

        nav.innerHTML = routes.map(({ name, route }) => `
            <a href="#"
               data-route="${route}"
               class="nav-link border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                ${name}
            </a>
        `).join('');

        // Attach navigation listeners
        $$('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const route = e.currentTarget.dataset.route;

                // Update active state
                $$('.nav-link').forEach(l => {
                    l.classList.remove('border-blue-500', 'text-gray-900');
                    l.classList.add('border-transparent', 'text-gray-500');
                });
                e.currentTarget.classList.remove('border-transparent', 'text-gray-500');
                e.currentTarget.classList.add('border-blue-500', 'text-gray-900');

                // Navigate
                this.router.navigate(route);
            });
        });

        // Set initial active state
        $$('.nav-link')[0]?.classList.add('border-blue-500', 'text-gray-900');
        $$('.nav-link')[0]?.classList.remove('border-transparent', 'text-gray-500');
    }
};

// Make components globally accessible for onclick handlers
window.StudentsComponent = StudentsComponent;
window.TeachersComponent = TeachersComponent;
window.ClassesComponent = ClassesComponent;
window.SubjectsComponent = SubjectsComponent;
window.AttendanceComponent = AttendanceComponent;
window.GradesComponent = GradesComponent;
window.TokensComponent = TokensComponent;
window.SchoolApp = SchoolApp;

// ===== REGISTRATION TOKENS COMPONENT =====
const TokensComponent = {
    tokens: [],
    currentPage: 1,
    filters: {},

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadTokens();
        this.attachEventListeners();
    },

    getTemplate() {
        return `
            <div class="px-4 sm:px-0">
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">Enrollment Tokens</h2>
                        <p class="mt-1 text-sm text-gray-500">Generate and manage student enrollment tokens</p>
                    </div>
                    <button id="addTokenBtn" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 focus:outline-none transform hover:scale-105 transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Generate Token
                    </button>
                </div>

                <!-- Public Enrollment Link -->
                <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Public Enrollment Link</h3>
                            <p class="text-xs text-gray-600 mt-1">Share this link with students to enroll using tokens</p>
                        </div>
                        <a href="/enroll" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Open Enrollment Form
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6 bg-white rounded-xl shadow-sm p-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="consumed">Consumed</option>
                                <option value="expired">Expired</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" id="searchInput" placeholder="Token code or class..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Tokens Table -->
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden" id="tokensContainer">
                    <div class="animate-pulse p-8">
                        <div class="h-8 bg-gray-200 rounded w-full mb-4"></div>
                        <div class="h-8 bg-gray-200 rounded w-full mb-4"></div>
                        <div class="h-8 bg-gray-200 rounded w-full"></div>
                    </div>
                </div>
            </div>
        `;
    },

    async loadTokens() {
        try {
            let url = '/api/tokens?per_page=15&page=' + this.currentPage;

            if (this.filters.status) url += '&status=' + this.filters.status;
            if (this.filters.search) url += '&search=' + this.filters.search;

            const response = await API.get(url);
            this.tokens = response.data;
            this.pagination = {
                current_page: response.current_page,
                last_page: response.last_page,
                total: response.total
            };

            this.renderTokens();
        } catch (error) {
            this.showNotification('Error loading tokens: ' + error.message, 'error');
        }
    },

    renderTokens() {
        const container = $('#tokensContainer');

        if (this.tokens.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tokens</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by generating a new token.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = `
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-teal-50 to-cyan-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Token Code</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Academic Year</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Intended Class</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${this.tokens.map(token => `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <code class="px-3 py-1 bg-gray-100 rounded font-mono text-sm font-bold text-gray-800">${token.token_code}</code>
                                    <button onclick="TokensComponent.copyToken('${token.token_code}')" class="ml-2 text-gray-400 hover:text-teal-600" title="Copy token">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${token.academic_year}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${token.intended_class}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    ${token.status === 'active' ? 'bg-green-100 text-green-800' :
                                      token.status === 'consumed' ? 'bg-blue-100 text-blue-800' :
                                      token.status === 'expired' ? 'bg-red-100 text-red-800' :
                                      'bg-gray-100 text-gray-800'}">
                                    ${token.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(token.expiry_date).toLocaleDateString()}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="TokensComponent.viewToken(${token.id})" class="text-teal-600 hover:text-teal-900 mr-3">View</button>
                                <button onclick="TokensComponent.deleteToken(${token.id})" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>

            <!-- Pagination -->
            ${this.pagination && this.pagination.last_page > 1 ? `
                <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button ${this.pagination.current_page === 1 ? 'disabled' : ''} onclick="TokensComponent.goToPage(${this.pagination.current_page - 1})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </button>
                        <button ${this.pagination.current_page === this.pagination.last_page ? 'disabled' : ''} onclick="TokensComponent.goToPage(${this.pagination.current_page + 1})" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">${this.tokens.length}</span> of <span class="font-medium">${this.pagination.total}</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                ${Array.from({length: this.pagination.last_page}, (_, i) => i + 1).map(page => `
                                    <button onclick="TokensComponent.goToPage(${page})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium ${page === this.pagination.current_page ? 'text-teal-600 bg-teal-50' : 'text-gray-700 hover:bg-gray-50'}">
                                        ${page}
                                    </button>
                                `).join('')}
                            </nav>
                        </div>
                    </div>
                </div>
            ` : ''}
        `;
    },

    attachEventListeners() {
        $('#addTokenBtn').addEventListener('click', () => this.openModal());

        const statusFilter = $('#statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.currentPage = 1;
                this.loadTokens();
            });
        }

        const searchInput = $('#searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.filters.search = e.target.value;
                    this.currentPage = 1;
                    this.loadTokens();
                }, 500);
            });
        }
    },

    openModal(token = null) {
        const isEdit = !!token;
        const modal = $('#modalContainer');

        modal.innerHTML = `
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto transform transition-all">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">${isEdit ? 'Edit Token' : 'Generate New Token'}</h3>
                    </div>
                    <form id="tokenForm" class="px-6 py-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year *</label>
                                <input type="text" name="academic_year" required value="${token?.academic_year || new Date().getFullYear() + '-' + (new Date().getFullYear() + 1)}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Intended Class *</label>
                                <input type="text" name="intended_class" required value="${token?.intended_class || ''}" placeholder="e.g., Grade 1-A" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                <input type="date" name="expiry_date" required value="${token?.expiry_date ? token.expiry_date.split('T')[0] : ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Note (Optional)</label>
                                <textarea name="note" rows="3" placeholder="Any additional notes..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-teal-500 focus:outline-none">${token?.note || ''}</textarea>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" id="closeModal" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white rounded-lg hover:from-teal-700 hover:to-cyan-700">
                                ${isEdit ? 'Update' : 'Generate'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        this.attachModalListeners(isEdit, token);
    },

    attachModalListeners(isEdit, token) {
        $('#closeModal').addEventListener('click', () => {
            $('#modalContainer').innerHTML = '';
        });

        $('#tokenForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                if (isEdit) {
                    await API.put(`/api/tokens/${token.id}`, data);
                    this.showNotification('Token updated successfully', 'success');
                } else {
                    await API.post('/api/tokens', data);
                    this.showNotification('Token generated successfully', 'success');
                }

                $('#modalContainer').innerHTML = '';
                await this.loadTokens();
            } catch (error) {
                this.showNotification('Error: ' + error.message, 'error');
            }
        });
    },

    async viewToken(id) {
        try {
            const token = await API.get(`/api/tokens/${id}`);
            this.openModal(token);
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    async deleteToken(id) {
        if (!confirm('Are you sure you want to delete this token?')) return;

        try {
            await API.delete(`/api/tokens/${id}`);
            await this.loadTokens();
            this.showNotification('Token deleted successfully', 'success');
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    copyToken(tokenCode) {
        navigator.clipboard.writeText(tokenCode);
        this.showNotification('Token copied to clipboard!', 'success');
    },

    goToPage(page) {
        this.currentPage = page;
        this.loadTokens();
    },

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} fade-in z-50`
        }, message);

        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
};
