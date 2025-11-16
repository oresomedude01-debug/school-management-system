# School Management System

A complete Laravel-based School Management System with a modern React-like UI built using **vanilla JavaScript** and **Tailwind CSS** (via CDN). **No npm install required!** Perfect for cPanel deployment.

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.4-blue)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CDN-38B2AC)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-yellow)

## Features

### Core Modules
- **Dashboard** - Overview with statistics and charts
- **Student Management** - Complete CRUD operations
- **Teacher Management** - Employee records and assignments
- **Class Management** - Grade levels and sections
- **Subject Management** - Course catalog
- **Attendance Tracking** - Daily attendance records
- **Grades & Reports** - Exam results and report cards

### Authentication & Authorization
- Role-based access control (Admin, Teacher, Student)
- Secure login/logout
- Session management

### User Interface
- Modern, clean design with Tailwind CSS
- Fully responsive (mobile, tablet, desktop)
- React-like component system (vanilla JS)
- No build tools required
- Fast and lightweight

## Technology Stack

- **Backend**: Laravel 12
- **Frontend**: Vanilla JavaScript (No React, Vue, or build tools!)
- **Styling**: Tailwind CSS (loaded via CDN)
- **Database**: MySQL
- **Server**: Apache (cPanel compatible)
- **PHP**: 8.1+

## Quick Start

### Prerequisites
- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Apache with mod_rewrite

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd school-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and set your database credentials:
   ```env
   DB_DATABASE=school_management
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   - URL: http://localhost:8000
   - Email: admin@school.com
   - Password: password123

## cPanel Deployment

See the comprehensive [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for detailed cPanel deployment instructions.

### Quick cPanel Steps
1. Upload files to cPanel via File Manager
2. Create MySQL database and user
3. Configure `.env` with database credentials
4. Set permissions: `chmod -R 755 storage bootstrap/cache`
5. Run: `php artisan migrate:fresh --seed`
6. Clear caches: `php artisan config:cache`
7. Visit your domain and login!

## Default Credentials

After seeding the database:
- **Email**: admin@school.com
- **Password**: password123

**⚠️ IMPORTANT**: Change these credentials immediately after first login!

## Project Structure

```
school-management-system/
├── app/
│   ├── Http/Controllers/     # All API controllers
│   └── Models/                # Eloquent models
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Sample data
├── public/
│   └── js/
│       └── app.js            # Vanilla JS application (React-like)
├── resources/
│   └── views/                 # Blade templates
├── routes/
│   └── web.php                # Application routes
└── DEPLOYMENT_GUIDE.md        # Detailed deployment instructions
```

## API Endpoints

All endpoints are RESTful and return JSON:

```
GET    /api/students        - List all students
POST   /api/students        - Create student
GET    /api/students/{id}   - View student
PUT    /api/students/{id}   - Update student
DELETE /api/students/{id}   - Delete student

# Similar patterns for:
/api/teachers
/api/classes
/api/subjects
/api/attendance
/api/grades
```

## Vanilla JavaScript Architecture

The application uses a custom React-like component system:

```javascript
// Component Structure
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

### Key Features of the JS System
- **State Management**: Custom Store class
- **Routing**: Client-side router
- **API Helper**: Fetch wrapper with CSRF protection
- **Component System**: Modular, reusable components
- **No Build Process**: Plain ES6+ JavaScript

## Database Schema

### Main Tables
- `users` - All system users (polymorphic)
- `students` - Student-specific data
- `teachers` - Teacher employment records
- `classes` - Grade levels and sections
- `subjects` - Course catalog
- `enrollments` - Student-class relationships
- `attendance` - Daily attendance records
- `grades` - Exam results and scores

### Relationships
- User → Student (1:1)
- User → Teacher (1:1)
- Student → Classes (Many-to-Many via Enrollments)
- Class → Subjects (Many-to-Many)
- Student → Attendance (1:Many)
- Student → Grades (1:Many)

## Customization

### Adding New Features
1. Create a controller: `php artisan make:controller MyController`
2. Add routes in `routes/web.php`
3. Create a component in `public/js/app.js`
4. Register the route in the router
5. Add navigation link

### Styling
- All styles use Tailwind CSS utility classes
- Loaded via CDN (no compilation needed)
- Customize in component templates

## Security

### Built-in Security Features
- CSRF protection
- Password hashing (bcrypt)
- SQL injection protection (Eloquent ORM)
- XSS protection (Blade escaping)
- Session management
- Role-based access control

### Recommendations
- Use HTTPS in production
- Change default credentials
- Set `APP_DEBUG=false` in production
- Regular backups
- Keep Laravel updated

## Performance

### Optimization Tips
- Enable OPcache
- Cache configuration: `php artisan config:cache`
- Cache routes: `php artisan route:cache`
- Cache views: `php artisan view:cache`
- Use production .env settings

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Contributing
Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License
This project is open-source software licensed under the MIT license.

## Support
For deployment help, see [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

## Acknowledgments
- Built with Laravel 12
- Styled with Tailwind CSS
- No npm required!
- cPanel ready!

---

**Made with ❤️ for schools that need simple, powerful management systems without complex build tools!**
