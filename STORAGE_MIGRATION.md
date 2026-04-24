# Database-Only Storage Migration

## Overview

The Ulimi Marketplace project has been migrated from mixed file/database storage to database-only storage. This aligns the project with industry standards and provides better scalability, security, and maintainability.

## What Changed

### Before (Mixed Storage)
- User authentication: File-based (`storage/users.php`)
- Business data: Database (listings, profiles, carts, messages)
- Dual system requiring synchronization
- Custom `FileUserStorage` class

### After (Database-Only)
- All data: Database
- Single source of truth
- Standard PDO-based operations
- `User` model for database operations

## Migration Details

### Files Modified

1. **app/Models/User.php** - Replaced FileUserStorage with database operations
2. **app/Core/Auth.php** - Updated to use User model, added role_id to role string mapping
3. **app/Controllers/AuthController.php** - Replaced FileUserStorage with User model
4. **app/Controllers/DashboardController.php** - Replaced FileUserStorage with User model
5. **app/Models/Listing.php** - Updated cleanupOrphanedListings to use database
6. **api/seller-products.php** - Updated to use database for user authentication

### Files Deprecated

- **app/Core/FileUserStorage.php** → `app/Core/FileUserStorage.php.deprecated`
- **storage/users.php** → `storage/users.php.bak` (after migration)

### New Files Created

- **storage/sql/migrations/create_default_admin.php** - Creates default admin user
- **storage/sql/migrations/migrate_file_users.php** - Migrates file users to database

## Database Schema

### users Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- role_id (INT) - 1=seller, 2=buyer, 3=admin
- email (VARCHAR, UNIQUE)
- slug (VARCHAR, UNIQUE)
- password_hash (VARCHAR)
- status (VARCHAR) - 'active', 'inactive'
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### user_profiles Table
```sql
- user_id (INT, PRIMARY KEY, FOREIGN KEY)
- display_name (VARCHAR)
- rating_avg (DECIMAL)
- rating_count (INT)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## Role Mapping

| Role String | Role ID |
|-------------|---------|
| seller      | 1       |
| buyer       | 2       |
| admin       | 3       |

## Plug-and-Play Setup

### New Installation

```bash
# 1. Clone project
git clone <repo-url>
cd ulimi3

# 2. Install Composer dependencies
.\composer.bat install

# 3. Setup database (creates admin user automatically)
php setup_database.php

# 4. Configure .env
cp .env.example .env
# Edit .env with database credentials

# 5. Ready to use!
```

### Existing Installation (with file users)

```bash
# 1. Pull latest code
git pull

# 2. Install Composer dependencies
.\composer.bat install

# 3. Run setup (migrates file users automatically)
php setup_database.php

# 4. Ready to use!
```

## Default Admin Credentials

- **Email**: admin@example.com
- **Password**: admin123
- **Important**: Change the default admin password after first login!

## Migration Process

The setup script automatically:

1. Creates database and tables from schema.sql
2. Runs all pending migrations
3. Creates default admin user (if not exists)
4. Migrates file users from `storage/users.php` to database (if file exists)
5. Backs up `users.php` to `users.php.bak` after migration

## Backward Compatibility

The migration maintains backward compatibility:

- Role strings are still available via `Auth::role()` (mapped from role_id)
- All existing session management unchanged
- All existing views and routes unchanged
- Custom Router, Request, View classes remain

## Rollback Plan

If you need to rollback:

1. Restore file users:
   ```bash
   mv storage/users.php.bak storage/users.php
   ```

2. Restore FileUserStorage:
   ```bash
   mv app/Core/FileUserStorage.php.deprecated app/Core/FileUserStorage.php
   ```

3. Revert Auth class to use FileUserStorage

4. Revert controllers to use FileUserStorage

## Benefits

1. **Industry Standard**: Database-only auth is the norm
2. **Scalability**: Database scales, files don't
3. **Consistency**: Single source of truth
4. **Security**: Database has better access controls
5. **Performance**: Database queries are optimized
6. **Backup**: Database backups are standard
7. **Replication**: Database can be replicated across servers
8. **Transactions**: Database supports ACID operations

## Testing

After migration, test:

1. Admin login (admin@example.com / admin123)
2. Regular user registration
3. User profile update
4. User deletion
5. Role-based access control
6. Session management

## Legacy System

The `marketplace/` directory remains as a separate legacy system. It is marked as deprecated and should be migrated or removed in a future update.

## Support

For issues or questions:
- Check this documentation
- Review migration files in `storage/sql/migrations/`
- Check the deprecated `FileUserStorage.php.deprecated` for reference
