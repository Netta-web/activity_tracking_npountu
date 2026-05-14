# SupportOps Tracker

A real-time activity tracking and shift handover platform built for IT support operations teams. SupportOps Tracker gives administrators full visibility across all personnel and tasks, while support staff see only their assigned work — ensuring clean, accountable handovers every shift.

---

## Features

### For Administrators
- Full dashboard overview — total, pending, in-progress, done, critical, and overdue counts
- Manage all activities across every department and assignee
- Personnel performance breakdown (tasks assigned, pending, completed per staff member)
- Category distribution chart across all service areas
- Create, edit, reassign, and delete any activity
- Manage user accounts

### For Support Personnel
- Personal dashboard scoped to assigned activities only
- Log updates and remarks against activities
- Track activity status through the full lifecycle: Pending → In Progress → Done
- View shift handover board — pending items sorted by priority and due date

### Activity Management
- 10 built-in service categories: SMS Monitoring, Server Monitoring, Database Validation, API Health Check, Incident Response, Network Monitoring, Application Support, Security Audit, Backup Verification, Performance Review
- Priority levels: Low, Medium, High, Critical (colour-coded)
- Status tracking: Pending, In Progress, Done
- Due date assignment with automatic overdue detection
- Full update history per activity

### Reporting
- Filterable activity update log (by date range, staff member, category, status, priority)
- CSV export of filtered reports
- Audit log of all system actions (create, update, delete)

### Handover Board
- Pending activities sorted by priority then due date
- Recently completed items
- Today's update feed — everything logged in the current shift

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 (PHP 8.4) |
| Frontend | Blade + Alpine.js + Tailwind CSS 3 |
| Build tool | Vite 8 |
| Auth | Laravel Breeze |
| Database | MySQL 8 |
| Session/Cache/Queue | Database driver |
| Deployment | Docker on Render |
| Node.js | 22 LTS |

---

## Local Development

### Prerequisites
- PHP 8.4+
- Composer 2
- Node.js 22+
- MySQL 8

### Setup

```bash
# Clone the repository
git clone https://github.com/Netta-web/activity_tracking_npountu.git
cd activity_tracking_npountu/supportops-tracker

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then run migrations and seed
php artisan migrate --seed

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

The application will be available at `http://localhost:8000`.

For active development with hot module replacement:

```bash
composer run dev
```

---

## Environment Variables

Copy `.env.example` to `.env` and configure the following:

```env
APP_KEY=          # Generated via: php artisan key:generate
APP_URL=          # e.g. https://your-domain.com

DB_HOST=          # MySQL host
DB_DATABASE=      # Database name
DB_USERNAME=      # Database user
DB_PASSWORD=      # Database password
```

All other values in `.env.example` are pre-configured with sensible production defaults.

---

## Database Seeding

Running `php artisan migrate --seed` creates:

- 1 administrator account
- 6 support personnel accounts across different departments
- Sample activities and update history

See `database/seeders/UserSeeder.php` for seeded accounts.

---

## Deployment (Render)

The project ships with a production-ready `Dockerfile` and `render.yaml`.

### Steps

1. Push the repository to GitHub
2. In the [Render dashboard](https://render.com), create a **New Blueprint Instance** and connect the repository — Render reads `render.yaml` automatically
3. Add the following secret environment variables in the Render dashboard:

| Variable | Description |
|----------|-------------|
| `APP_KEY` | Generate with `php artisan key:generate --show` |
| `APP_URL` | Your Render service URL |
| `DB_HOST` | MySQL host from your database provider |
| `DB_DATABASE` | Database name |
| `DB_USERNAME` | Database username |
| `DB_PASSWORD` | Database password |

4. Deploy — migrations run automatically on every deploy

### What the container does on start
1. Discovers Laravel packages
2. Links storage
3. Runs `php artisan migrate --force`
4. Caches config, routes, and views
5. Starts the PHP built-in server on `$PORT`

---

## Role Reference

| Role | Access |
|------|--------|
| `admin` | Full access to all activities, users, reports, and settings |
| `support` | Access limited to personally assigned activities and handover board |

---

## Project Structure

```
supportops-tracker/
├── app/
│   ├── Http/Controllers/     # ActivityController, DashboardController, HandoverController, ReportController, UserController
│   ├── Models/               # Activity, ActivityUpdate, AuditLog, User
│   └── Policies/             # Authorization policies
├── database/
│   ├── migrations/           # Schema definitions
│   └── seeders/              # Default users and sample data
├── resources/
│   ├── js/                   # Alpine.js + Axios bootstrap
│   └── views/                # Blade templates
├── Dockerfile                # Production Docker image (PHP 8.4 + Node 22)
└── render.yaml               # Render deployment configuration (repo root)
```

---

## License

MIT
