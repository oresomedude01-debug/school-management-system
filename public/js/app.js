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
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Overview</h2>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                        ${this.renderStatCard('Students', stats.total_students, 'bg-blue-500', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z')}
                        ${this.renderStatCard('Teachers', stats.total_teachers, 'bg-green-500', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z')}
                        ${this.renderStatCard('Classes', stats.total_classes, 'bg-purple-500', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4')}
                        ${this.renderStatCard('Subjects', stats.total_subjects, 'bg-yellow-500', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253')}
                    </div>

                    <!-- Attendance Section -->
                    <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Today's Attendance</h3>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-green-600">Present</p>
                                    <p class="text-3xl font-bold text-green-700">${stats.present_today || 0}</p>
                                </div>
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-red-600">Absent</p>
                                    <p class="text-3xl font-bold text-red-700">${stats.absent_today || 0}</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-blue-600">Attendance Rate</p>
                                    <p class="text-3xl font-bold text-blue-700">${stats.attendance_rate || 0}%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Students -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recently Added Students</h3>
                            <div class="flow-root">
                                <ul class="divide-y divide-gray-200">
                                    ${stats.recent_students && stats.recent_students.length > 0
                                        ? stats.recent_students.map(student => `
                                            <li class="py-3">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                                            ${student.user.name.charAt(0).toUpperCase()}
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">${student.user.name}</p>
                                                        <p class="text-sm text-gray-500 truncate">${student.admission_number}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        `).join('')
                                        : '<li class="py-3 text-gray-500 text-center">No recent students</li>'
                                    }
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            content.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-600">Error loading dashboard: ${error.message}</p>
                </div>
            `;
        }
    },

    renderStatCard(title, value, bgColor, iconPath) {
        return `
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="${bgColor} rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">${title}</dt>
                                <dd class="text-3xl font-semibold text-gray-900">${value || 0}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        `;
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

// Simple placeholder components for other sections
const TeachersComponent = {
    render() {
        $('#mainContent').innerHTML = `
            <div class="px-4 sm:px-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Teachers</h2>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-600">Teachers management interface (follow Students component pattern)</p>
                </div>
            </div>
        `;
    }
};

const ClassesComponent = {
    render() {
        $('#mainContent').innerHTML = `
            <div class="px-4 sm:px-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Classes</h2>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-600">Classes management interface (follow Students component pattern)</p>
                </div>
            </div>
        `;
    }
};

const SubjectsComponent = {
    render() {
        $('#mainContent').innerHTML = `
            <div class="px-4 sm:px-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Subjects</h2>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-600">Subjects management interface (follow Students component pattern)</p>
                </div>
            </div>
        `;
    }
};

const AttendanceComponent = {
    render() {
        $('#mainContent').innerHTML = `
            <div class="px-4 sm:px-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Attendance</h2>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-600">Attendance tracking interface</p>
                </div>
            </div>
        `;
    }
};

const GradesComponent = {
    render() {
        $('#mainContent').innerHTML = `
            <div class="px-4 sm:px-0">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Grades</h2>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-600">Grades management interface</p>
                </div>
            </div>
        `;
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
            // Load navigation
            this.renderNavigation();

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
window.SchoolApp = SchoolApp;
