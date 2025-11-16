# Frontend Development Guide

## Overview
The School Management System uses **vanilla JavaScript** with a React-like component architecture. No npm, no build tools - just pure JavaScript with modern ES6+ features and Tailwind CSS via CDN.

## Architecture

### Component System
Components are objects with a `render()` method that generates HTML and handles interactions:

```javascript
const MyComponent = {
    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadData();
        this.attachEventListeners();
    },

    getTemplate() {
        return `<div>...</div>`;
    },

    async loadData() {
        const data = await API.get('/api/endpoint');
        this.renderData(data);
    },

    attachEventListeners() {
        $('#button').addEventListener('click', () => {...});
    }
};
```

### State Management
Simple Store class for reactive state:

```javascript
const store = new Store({
    currentUser: null,
    currentRoute: 'dashboard'
});

// Update state
store.setState({ currentUser: user });

// Subscribe to changes
store.subscribe((state) => {
    console.log('State updated:', state);
});
```

### Routing
Client-side routing without page reloads:

```javascript
router.register('myroute', () => MyComponent.render());
router.navigate('myroute');
```

### API Helper
Simplified fetch wrapper with CSRF protection:

```javascript
// GET request
const data = await API.get('/api/students');

// POST request
const result = await API.post('/api/students', formData);

// PUT request
const updated = await API.put(`/api/students/${id}`, formData);

// DELETE request
await API.delete(`/api/students/${id}`);
```

## Existing Components

### 1. Dashboard Component
**Location**: `public/js/app.js`
**Features**:
- Statistics cards
- Recent activity feed
- Quick actions
- **Charts** (using `public/js/charts.js`)

**Chart Integration**:
```javascript
new SimpleChart('myChart', {
    type: 'bar',  // or 'line', 'doughnut', 'pie'
    data: {
        labels: ['Mon', 'Tue', 'Wed'],
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: ['#3B82F6', '#10B981', '#8B5CF6']
        }]
    }
});
```

### 2. Students Component (CRUD Example)
**Location**: `public/js/app.js` - lines 250-450
**Features**:
- List view with pagination
- Search functionality
- Create modal form
- Update functionality
- Delete with confirmation
- Notifications

**Replication Pattern**:
To create Teachers, Classes, or Subjects CRUD, copy the Students component and modify:

1. **API Endpoints**: Change `/api/students` to `/api/teachers`
2. **Form Fields**: Update the modal form fields
3. **Table Columns**: Modify the table structure
4. **Component Name**: Rename to `TeachersComponent`

### 3. Placeholder Components
Currently, these components have basic templates:
- TeachersComponent
- ClassesComponent
- SubjectsComponent
- AttendanceComponent
- GradesComponent

## Adding New Features

### Creating a Complete CRUD Interface

#### Step 1: Define the Component
```javascript
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
                    <h2 class="text-2xl font-bold text-gray-900">Teachers</h2>
                    <button id="addTeacherBtn" class="btn-primary">
                        Add Teacher
                    </button>
                </div>
                <div id="teachersTableContainer"></div>
            </div>
        `;
    },

    async loadTeachers() {
        try {
            const data = await API.get(`/api/teachers?page=${this.currentPage}`);
            this.teachers = data.data;
            this.renderTable(data);
        } catch (error) {
            this.showNotification('Error: ' + error.message, 'error');
        }
    },

    renderTable(data) {
        const container = $('#teachersTableContainer');
        container.innerHTML = `
            <table class="min-w-full">
                <!-- Table structure here -->
            </table>
        `;
    },

    attachEventListeners() {
        $('#addTeacherBtn').addEventListener('click', () => this.showAddModal());
    },

    showAddModal() {
        // Similar to StudentsComponent.showAddModal()
    }
};
```

#### Step 2: Register the Route
```javascript
SchoolApp.router.register('teachers', () => TeachersComponent.render());
```

#### Step 3: Add Navigation Link
In `SchoolApp.renderNavigation()`, add:
```javascript
{ name: 'Teachers', route: 'teachers' }
```

#### Step 4: Make Globally Accessible
```javascript
window.TeachersComponent = TeachersComponent;
```

### Adding Charts to Dashboard

1. **Create Canvas Element**:
```html
<canvas id="myChart" width="400" height="250"></canvas>
```

2. **Initialize Chart**:
```javascript
new SimpleChart('myChart', {
    type: 'bar',
    data: {
        labels: ['Label 1', 'Label 2'],
        datasets: [{
            data: [value1, value2],
            backgroundColor: ['#3B82F6', '#10B981']
        }]
    }
});
```

### Creating Attendance Calendar View

```javascript
const AttendanceComponent = {
    currentMonth: new Date(),

    render() {
        const content = $('#mainContent');
        content.innerHTML = this.renderCalendar();
        this.attachEventListeners();
    },

    renderCalendar() {
        const year = this.currentMonth.getFullYear();
        const month = this.currentMonth.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let html = `
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Attendance Calendar</h2>
                    <div class="flex space-x-2">
                        <button id="prevMonth" class="btn-secondary">←</button>
                        <span class="px-4 py-2 font-semibold">
                            ${this.currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}
                        </span>
                        <button id="nextMonth" class="btn-secondary">→</button>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-2">
                    ${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day =>
                        `<div class="font-bold text-center p-2">${day}</div>`
                    ).join('')}
        `;

        // Empty cells for days before month starts
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="p-2"></div>';
        }

        // Calendar days
        for (let day = 1; day <= daysInMonth; day++) {
            html += `
                <div class="border rounded-lg p-2 hover:bg-blue-50 cursor-pointer text-center"
                     data-date="${year}-${month + 1}-${day}">
                    ${day}
                </div>
            `;
        }

        html += '</div></div>';
        return html;
    },

    attachEventListeners() {
        $('#prevMonth')?.addEventListener('click', () => {
            this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
            this.render();
        });

        $('#nextMonth')?.addEventListener('click', () => {
            this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
            this.render();
        });

        // Click on day to mark attendance
        $$('[data-date]').forEach(day => {
            day.addEventListener('click', (e) => {
                const date = e.currentTarget.dataset.date;
                this.showAttendanceModal(date);
            });
        });
    },

    showAttendanceModal(date) {
        // Show modal to mark attendance for the selected date
    }
};
```

### Creating Grades/Report Cards UI

```javascript
const GradesComponent = {
    async render() {
        const content = $('#mainContent');
        content.innerHTML = `
            <div class="px-4 sm:px-0">
                <h2 class="text-2xl font-bold mb-6">Grades & Report Cards</h2>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow p-4 mb-6 grid grid-cols-3 gap-4">
                    <select id="classFilter" class="form-select">
                        <option value="">Select Class</option>
                    </select>
                    <select id="subjectFilter" class="form-select">
                        <option value="">Select Subject</option>
                    </select>
                    <select id="examFilter" class="form-select">
                        <option value="">Select Exam</option>
                    </select>
                </div>

                <!-- Grades Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">Student</th>
                                <th class="px-6 py-3 text-left">Subject</th>
                                <th class="px-6 py-3 text-left">Marks</th>
                                <th class="px-6 py-3 text-left">Grade</th>
                                <th class="px-6 py-3 text-left">Percentage</th>
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="gradesTableBody">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        `;

        await this.loadGrades();
    },

    async loadGrades() {
        const data = await API.get('/api/grades');
        this.renderGradesTable(data);
    },

    renderGradesTable(grades) {
        const tbody = $('#gradesTableBody');
        tbody.innerHTML = grades.map(grade => `
            <tr>
                <td class="px-6 py-4">${grade.student.user.name}</td>
                <td class="px-6 py-4">${grade.subject.name}</td>
                <td class="px-6 py-4">${grade.marks_obtained}/${grade.total_marks}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold ${this.getGradeClass(grade.grade)}">
                        ${grade.grade}
                    </span>
                </td>
                <td class="px-6 py-4">${grade.percentage.toFixed(2)}%</td>
                <td class="px-6 py-4">
                    <button onclick="GradesComponent.editGrade(${grade.id})" class="text-blue-600 hover:text-blue-900">Edit</button>
                </td>
            </tr>
        `).join('');
    },

    getGradeClass(grade) {
        const gradeColors = {
            'A+': 'bg-green-100 text-green-800',
            'A': 'bg-green-100 text-green-700',
            'B': 'bg-blue-100 text-blue-700',
            'C': 'bg-yellow-100 text-yellow-700',
            'D': 'bg-orange-100 text-orange-700',
            'F': 'bg-red-100 text-red-700'
        };
        return gradeColors[grade] || 'bg-gray-100 text-gray-700';
    }
};
```

## Styling Guide

### Using Tailwind CSS
All components use Tailwind utility classes (loaded via CDN).

**Common Patterns**:

```html
<!-- Card -->
<div class="bg-white rounded-2xl shadow-lg p-6">

<!-- Button Primary -->
<button class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2.5 rounded-full hover:shadow-lg transform hover:scale-105 transition-all">

<!-- Input Field -->
<input class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

<!-- Table -->
<table class="min-w-full divide-y divide-gray-200">

<!-- Badge -->
<span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
```

### Animations
Custom animations defined in component styles:

```javascript
style="animation: fadeInUp 0.6s ease-out 0.5s both;"
```

**Available Animations**:
- `fadeInUp` - Fade in from bottom
- `fadeInDown` - Fade in from top
- `fadeIn` - Simple fade in
- `slideInRight` - Slide from right
- `float` - Floating effect

## Best Practices

1. **Always use API Helper** instead of raw fetch
2. **Handle errors gracefully** with try-catch
3. **Show loading states** during async operations
4. **Use notifications** for user feedback
5. **Make components reusable** and modular
6. **Follow the established pattern** from StudentsComponent
7. **Test with real API endpoints** before deployment
8. **Add animations** for better UX
9. **Keep components focused** - one responsibility per component
10. **Document complex logic** with comments

## File Structure

```
public/
├── js/
│   ├── app.js          # Main application (Students CRUD included)
│   ├── charts.js       # Chart library (Bar, Line, Doughnut, Pie)
│   └── landing.js      # Landing page animations
resources/
└── views/
    ├── layout.blade.php
    ├── login.blade.php
    ├── dashboard.blade.php
    └── welcome.blade.php  # Landing page
```

## Next Steps

To complete the frontend:

1. **Copy StudentsComponent** to create TeachersComponent, ClassesComponent, SubjectsComponent
2. **Implement AttendanceComponent** using the calendar pattern above
3. **Implement GradesComponent** using the grades pattern above
4. **Add role-based views** (student portal, teacher portal, parent portal)
5. **Enhance dashboard charts** with real data
6. **Add more animations** throughout
7. **Implement search filters** in list views
8. **Add export functionality** (PDF, Excel)
9. **Create print-friendly reports**
10. **Add dark mode toggle**

## Resources

- **Tailwind CSS Docs**: https://tailwindcss.com/docs
- **MDN Web Docs**: https://developer.mozilla.org/
- **Laravel Blade**: https://laravel.com/docs/blade
- **Fetch API**: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API

## Support

For issues or questions:
1. Check the Students component implementation as a reference
2. Review the API endpoints in `routes/web.php`
3. Test with browser DevTools console
4. Check Laravel logs for backend errors

---

**Built with ❤️ - No npm, no webpack, just pure JavaScript magic!**
