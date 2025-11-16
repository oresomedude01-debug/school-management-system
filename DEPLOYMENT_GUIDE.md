# School Management System - Deployment Guide

## Overview
This is a complete Laravel School Management System with a React-like UI built using vanilla JavaScript and Tailwind CSS (via CDN). **No npm install required!** Perfect for cPanel deployment.

## Features
- Student Management (CRUD operations)
- Teacher Management
- Class Management
- Subject Management
- Attendance Tracking
- Grades/Report Cards System
- Dashboard with Statistics
- Role-based Authentication (Admin, Teacher, Student)
- Modern UI with Tailwind CSS
- Vanilla JavaScript (React-like components)
- Mobile Responsive

## Technology Stack
- **Backend**: Laravel 12
- **Frontend**: Vanilla JavaScript (No build tools!)
- **Styling**: Tailwind CSS (via CDN)
- **Database**: MySQL
- **Server**: Apache (cPanel compatible)

## Prerequisites
- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Apache with mod_rewrite enabled

---

## cPanel Deployment Instructions

### Step 1: Upload Files
1. Compress your project folder into a ZIP file
2. Log in to your cPanel account
3. Go to **File Manager**
4. Navigate to your domain's root directory (usually `public_html`)
5. Upload the ZIP file
6. Extract the files

### Step 2: Configure Directory Structure
For cPanel deployment, you have two options:

**Option A: Public Directory Setup (Recommended)**
1. Move all contents of the `public` folder to your `public_html` directory
2. Move all other folders (app, database, vendor, etc.) OUTSIDE of `public_html` for security
3. Update the `public_html/index.php` file to point to the correct paths:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

**Option B: Root .htaccess Redirect**
1. Upload the entire project to `public_html`
2. The included `.htaccess` file in the root will redirect all requests to the `public` folder
3. This is simpler but slightly less secure

### Step 3: Create MySQL Database
1. In cPanel, go to **MySQL Databases**
2. Create a new database (e.g., `school_management`)
3. Create a database user
4. Add the user to the database with ALL PRIVILEGES
5. Note down:
   - Database name
   - Database username
   - Database password
   - Database host (usually `localhost`)

### Step 4: Configure Environment
1. In File Manager, find the `.env` file in your project root
2. If `.env` doesn't exist, copy `.env.example` to `.env`
3. Edit `.env` with your database credentials:
   ```env
   APP_NAME="School Management System"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

### Step 5: Set Proper Permissions
In cPanel Terminal or File Manager permissions, set:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Step 6: Generate Application Key
1. Open cPanel **Terminal**
2. Navigate to your project directory:
   ```bash
   cd public_html  # or wherever your project is
   ```
3. Run:
   ```bash
   php artisan key:generate
   ```

### Step 7: Run Migrations and Seeders
In cPanel Terminal:
```bash
php artisan migrate:fresh --seed
```

This will:
- Create all database tables
- Create a default admin user
- Add sample subjects

### Step 8: Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 9: Test the Application
1. Visit your domain in a browser
2. You should see the login page
3. Login with default credentials:
   - **Email**: admin@school.com
   - **Password**: password123

---

## Default Login Credentials

After running the seeders, you can login with:
- **Email**: admin@school.com
- **Password**: password123

**IMPORTANT**: Change this password immediately after first login!

---

## Application Structure

```
school-management-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── StudentController.php
│   │   ├── TeacherController.php
│   │   ├── ClassController.php
│   │   ├── SubjectController.php
│   │   ├── AttendanceController.php
│   │   └── GradeController.php
│   └── Models/
│       ├── User.php
│       ├── Student.php
│       ├── Teacher.php
│       ├── SchoolClass.php
│       ├── Subject.php
│       ├── Enrollment.php
│       ├── Attendance.php
│       └── Grade.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── js/
│   │   └── app.js          # Vanilla JS React-like components
│   └── index.php
├── resources/
│   └── views/
│       ├── layout.blade.php
│       ├── login.blade.php
│       └── dashboard.blade.php
└── routes/
    └── web.php
```

---

## Features Guide

### Dashboard
- View total students, teachers, classes, and subjects
- Today's attendance statistics
- Recently added students
- Attendance rate percentage

### Student Management
- Add new students with complete information
- Search and filter students
- View student details
- Update student information
- Delete students
- Pagination support

### Other Modules
The system includes placeholder components for:
- Teachers Management
- Classes Management
- Subjects Management
- Attendance Tracking
- Grades/Report Cards

These follow the same pattern as the Student Management module.

---

## Customization Guide

### Adding New Features
The vanilla JavaScript component system makes it easy to add new features:

1. **Create a Component** in `public/js/app.js`:
   ```javascript
   const MyComponent = {
       async render() {
           // Your component logic
       }
   };
   ```

2. **Register the Route**:
   ```javascript
   SchoolApp.router.register('myroute', () => MyComponent.render());
   ```

3. **Add Navigation Link** in `renderNavigation()` method

### Styling
- All styles use Tailwind CSS classes (loaded via CDN)
- No build process required
- Fully responsive out of the box

### API Endpoints
All API endpoints are in `routes/web.php` and follow RESTful conventions:
- GET `/api/students` - List students
- POST `/api/students` - Create student
- GET `/api/students/{id}` - View student
- PUT `/api/students/{id}` - Update student
- DELETE `/api/students/{id}` - Delete student

---

## Troubleshooting

### 500 Internal Server Error
- Check file permissions (755 for directories, 644 for files)
- Ensure storage and bootstrap/cache are writable
- Check .env configuration
- Enable error display temporarily: `APP_DEBUG=true`

### Database Connection Error
- Verify database credentials in `.env`
- Ensure database exists
- Check if user has proper privileges

### Blank Page After Login
- Clear all caches: `php artisan config:clear`
- Check JavaScript console for errors
- Ensure `public/js/app.js` is accessible

### Routes Not Working
- Check if mod_rewrite is enabled
- Verify .htaccess files are in place
- Clear route cache: `php artisan route:clear`

---

## Security Recommendations

1. **Change Default Credentials** immediately
2. **Set APP_DEBUG=false** in production
3. **Use HTTPS** (enable SSL in cPanel)
4. **Keep Laravel Updated**
5. **Regular Backups** of database and files
6. **Restrict storage folder** access via .htaccess
7. **Use strong passwords** for all users

---

## Performance Optimization

1. **Enable OPcache** in cPanel PHP settings
2. **Use production .env settings**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```
3. **Cache configuration**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## Database Schema

### Users Table
- Stores all users (admin, teachers, students)
- Role-based system

### Students Table
- Linked to users table
- Contains student-specific information
- Parent/guardian details

### Teachers Table
- Linked to users table
- Employment information

### Classes Table
- Grade levels and sections
- Assigned class teachers

### Subjects Table
- Subject catalog
- Credits and descriptions

### Enrollments Table
- Student-Class relationships
- Academic year tracking

### Attendance Table
- Daily attendance records
- Multiple status types (present, absent, late, excused)

### Grades Table
- Student exam results
- Multiple exam types
- Automatic percentage calculation

---

## Support and Maintenance

### Updating the Application
1. Backup your database and files
2. Pull latest changes
3. Run migrations: `php artisan migrate`
4. Clear caches

### Adding Admin Users
Use Tinker in cPanel Terminal:
```bash
php artisan tinker
```

Then:
```php
$user = new App\Models\User;
$user->name = 'New Admin';
$user->email = 'admin@example.com';
$user->password = Hash::make('password');
$user->role = 'admin';
$user->save();
```

---

## License
This School Management System is open-source software.

## Credits
- Built with Laravel 12
- Styled with Tailwind CSS
- No third-party JavaScript frameworks required!

---

**For any issues or questions, please refer to the troubleshooting section or check Laravel documentation.**
