<p align="center"><a href="#"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Project Logo"></a></p>

# Logbook UKM

This repository contains the Logbook UKM application — a Laravel-based student logbook system with a dashboard that visualizes student activity.

## Key Features
- Student dashboard with an activity distribution doughnut chart (log vs project entries).
- Daily routine line chart (last 30 days) with automatic polling updates when new entries are added.
- Contribution-style overview (server-side 52-week aggregation available) and recent activity tables.
- Role-aware pages for students, supervisors, and admins.

## Quick Setup
Prerequisites: PHP 8+, Composer, Node.js (LTS), npm/Yarn.

1. Install PHP dependencies:

```bash
composer install
```

2. Install JS dependencies and build assets (Vite):

```bash
npm install
npm run build   # or `npm run dev` for local development
```

3. Copy environment and generate app key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations and seed (if needed):

```bash
php artisan migrate
php artisan db:seed
```

5. Serve locally:

```bash
php artisan serve
```

Open `http://localhost:8000/home` and log in as a student to see the dashboard.

## Dashboard Notes
- The dashboard uses Chart.js for visualizations. Assets are bundled with Vite.
- Daily line chart (last 30 days) pulls data from `GET /home/daily-counts` (authenticated) and polls every 20s by default.
- The doughnut chart displays total `log` vs `project` entries; center text shows the total count.
- Recent activity panels show the 5 most recent log and project entries.

## Routes of interest
- `GET /home` — dashboard view
- `GET /home/daily-counts` — JSON endpoint returning last 30 days of counts (used by the line chart)

## Development tips
- To test chart updates quickly, create a new log or project entry and refresh the dashboard (or wait for the poll interval).
- To change the polling interval, edit the inline script in `resources/views/home.blade.php` where `setInterval` is called.

## Contributing
Contributions are welcome. Please open issues or pull requests for enhancements or bug fixes.

## License
This project follows the MIT license.
