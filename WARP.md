# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a Laravel 12-based logbook management system for students and supervisors at UKM. Students can create daily log entries and project entries, supervisors can approve them, and the system supports PDF export of logbooks. The application uses Vue.js for frontend components, Tailwind CSS and Bootstrap for styling, and barryvdh/laravel-dompdf for PDF generation.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vue.js 3.2 + Vite, Bootstrap 5.2, Tailwind CSS 4.0
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Testing**: Pest PHP 4.x
- **PDF Export**: barryvdh/laravel-dompdf
- **Dev Tools**: Laravel Pint (linting), Laravel Sail (Docker)

## Common Commands

### Setup & Installation
```powershell
# Full setup (install dependencies, generate key, migrate, build assets)
composer setup

# Manual setup steps
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

### Development
```powershell
# Start all dev services (server, queue worker, Vite)
composer dev

# Individual services
php artisan serve                    # Start Laravel server (port 8000)
php artisan queue:listen --tries=1   # Start queue worker
npm run dev                          # Start Vite dev server

# Database operations
php artisan migrate                  # Run migrations
php artisan migrate:fresh --seed     # Fresh DB with seeders
php artisan db:seed                  # Run seeders only

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Testing
```powershell
# Run all tests
composer test
# Or directly:
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run tests with coverage
php artisan test --coverage
```

### Code Quality
```powershell
# Format code with Laravel Pint
vendor/bin/pint

# Fix specific files
vendor/bin/pint app/Models/User.php
```

### Asset Building
```powershell
npm run build    # Production build
npm run dev      # Development with hot reload
```

## Architecture & Key Patterns

### Role-Based Access Control (RBAC)

The application implements a simple role-based system with three roles: `admin`, `supervisor`, and `student`.

**User Roles** (stored in `users.role` column):
- `admin`: Full system access, user management
- `supervisor`: Can view and approve student entries
- `student`: Can create and manage own log/project entries

**Authorization Layers**:
1. **RoleMiddleware** (`app/Http/Middleware/RoleMiddleware.php`): Route-level protection using `middleware('role:supervisor')` syntax
2. **Policies** (`app/Policies/`): Model-level authorization for LogEntry, ProjectEntry, and User operations
3. **Helper Methods**: User model has `isAdmin()`, `isSupervisor()`, `isStudent()` methods

### Domain Models & Relationships

**User Model** (`app/Models/User.php`):
- Has many `LogEntry` and `ProjectEntry`
- Uses soft deletes
- Includes profile picture handling with default avatar generation

**LogEntry & ProjectEntry Models** (`app/Models/`):
- Near-identical structure (dual-purpose system for different entry types)
- Both support:
  - Daily activity tracking
  - Supervisor approval workflow (fields: `supervisor_approved`, `approved_by`, `approved_at`)
  - Weekly reflection feature (fields: `weekly_reflection_content`, `reflection_week_start`, `reflection_supervisor_signed`, etc.)
- Relationships: `student()`, `approver()`, `reflectionSigner()` all pointing to User model
- Use soft deletes

### Controller Patterns

**LogEntryController & ProjectEntryController**:
- Authorization via policies (`$this->authorize()` calls)
- Students limited to one entry per day (validated in `store()` and `update()`)
- Standard CRUD with separate confirmation view for deletes

**SupervisorController**:
- Dashboard with pending entries and stats
- Approval actions that set `supervisor_approved`, `approved_by`, `approved_at`
- Role checks in each method (belt-and-suspenders with middleware)

**UserController**:
- Profile management for all users
- PDF export routes for students (`showExportForm()`, `exportLogbook()`)
- Admin-only user CRUD with soft delete/restore

### Route Organization

Routes in `routes/web.php` follow this pattern:
- Public routes (welcome, auth)
- Resource routes for log-entries and project-entries (standard CRUD)
- Authenticated user routes (profile, export) with role-based middleware groups
- Admin routes (user management) - specific routes listed before parameterized routes to avoid conflicts
- Supervisor routes (approval dashboard) - grouped under `role:supervisor` middleware

**Note**: Admin routes place specific paths like `/users/create` and `/users/trashed` before the parameterized `/users/{user}` route to prevent routing conflicts.

### Database & Migrations

- Uses SQLite by default (see `.env.example`)
- Key migrations include user profile fields, log/project entry tables, soft deletes, weekly reflection fields
- Migration `2025_10_13_060530_rename_log_entries_table.php` indicates table rename happened during development

### Seeders

Available seeders (`database/seeders/`):
- `RoleBasedUserSeeder`: Creates users with different roles
- `TestDataSeeder`: General test data
- `TestLogEntriesSeeder`: Sample log entries
- `PendingProjectEntriesSeeder`: Sample pending project entries

## Development Workflow

### Creating New Features

1. **Models**: Add to `app/Models/`, use soft deletes where appropriate
2. **Migrations**: `php artisan make:migration create_table_name`
3. **Policies**: Add to `app/Policies/`, register in `AuthServiceProvider` if needed
4. **Controllers**: Add to `app/Http/Controllers/`, use `$this->authorize()` for policy checks
5. **Routes**: Add to `routes/web.php` with appropriate middleware
6. **Views**: Add to `resources/views/` (Blade templates)
7. **Tests**: Add to `tests/Feature/` or `tests/Unit/`

### Working with Policies

All authorization goes through Laravel policies. When modifying access control:
- Check existing policies in `app/Policies/`
- Use `$this->authorize('action', $model)` in controllers
- For role checks, also use `RoleMiddleware` on routes

### Frontend Development

- Vue components: `resources/js/components/`
- Main entry: `resources/js/app.js`
- Styles: `resources/sass/app.scss` (Bootstrap/Tailwind)
- Vite handles bundling; config in `vite.config.js`
- Uses both Bootstrap and Tailwind (ensure no class conflicts)

### PDF Export

- Uses `barryvdh/laravel-dompdf` package
- Export templates: `resources/views/exports/logbook.blade.php`
- Student-only feature (protected by middleware)

## Important Notes

- **One entry per day rule**: Students can only create one LogEntry per day (enforced in controller)
- **Soft deletes**: User, LogEntry, and ProjectEntry models use soft deletes; admins can restore
- **Queue**: Configured to use database queue (`QUEUE_CONNECTION=database`)
- **Testing environment**: Uses in-memory SQLite (see `phpunit.xml`)
- **Composer scripts**: Use `composer dev` for concurrent services, `composer test` for running tests
