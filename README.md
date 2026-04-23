# Ulimi - Agricultural Marketplace Platform

A PHP-based agricultural marketplace connecting farmers and buyers.

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP (or equivalent PHP/MySQL environment)
- Composer (for dependency management)

**Note for Windows users:** PHP must be in your system PATH to run `php` commands. If you get "php is not recognized", either:
- Add `C:\xampp\php` to your PATH, or
- Use the full path: `C:\xampp\php\php.exe storage/sql/setup.php`

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd ulimi3
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Apache (Optional)

If using XAMPP, run the PowerShell setup script to configure virtual host:

```powershell
.\setup_xampp.ps1
```

This will:
- Enable mod_rewrite
- Configure virtual host for ulimi3.local
- Add entry to hosts file (requires Administrator)

### 4. Set Up Database

The project now uses a unified database setup system. Run the setup script:

**Windows (PowerShell or Command Prompt):**
```powershell
php storage/sql/setup.php
```

**Linux/Mac:**
```bash
php storage/sql/setup.php
```

This will automatically:
- Create the `ulimi` database if it doesn't exist
- Execute the complete schema from `storage/sql/schema.sql`
- Create all required tables (roles, users, commodities, listings, orders, etc.)
- Set up the migrations tracking table
- Run any pending migrations

**Alternative:** You can also use the legacy wrapper:
```powershell
php setup_database.php
```

### 5. Configure Environment Variables

Copy the example environment file and configure it:

```powershell
copy .env.example .env
```

Edit `.env` and set the following required values:

```env
# Database Configuration
DB_HOST=127.0.0.1
DB_NAME=ulimi
DB_USER=root
DB_PASS=your_mysql_password

# Security Keys (Required for production)
# For local development, default values are used
CSRF_KEY=your-random-csrf-key
JWT_SECRET=your-random-jwt-secret
```

**Generate random secrets for production:**
```powershell
openssl rand -base64 32
```

**For production deployment:**
- Set `APP_ENV=production`
- Set `COOKIE_SECURE=1`
- Use strong MySQL password
- Generate unique CSRF_KEY and JWT_SECRET

**Note:** For local development, the application will use default values if these are not set. The security keys are only required in production mode.

### 6. Marketplace Database (Optional)

The marketplace module uses a separate database. Set it up with:

```bash
php marketplace/setup_database.php
```

## Verifying Installation

Run the debug script to verify the database is set up correctly:

```bash
php storage/sql/debug_database.php
```

This will check:
- All expected tables exist
- Tables have the correct column structure
- No extra or missing tables
- Record counts for each table

## Project Structure

```
ulimi3/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Core/           # Core framework (Database, Auth, etc.)
│   ├── Models/         # Data models
│   ├── Routes/         # Route definitions
│   └── Views/          # View templates
├── public/             # Public web root
│   ├── assets/         # CSS, JS, images
│   └── uploads/        # User uploads
├── storage/
│   └── sql/
│       ├── schema.sql          # Complete database schema
│       ├── setup.php          # Unified setup script
│       ├── debug_database.php # Debug verification script
│       └── migrations/        # Migration scripts
├── marketplace/         # Marketplace module
└── api/                # API endpoints
```

## Database Tables

The main database includes:

**User Management:**
- roles, users, user_profiles

**Commodities & Listings:**
- commodities, commodity_listings, listing_images

**Orders & Payments:**
- carts, cart_items, orders, order_items, payments, shipments

**Communication:**
- conversations, messages, notifications

**Other:**
- price_ticks, favorites, migrations

## Development

### Running the Application

1. Start Apache and MySQL from XAMPP Control Panel
2. Access the application at `http://ulimi3.local` (if configured) or `http://localhost/ulimi3/public`

### Adding New Migrations

1. Create a new PHP file in `storage/sql/migrations/`
2. Name it descriptively (e.g., `add_new_feature.php`)
3. The migration will be automatically picked up by `setup.php`

### Debugging Database Issues

Use the debug script to diagnose database problems:

```powershell
php storage/sql/debug_database.php
```

For table-specific checks, use the utility scripts in `storage/sql/migrations/`:
- `check_tables.php` - Check conversations/messages tables
- `check_is_read.php` - Check messages table structure

## Troubleshooting

**Database connection fails:**
- Verify MySQL is running
- Check credentials in `storage/sql/setup.php` and `app/Core/Database.php`
- Ensure the database exists

**Tables missing after setup:**
- Run `php storage/sql/debug_database.php` to diagnose
- Re-run setup: `php storage/sql/setup.php`

**Permission errors:**
- Ensure `public/uploads/` is writable
- Check file permissions on `storage/` directory

## Security Notes

- Update default database credentials in production
- Use environment variables for sensitive configuration
- Enable HTTPS in production
- Regularly update dependencies

## License

[Your License Here]
