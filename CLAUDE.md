# CLAUDE.md - AI Assistant Guide

## Project Overview

**School Management System** is a Laravel 12-based application with a **vanilla JavaScript** frontend (no build tools required). The system provides comprehensive school administration features including student management, teacher management, class organization, attendance tracking, and grade management.

### Key Characteristics
- **No npm install required for deployment** - Uses CDN for Tailwind CSS
- **Vanilla JavaScript** with React-like component architecture
- **cPanel-friendly** - Designed for easy shared hosting deployment
- **Role-based access control** - Admin, Teacher, Student roles
- **RESTful API** - JSON-based backend communication
- **Single Page Application** - Smooth navigation without page reloads

---

## Technology Stack

### Backend
- **Framework**: Laravel 12
- **PHP Version**: 8.2+
- **Database**: MySQL 5.7+
- **ORM**: Eloquent
- **Authentication**: Laravel's built-in auth with sessions

### Frontend
- **JavaScript**: Vanilla ES6+ (no frameworks, no build tools)
- **CSS Framework**: Tailwind CSS 4.0 (loaded via CDN)
- **Charts**: Custom SimpleChart library (`public/js/charts.js`)
- **Icons**: Heroicons (inline SVG)

### Development Tools
- **Package Manager**: Composer (backend only)
- **Testing**: PHPUnit
- **Code Style**: Laravel Pint
- **Server**: Apache with mod_rewrite

---

## Directory Structure

```
school-management-system/
├── app/
│   ├── Http/
│   │   └── Controllers/          # All API & page controllers
│   │       ├── AuthController.php
│   │       ├── DashboardController.php
│   │       ├── StudentController.php
│   │       ├── TeacherController.php
│   │       ├── ClassController.php
│   │       ├── SubjectController.php
│   │       ├── AttendanceController.php
│   │       ├── GradeController.php
│   │       └── LocalizationController.php
│   ├── Models/                   # Eloquent models
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   ├── SchoolClass.php
│   │   ├── Subject.php
│   │   ├── Enrollment.php
│   │   ├── Attendance.php
│   │   ├── Grade.php
│   │   └── RegistrationToken.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/               # Database schema migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_11_16_074707_add_role_to_users_table.php
│   │   ├── 2025_11_16_074708_create_students_table.php
│   │   ├── 2025_11_16_074708_create_teachers_table.php
│   │   ├── 2025_11_16_074709_create_classes_table.php
│   │   ├── 2025_11_16_074709_create_subjects_table.php
│   │   ├── 2025_11_16_074710_create_enrollments_table.php
│   │   ├── 2025_11_16_074711_create_attendance_table.php
│   │   ├── 2025_11_16_074711_create_grades_table.php
│   │   └── 2025_11_16_193741_create_registration_tokens_table.php
│   ├── seeders/
│   │   └── DatabaseSeeder.php   # Sample data generator
│   └── factories/
│       └── UserFactory.php
│
├── public/
│   ├── js/
│   │   ├── app.js                # Main application (React-like components)
│   │   ├── charts.js             # Custom chart library
│   │   ├── dashboard-roles.js    # Role-based dashboard logic
│   │   ├── i18n.js               # Internationalization helper
│   │   └── landing.js            # Landing page scripts
│   └── index.php                 # Laravel entry point
│
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── app.blade.php         # Landing page
│   │   ├── login.blade.php       # Login page
│   │   ├── dashboard.blade.php   # Main dashboard layout
│   │   ├── students/
│   │   │   └── index.blade.php
│   │   ├── teachers/
│   │   │   └── index.blade.php
│   │   └── ...
│   └── js/
│       ├── app.js                # Vite entrypoint (optional)
│       └── bootstrap.js
│
├── routes/
│   ├── web.php                   # All routes (web + API)
│   └── console.php               # Artisan commands
│
├── lang/                         # Translations
│   ├── en/
│   │   └── app.php
│   └── ar/
│       └── app.php
│
├── storage/                      # Logs, cache, sessions
├── tests/                        # PHPUnit tests
├── .env.example                  # Environment template
├── composer.json                 # PHP dependencies
├── package.json                  # Optional frontend tools
├── README.md                     # User documentation
├── DEPLOYMENT_GUIDE.md           # cPanel deployment guide
└── FRONTEND_GUIDE.md             # Vanilla JS architecture guide
```

---

## Database Schema & Relationships

### Core Models

#### User
**File**: `app/Models/User.php`
**Table**: `users`
**Purpose**: Polymorphic user model for all system users

**Fields**:
- `id`, `name`, `email`, `password`, `role` (admin/teacher/student)
- `phone`, `address`, `remember_token`, `timestamps`

**Relationships**:
- `hasOne(Student::class)` - Student profile
- `hasOne(Teacher::class)` - Teacher profile

**Methods**:
- `isAdmin()`, `isTeacher()`, `isStudent()` - Role checks

#### Student
**File**: `app/Models/Student.php`
**Table**: `students`

**Fields**:
- `user_id` (FK), `admission_number` (unique)
- `date_of_birth`, `gender`
- `parent_name`, `parent_phone`, `parent_email`
- `medical_info`, `status` (active/inactive/graduated)

**Relationships**:
- `belongsTo(User::class)` - User account
- `hasMany(Enrollment::class)` - Class enrollments
- `belongsToMany(SchoolClass::class)` via enrollments - Classes
- `hasMany(Attendance::class)` - Attendance records
- `hasMany(Grade::class)` - Grade records

#### Teacher
**File**: `app/Models/Teacher.php`
**Table**: `teachers`

**Fields**:
- `user_id` (FK), `employee_id` (unique)
- `date_of_joining`, `qualification`, `specialization`
- `status` (active/inactive)

**Relationships**:
- `belongsTo(User::class)` - User account
- `hasMany(SchoolClass::class)` - Assigned classes

#### SchoolClass
**File**: `app/Models/SchoolClass.php`
**Table**: `classes`

**Fields**:
- `name`, `grade_level`, `section`
- `teacher_id` (FK), `academic_year`
- `capacity`, `room_number`, `status`

**Relationships**:
- `belongsTo(Teacher::class)` - Class teacher
- `belongsToMany(Subject::class)` - Subjects taught
- `hasMany(Enrollment::class)` - Student enrollments

#### Subject
**File**: `app/Models/Subject.php`
**Table**: `subjects`

**Fields**:
- `name`, `code` (unique), `description`
- `credits`, `status` (active/inactive)

**Relationships**:
- `belongsToMany(SchoolClass::class)` - Classes teaching this subject

#### Enrollment
**File**: `app/Models/Enrollment.php`
**Table**: `enrollments`

**Purpose**: Many-to-many relationship between students and classes

**Fields**:
- `student_id` (FK), `class_id` (FK)
- `academic_year`, `enrollment_date`
- `status` (active/completed/dropped)

#### Attendance
**File**: `app/Models/Attendance.php`
**Table**: `attendance`

**Fields**:
- `student_id` (FK), `class_id` (FK)
- `date`, `status` (present/absent/late)
- `remarks`

#### Grade
**File**: `app/Models/Grade.php`
**Table**: `grades`

**Fields**:
- `student_id` (FK), `subject_id` (FK), `class_id` (FK)
- `exam_type` (Midterm/Final/Quiz)
- `marks_obtained`, `total_marks`, `grade` (A+, A, B+, etc.)
- `academic_year`, `remarks`

#### RegistrationToken
**File**: `app/Models/RegistrationToken.php`
**Table**: `registration_tokens`

**Purpose**: Self-enrollment tokens for students

**Fields**:
- `token` (unique), `class_id` (FK)
- `max_uses`, `used_count`
- `expires_at`, `is_active`

---

## API Endpoints & Routing

### Route Structure
**File**: `routes/web.php`

All routes follow RESTful conventions. API routes return JSON, page routes return Blade views.

### Public Routes
```php
GET  /                  # Landing page
GET  /login             # Login page (AuthController@showLogin)
POST /login             # Login action (AuthController@login)
POST /logout            # Logout action (AuthController@logout)
```

### Protected Routes (requires auth middleware)

#### Dashboard
```php
GET  /dashboard              # Dashboard page
GET  /api/dashboard/stats    # Dashboard statistics (JSON)
```

#### Students (CRUD)
```php
GET    /students                    # Students page (Blade view)
GET    /api/students                # List students (JSON, paginated)
POST   /api/students                # Create student
GET    /api/students/{student}      # Show student details
PUT    /api/students/{student}      # Update student
DELETE /api/students/{student}      # Delete student
```

**Controller**: `app/Http/Controllers/StudentController.php`

**Query Parameters** (index):
- `status` - Filter by status (active/inactive/graduated)
- `search` - Search by name, email, or admission number
- `per_page` - Results per page (default: 15)

#### Teachers (CRUD)
```php
GET    /teachers                    # Teachers page
GET    /api/teachers                # List teachers (JSON)
POST   /api/teachers                # Create teacher
GET    /api/teachers/{teacher}      # Show teacher
PUT    /api/teachers/{teacher}      # Update teacher
DELETE /api/teachers/{teacher}      # Delete teacher
```

**Controller**: `app/Http/Controllers/TeacherController.php`

#### Classes (CRUD)
```php
GET    /classes                     # Classes page
GET    /api/classes                 # List classes (JSON)
POST   /api/classes                 # Create class
GET    /api/classes/{class}         # Show class
PUT    /api/classes/{class}         # Update class
DELETE /api/classes/{class}         # Delete class
```

**Controller**: `app/Http/Controllers/ClassController.php`

#### Subjects (CRUD)
```php
GET    /subjects                    # Subjects page
GET    /api/subjects                # List subjects (JSON)
POST   /api/subjects                # Create subject
GET    /api/subjects/{subject}      # Show subject
PUT    /api/subjects/{subject}      # Update subject
DELETE /api/subjects/{subject}      # Delete subject
```

#### Attendance
```php
GET    /attendance                  # Attendance page
GET    /api/attendance              # List attendance records
POST   /api/attendance              # Record attendance
GET    /api/attendance/{id}         # Show attendance record
PUT    /api/attendance/{id}         # Update attendance
```

#### Grades
```php
GET    /grades                      # Grades page
GET    /api/grades                  # List grades
POST   /api/grades                  # Create grade record
GET    /api/grades/{grade}          # Show grade
PUT    /api/grades/{grade}          # Update grade
DELETE /api/grades/{grade}          # Delete grade
```

#### Localization
```php
GET    /api/translations/{locale}   # Get translations for locale
POST   /api/locale                  # Set user locale preference
GET    /api/locale                  # Get current locale
```

---

## Backend Conventions

### Controller Pattern

All controllers follow this structure:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelName;
use Illuminate\Support\Facades\DB;

class ModelController extends Controller
{
    // List all (with pagination, filtering, search)
    public function index(Request $request)
    {
        $query = ModelName::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Apply search
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $items = $query->latest()->paginate($request->get('per_page', 15));
        return response()->json($items);
    }

    // Create new record
    public function store(Request $request)
    {
        $validated = $request->validate([/* rules */]);

        DB::beginTransaction();
        try {
            $item = ModelName::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Created successfully',
                'data' => $item
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show single record
    public function show(ModelName $item)
    {
        return response()->json($item->load(['relationships']));
    }

    // Update record
    public function update(Request $request, ModelName $item)
    {
        $validated = $request->validate([/* rules */]);

        DB::beginTransaction();
        try {
            $item->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'data' => $item
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete record
    public function destroy(ModelName $item)
    {
        try {
            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
```

### Model Conventions

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelName extends Model
{
    // Mass assignable attributes
    protected $fillable = [
        'field1',
        'field2',
    ];

    // Date casting
    protected $casts = [
        'date_field' => 'date',
        'datetime_field' => 'datetime',
    ];

    // Relationships
    public function relatedModel(): BelongsTo
    {
        return $this->belongsTo(RelatedModel::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChildModel::class);
    }
}
```

### Migration Naming

**Pattern**: `YYYY_MM_DD_HHMMSS_action_table_name.php`

**Examples**:
- `2025_11_16_074708_create_students_table.php`
- `2025_11_16_074707_add_role_to_users_table.php`

**Migration Structure**:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('field_name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

### Seeder Pattern

**File**: `database/seeders/DatabaseSeeder.php`

```php
public function run(): void
{
    // Create admin
    User::create([...]);

    // Create related models in order of dependencies
    $subjects = [];
    foreach ($subjectsData as $data) {
        $subjects[] = Subject::create($data);
    }

    // Use transactions for complex operations
    DB::transaction(function () {
        // Create related records
    });
}
```

---

## Frontend Architecture (Vanilla JavaScript)

### Core Concepts

The frontend uses a **custom React-like component system** without any build tools or npm dependencies.

**File**: `public/js/app.js` (125KB - contains all components)

### Utility Functions

```javascript
// DOM selectors
const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => document.querySelectorAll(selector);

// Create elements programmatically
const createElement = (tag, props = {}, ...children) => {
    const element = document.createElement(tag);
    Object.entries(props).forEach(([key, value]) => {
        if (key === 'className') element.className = value;
        else if (key.startsWith('on')) {
            element.addEventListener(key.substring(2).toLowerCase(), value);
        } else {
            element.setAttribute(key, value);
        }
    });
    children.forEach(child => element.appendChild(child));
    return element;
};
```

### API Helper

**Location**: `public/js/app.js` lines 31-77

```javascript
const API = {
    async request(url, options = {}) {
        const defaultOptions = {
            headers: {
                'X-CSRF-TOKEN': csrfToken,  // Injected from Blade
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        };

        const response = await fetch(url, { ...defaultOptions, ...options });
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }

        return data;
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
```

**Usage**:
```javascript
// GET request
const students = await API.get('/api/students');

// POST request
const newStudent = await API.post('/api/students', {
    name: 'John Doe',
    email: 'john@example.com'
});

// PUT request
await API.put(`/api/students/${id}`, updatedData);

// DELETE request
await API.delete(`/api/students/${id}`);
```

### State Management

**Location**: `public/js/app.js` lines 79-105

```javascript
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

// Global store instance
const store = new Store({
    currentUser: null,
    currentRoute: 'dashboard'
});
```

### Router

**Location**: `public/js/app.js` lines 107-124

```javascript
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

// Global router instance
const router = new Router();
```

### Component Pattern

All components follow this structure:

```javascript
const MyComponent = {
    // Component state
    data: [],
    currentPage: 1,
    filters: {},

    // Main render method - called to display component
    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();  // Set initial HTML
        await this.loadData();                   // Fetch data from API
        this.attachEventListeners();             // Bind event handlers
    },

    // Generate HTML template
    getTemplate() {
        return `
            <div class="container">
                <div id="header">...</div>
                <div id="dataContainer">
                    <!-- Data will be inserted here -->
                </div>
            </div>
        `;
    },

    // Load data from API
    async loadData() {
        try {
            const response = await API.get('/api/endpoint');
            this.data = response.data;
            this.renderData();
        } catch (error) {
            this.showNotification('Error loading data', 'error');
        }
    },

    // Render data into DOM
    renderData() {
        const container = $('#dataContainer');
        container.innerHTML = this.data.map(item => `
            <div class="item">
                ${item.name}
                <button onclick="MyComponent.edit(${item.id})">Edit</button>
            </div>
        `).join('');
    },

    // Event handlers
    attachEventListeners() {
        $('#addButton').addEventListener('click', () => this.openModal());
        $('#searchInput').addEventListener('input', (e) => this.search(e.target.value));
    },

    // CRUD operations
    async create(data) {
        await API.post('/api/endpoint', data);
        await this.loadData();
        this.showNotification('Created successfully', 'success');
    },

    async edit(id) {
        const item = await API.get(`/api/endpoint/${id}`);
        this.openModal(item);
    },

    async update(id, data) {
        await API.put(`/api/endpoint/${id}`, data);
        await this.loadData();
        this.showNotification('Updated successfully', 'success');
    },

    async delete(id) {
        if (!confirm('Are you sure?')) return;
        await API.delete(`/api/endpoint/${id}`);
        await this.loadData();
        this.showNotification('Deleted successfully', 'success');
    },

    // UI helpers
    showNotification(message, type) {
        const notification = createElement('div', {
            className: `notification ${type}`
        }, message);
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    },

    openModal(data = null) {
        // Modal implementation
    }
};
```

### Existing Components

**Location**: All in `public/js/app.js`

1. **DashboardComponent** (lines 129-450)
   - Statistics cards
   - Charts (attendance, enrollment, distribution)
   - Recent activity feed
   - Quick actions

2. **StudentsComponent** (lines 452-670)
   - Student list with pagination
   - Search and filters
   - Create/Edit modal
   - Delete confirmation
   - View student details

3. **TeachersComponent** (lines 672-...)
   - Teacher management
   - Similar CRUD pattern to Students

4. **ClassesComponent**
   - Class management
   - Teacher assignments
   - Student enrollments

5. **SubjectsComponent**
   - Subject catalog
   - Credit system

6. **AttendanceComponent**
   - Daily attendance tracking
   - Bulk operations

7. **GradesComponent**
   - Grade entry
   - Report generation

### Navigation System

**SPA Navigation** (no page reloads):

```javascript
// Navigation links are intercepted
document.querySelectorAll('[data-route]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const route = e.currentTarget.dataset.route;

        // Update URL without reload
        history.pushState({}, '', `/${route}`);

        // Navigate to component
        router.navigate(route);

        // Update active states
        updateActiveStates(route);
    });
});

// Browser back/forward buttons
window.addEventListener('popstate', () => {
    const route = location.pathname.slice(1) || 'dashboard';
    router.navigate(route);
});
```

### Chart System

**File**: `public/js/charts.js`

**Custom SimpleChart class** for visualizations (no external libraries):

```javascript
// Usage
new SimpleChart('canvasId', {
    type: 'bar',  // or 'line', 'doughnut', 'pie'
    data: {
        labels: ['Jan', 'Feb', 'Mar'],
        datasets: [{
            label: 'Sales',
            data: [10, 20, 30],
            backgroundColor: '#3B82F6',
            borderColor: '#2563EB'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
```

### Internationalization (i18n)

**File**: `public/js/i18n.js`

**Backend translations**: `lang/en/app.php`, `lang/ar/app.php`

```javascript
// Usage in components
const t = (key) => i18n.translate(key);

// In templates
`<h1>${t('dashboard.title')}</h1>`

// Switch locale
await i18n.setLocale('ar');  // Fetches translations and re-renders
```

---

## Development Workflows

### Adding a New Feature (Full Stack)

#### 1. Create Migration

```bash
php artisan make:migration create_feature_table
```

Edit `database/migrations/YYYY_MM_DD_HHMMSS_create_feature_table.php`:

```php
public function up(): void
{
    Schema::create('features', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
}
```

Run migration:
```bash
php artisan migrate
```

#### 2. Create Model

```bash
php artisan make:model Feature
```

Edit `app/Models/Feature.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['name', 'description', 'status'];

    // Add relationships if needed
}
```

#### 3. Create Controller

```bash
php artisan make:controller FeatureController
```

Edit `app/Http/Controllers/FeatureController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        $query = Feature::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $features = $query->latest()->paginate(15);
        return response()->json($features);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);

        DB::beginTransaction();
        try {
            $feature = Feature::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Feature created successfully',
                'data' => $feature
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Add show, update, destroy methods...
}
```

#### 4. Add Routes

Edit `routes/web.php`:

```php
Route::middleware(['auth'])->group(function () {
    // Page route
    Route::get('/features', function () {
        return view('features.index');
    })->name('features');

    // API routes
    Route::get('/api/features', [FeatureController::class, 'index']);
    Route::post('/api/features', [FeatureController::class, 'store']);
    Route::get('/api/features/{feature}', [FeatureController::class, 'show']);
    Route::put('/api/features/{feature}', [FeatureController::class, 'update']);
    Route::delete('/api/features/{feature}', [FeatureController::class, 'destroy']);
});
```

#### 5. Create Blade View

Create `resources/views/features/index.blade.php`:

```blade
@extends('layout')

@section('content')
<div id="mainContent">
    <!-- Content will be rendered by JavaScript -->
</div>

<script>
    // Component will load here
    document.addEventListener('DOMContentLoaded', () => {
        FeaturesComponent.render();
    });
</script>
@endsection
```

#### 6. Create Frontend Component

Edit `public/js/app.js` and add:

```javascript
const FeaturesComponent = {
    features: [],
    currentPage: 1,

    async render() {
        const content = $('#mainContent');
        content.innerHTML = this.getTemplate();
        await this.loadFeatures();
        this.attachEventListeners();
    },

    getTemplate() {
        return `
            <div class="px-4 sm:px-0">
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold">Features</h2>
                        <p class="mt-1 text-sm text-gray-500">Manage features</p>
                    </div>
                    <button id="addFeatureBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Add Feature
                    </button>
                </div>

                <div id="featuresContainer" class="bg-white shadow rounded-lg">
                    <!-- Features will be loaded here -->
                </div>
            </div>
        `;
    },

    async loadFeatures() {
        try {
            const response = await API.get('/api/features');
            this.features = response.data;
            this.renderFeatures();
        } catch (error) {
            this.showNotification('Error loading features', 'error');
        }
    },

    renderFeatures() {
        const container = $('#featuresContainer');
        container.innerHTML = `
            <ul class="divide-y divide-gray-200">
                ${this.features.map(feature => `
                    <li class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">${feature.name}</h3>
                                <p class="text-gray-600">${feature.description || ''}</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="FeaturesComponent.edit(${feature.id})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                <button onclick="FeaturesComponent.delete(${feature.id})" class="text-red-600 hover:text-red-800">Delete</button>
                            </div>
                        </div>
                    </li>
                `).join('')}
            </ul>
        `;
    },

    attachEventListeners() {
        $('#addFeatureBtn').addEventListener('click', () => this.openModal());
    },

    async delete(id) {
        if (!confirm('Are you sure?')) return;
        await API.delete(`/api/features/${id}`);
        await this.loadFeatures();
        this.showNotification('Deleted successfully', 'success');
    },

    // Add other methods...

    showNotification(message, type) {
        const notification = createElement('div', {
            className: `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} z-50`
        }, message);
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
};

// Register route
router.register('features', () => FeaturesComponent.render());
```

#### 7. Add Navigation Link

Edit `resources/views/dashboard.blade.php`:

```blade
<!-- Add to sidebar -->
<a href="/features" data-route="features" class="nav-link">
    <svg><!-- icon --></svg>
    Features
</a>
```

#### 8. Update Seeder (Optional)

Edit `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    // Existing seeders...

    // Add feature seeds
    Feature::create([
        'name' => 'Sample Feature',
        'description' => 'This is a sample feature',
        'status' => 'active'
    ]);
}
```

#### 9. Test

```bash
# Reset database with seeds
php artisan migrate:fresh --seed

# Start server
php artisan serve

# Visit http://localhost:8000/features
```

---

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/StudentTest.php

# Run with coverage
php artisan test --coverage
```

### Writing Tests

**Example**: `tests/Feature/StudentTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_students()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->getJson('/api/students');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'admission_number', 'user']
                ]
            ]);
    }

    public function test_can_create_student()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $studentData = [
            'name' => 'John Doe',
            'email' => 'john@student.com',
            'password' => 'password123',
            'admission_number' => 'STU00001',
            'date_of_birth' => '2010-01-01',
            'gender' => 'male',
            'parent_name' => 'Parent Name',
            'parent_phone' => '1234567890'
        ];

        $response = $this->actingAs($admin)
            ->postJson('/api/students', $studentData);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('students', [
            'admission_number' => 'STU00001'
        ]);
    }
}
```

---

## Deployment

### Local Development

```bash
# 1. Clone repository
git clone <repo-url>
cd school-management-system

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_DATABASE=school_management
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations and seeders
php artisan migrate:fresh --seed

# 6. Start server
php artisan serve

# Visit http://localhost:8000
# Login: admin@school.com / password123
```

### cPanel Deployment

See `DEPLOYMENT_GUIDE.md` for detailed instructions.

**Quick summary**:
1. Upload files via File Manager or FTP
2. Create MySQL database and user
3. Configure `.env` with database credentials
4. Run migrations: `php artisan migrate --force`
5. Set permissions: `chmod -R 755 storage bootstrap/cache`
6. Clear caches: `php artisan config:cache && php artisan route:cache`

---

## Code Style & Best Practices

### PHP (Laravel)

- Follow **PSR-12** coding standard
- Use **Laravel Pint** for formatting: `./vendor/bin/pint`
- Type-hint parameters and return types
- Use dependency injection
- Wrap multi-step operations in database transactions
- Always validate input
- Use Eloquent relationships instead of manual joins

### JavaScript

- Use **ES6+** features (const/let, arrow functions, async/await)
- No semicolons (consistent with project style)
- Use template literals for HTML
- Avoid jQuery-style chaining
- Keep components self-contained
- Use async/await instead of promises
- Handle errors gracefully with try/catch

### Database

- Use migrations (never modify database manually)
- Add indexes on foreign keys
- Use soft deletes for important data
- Use `onDelete('cascade')` for strict relationships
- Name tables in plural (users, students, classes)
- Name foreign keys as `{model}_id` (user_id, class_id)

### Security

- **CSRF protection** enabled on all POST/PUT/DELETE requests
- **Password hashing** with bcrypt
- **SQL injection prevention** via Eloquent ORM
- **XSS protection** via Blade escaping
- **Authentication** required for all admin routes
- **Role-based access control** via middleware
- **Input validation** on all user input

---

## Common Tasks for AI Assistants

### Task: Add a new field to existing model

1. **Create migration**:
```bash
php artisan make:migration add_field_to_table
```

2. **Edit migration**:
```php
public function up(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->string('new_field')->nullable()->after('existing_field');
    });
}

public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn('new_field');
    });
}
```

3. **Update model's `$fillable` array**
4. **Update controller validation rules**
5. **Update frontend component template**

### Task: Fix a bug in component

1. **Locate component** in `public/js/app.js`
2. **Identify the method** with the issue
3. **Fix the logic** (check API calls, event listeners, DOM manipulation)
4. **Test in browser** (no compilation needed!)

### Task: Add API endpoint

1. **Add route** in `routes/web.php`
2. **Create controller method** or add to existing controller
3. **Add validation rules**
4. **Return JSON response**
5. **Update frontend component** to call new endpoint

### Task: Debug database issue

```bash
# Check migrations status
php artisan migrate:status

# Run pending migrations
php artisan migrate

# Fresh install (WARNING: deletes data)
php artisan migrate:fresh --seed

# Check database connection
php artisan tinker
> \DB::connection()->getPdo();
```

### Task: Clear caches

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

### Task: Add translation

1. Edit `lang/en/app.php`:
```php
return [
    'new_key' => 'English text',
];
```

2. Edit `lang/ar/app.php`:
```php
return [
    'new_key' => 'النص العربي',
];
```

3. Use in component:
```javascript
const text = await i18n.translate('new_key');
```

---

## Troubleshooting

### "Route not found"
- Check `routes/web.php` for route definition
- Run `php artisan route:list` to see all routes
- Clear route cache: `php artisan route:clear`

### "Class not found"
- Run `composer dump-autoload`
- Check namespace and use statements

### "CSRF token mismatch"
- Ensure `<meta name="csrf-token">` is in layout
- Check `csrfToken` variable is defined in JavaScript
- Verify `X-CSRF-TOKEN` header is sent with requests

### "Column not found"
- Run `php artisan migrate`
- Check migration file for column name
- Refresh database: `php artisan migrate:fresh`

### "Component not rendering"
- Check browser console for JavaScript errors
- Verify API endpoint returns valid JSON
- Check if route is registered in router
- Verify event listeners are attached after DOM load

### "Permission denied" (cPanel)
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R username:username storage
chown -R username:username bootstrap/cache
```

---

## Important Files Reference

| File | Purpose |
|------|---------|
| `routes/web.php` | All route definitions |
| `public/js/app.js` | Main frontend application (all components) |
| `public/js/charts.js` | Chart library |
| `public/js/i18n.js` | Internationalization |
| `app/Http/Controllers/` | Backend API controllers |
| `app/Models/` | Eloquent ORM models |
| `database/migrations/` | Database schema |
| `database/seeders/DatabaseSeeder.php` | Sample data generator |
| `resources/views/dashboard.blade.php` | Main app layout |
| `lang/en/app.php` | English translations |
| `lang/ar/app.php` | Arabic translations |
| `.env` | Environment configuration |
| `composer.json` | PHP dependencies |

---

## Default Credentials

After running seeders:

- **Admin**: admin@school.com / password123
- **Teachers**: {firstname.lastname}@school.com / password123
- **Students**: {firstname.lastname}{number}@student.school.com / password123

**⚠️ IMPORTANT**: Change default passwords in production!

---

## Quick Commands Cheat Sheet

```bash
# Development
php artisan serve                    # Start dev server
php artisan tinker                   # Interactive REPL
php artisan route:list               # List all routes
php artisan migrate:status           # Check migrations

# Database
php artisan migrate                  # Run migrations
php artisan migrate:fresh            # Drop all tables and re-run
php artisan migrate:fresh --seed     # Fresh install with sample data
php artisan db:seed                  # Run seeders only

# Cache
php artisan config:cache             # Cache configuration
php artisan route:cache              # Cache routes
php artisan view:cache               # Cache views
php artisan cache:clear              # Clear application cache

# Code Generation
php artisan make:model ModelName
php artisan make:controller ControllerName
php artisan make:migration create_table_name
php artisan make:seeder SeederName

# Testing
php artisan test                     # Run all tests
php artisan test --filter testName   # Run specific test

# Code Style
./vendor/bin/pint                    # Format PHP code
```

---

## Questions or Issues?

- **Documentation**: Check `README.md`, `DEPLOYMENT_GUIDE.md`, `FRONTEND_GUIDE.md`
- **Code Examples**: Look at existing controllers/components for patterns
- **Database Schema**: Check migration files in `database/migrations/`
- **API Testing**: Use browser DevTools Network tab or Postman

---

**Last Updated**: 2025-11-17
**Laravel Version**: 12.x
**PHP Version**: 8.2+
**Architecture**: Vanilla JavaScript + Laravel API
