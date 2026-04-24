# Ulimi - Agricultural Marketplace Platform

A PHP-based agricultural marketplace connecting farmers and buyers.

## Recent Updates (April 24, 2026)

### Major Architectural Improvements

**Issues Resolved:**
- **No dependency manager**: Added Composer support for dependency management
- **Mixed storage strategies**: Migrated from file-based user storage to database-backed User model
- **No service container**: Implemented PSR-11 compliant Dependency Injection Container
- **Hardcoded paths**: Replaced hardcoded paths with STORAGE_PATH constant throughout codebase
- **No middleware system**: Implemented complete middleware pipeline with 8 middleware classes
- **No rate limiting**: Implemented RateLimitMiddleware with configurable groups
- **No HTTPS enforcement**: Implemented HttpsMiddleware to enforce HTTPS on all routes

**Statistics:**
- 49 files modified
- 870 lines added, 6,654 lines deleted (net reduction: 5,784 lines)
- Code cleanup and modernization across controllers, views, and core components

### New Features

**Middleware System:**
- AuthenticationMiddleware - User authentication checking
- CsrfMiddleware - CSRF token validation
- HttpsMiddleware - HTTPS enforcement
- RateLimitMiddleware - Rate limiting with configurable groups (auth, api, api-guest, upload, general)
- RoleMiddleware - Role-based access control (admin, seller, buyer)
- Pipeline - Middleware pipeline execution
- AbstractMiddleware & MiddlewareInterface - Base classes and interfaces

**Dependency Injection Container:**
- PSR-11 compliant container with automatic dependency resolution
- Singleton and binding support
- Automatic constructor dependency injection

**Router Enhancements:**
- Fluent middleware registration via RouteBuilder
- Middleware groups for common patterns (auth, admin, seller, buyer)
- Pipeline-based middleware execution
- Container integration for dependency injection

**Database Migration:**
- User model migrated from file-based storage to database
- Automatic slug generation for users
- Role ID to role string mapping
- Full CRUD operations for user management

### Bug Fixes

**Favorites Page:**
- Fixed undefined "unit" key warning by adding `cl.currency as unit` to SQL query
- Fixed image loading by implementing fallback to `listing_image_path` when gallery images are missing
- Improved image path construction to ensure proper URL formatting
- Added onerror handler for failed image loads

**Code Quality:**
- Removed 6,654 lines of legacy/deprecated code
- Deleted obsolete views (add-listing, create-listing, seller-listings, etc.)
- Removed file-based user storage system
- Consolidated access-denied view location

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
│   ├── Core/           # Core framework
│   │   ├── Middleware/  # Middleware classes (auth, CSRF, rate limiting, etc.)
│   │   ├── Container.php # Dependency injection container
│   │   ├── Database.php # Database connection
│   │   ├── Auth.php     # Authentication system
│   │   └── Router.php   # Routing with middleware support
│   ├── Models/         # Data models (User, Listing, Order, etc.)
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
├── api/                # API endpoints
├── tests/              # PHPUnit tests
├── database/           # Database utilities
└── composer.json       # Composer dependencies
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

### Middleware System

The application now uses a middleware pipeline for request processing. Middleware can be applied to routes using the fluent API:

```php
// Apply single middleware
$router->get('/dashboard', fn() => $controller->dashboard())
    ->middleware('auth');

// Apply multiple middleware
$router->post('/api/products', fn() => $controller->create())
    ->middleware('auth', 'throttle-api');

// Predefined middleware groups:
// - auth: Authentication + CSRF
// - admin: Admin role check
// - seller: Seller role check
// - buyer: Buyer role check
// - throttle-auth: Rate limit auth endpoints
// - throttle-api: Rate limit API endpoints
// - throttle-api-guest: Rate limit guest API endpoints
// - throttle-upload: Rate limit file uploads
// - throttle-general: General rate limiting
```

### Dependency Injection

The application uses a PSR-11 compliant container for dependency injection. Controllers can type-hint dependencies:

```php
final class BrowseController
{
    private Listing $listingModel;

    public function __construct(Listing $listingModel = null)
    {
        $this->listingModel = $listingModel ?? new Listing();
    }
}
```

The container automatically resolves dependencies from the constructor.

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
- Enable HTTPS in production (now enforced by HttpsMiddleware)
- Regularly update dependencies via Composer
- Rate limiting is now enabled on all API and auth endpoints
- CSRF protection is enforced on authenticated routes
- Role-based access control prevents unauthorized access

### Breaking Changes

**User Storage Migration:**
- User data is now stored in the database instead of `storage/users.php`
- Old file-based user storage has been removed
- Run the migration script if upgrading from an older version:
  ```bash
  php storage/sql/migrations/migrate_file_users.php
  ```

**Middleware Requirements:**
- All routes now use middleware for authentication and authorization
- Manual auth checks in controllers have been removed
- Routes must use the fluent middleware API to apply protection

**Path Constants:**
- Hardcoded paths have been replaced with `STORAGE_PATH` constant
- Ensure the constant is defined in your bootstrap file

## License

[Your License Here]
