# Logbook UKM

A comprehensive logbook management system built with Laravel for Universiti Kebangsaan Malaysia (UKM) students to track their activities, projects, and weekly reflections with supervisor approval workflow.

## Features

### 🔐 User Management
- **Role-based Access Control**: Student, Supervisor, and Admin roles
- **Profile Management**: User profiles with matric number, workplace, and profile pictures
- **Soft Delete**: Safe user deletion with recovery options

### 📝 Activity Tracking
- **Log Entries**: Daily activity logging with detailed descriptions
- **Project Entries**: Project-specific activity tracking
- **Weekly Reflections**: Structured weekly reflection submissions
- **Supervisor Approval**: Workflow for activity approval and signing

### 📊 Dashboard & Analytics
- **Personal Dashboard**: Individual activity overview
- **Supervisor Dashboard**: Student progress monitoring
- **Activity Statistics**: Entry counts and approval status

### 📄 Export & Reporting
- **PDF Export**: Generate comprehensive logbook reports
- **Structured Reports**: Professional formatting for academic submissions

## Technology Stack

- **Framework**: Laravel 12.x
- **Database**: MySQL/PostgreSQL
- **Frontend**: Bootstrap (via Laravel UI)
- **PDF Generation**: DomPDF
- **Testing**: Pest PHP
- **Authentication**: Laravel Sanctum

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL database

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd logbook-ukm
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # Configure your database in .env file
   php artisan migrate --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the application**
   ```bash
   php artisan serve
   ```

### Quick Setup (Automated)
```bash
composer run setup
```

## Usage

### For Students
1. **Register/Login** with your matric number and email
2. **Create Log Entries** for daily activities
3. **Submit Weekly Reflections** with detailed insights
4. **Track Approvals** from your supervisor
5. **Export PDF** reports when needed

### For Supervisors
1. **Review Student Entries** and provide feedback
2. **Approve Activities** with digital signatures
3. **Monitor Progress** through dashboard analytics
4. **Sign Weekly Reflections** for completion

### For Admins
1. **Manage Users** (create, edit, delete, restore)
2. **Role Assignment** and permissions
3. **System Monitoring** and maintenance

## Project Structure

```
app/
├── Http/Controllers/
│   ├── LogEntryController.php
│   ├── UserController.php
│   └── SupervisorController.php
├── Models/
│   ├── User.php
│   ├── LogEntry.php
│   └── ProjectEntry.php
├── Policies/
│   ├── LogEntryPolicy.php
│   ├── ProjectEntryPolicy.php
│   └── UserPolicy.php
database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_log_entries_table.php
│   └── create_project_entries_table.php
resources/
├── views/
│   ├── log-entries/
│   ├── users/
│   └── layouts/
```

## API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

### Log Entries
- `GET /log-entries` - List user's log entries
- `POST /log-entries` - Create new log entry
- `GET /log-entries/{id}` - View specific entry
- `PUT /log-entries/{id}` - Update entry
- `DELETE /log-entries/{id}` - Delete entry

### User Management
- `GET /users` - List users (role-based)
- `GET /users/{id}` - View user profile
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user

## Testing

Run the test suite using Pest PHP:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/LogEntryTest.php

# Run with coverage
php artisan test --coverage
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

---

**Built with ❤️ for UKM students and supervisors**
