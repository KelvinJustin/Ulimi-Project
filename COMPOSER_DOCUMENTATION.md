# Composer Integration Documentation

## Overview

The Ulimi Marketplace project now uses **Composer** as its dependency manager, replacing the custom PSR-4 autoloader. This aligns the project with industry standards for PHP development.

## What is Composer?

Composer is PHP's standard dependency manager, similar to:
- **npm** for Node.js
- **pip** for Python
- **gem** for Ruby

It provides:
- **Standardized autoloading** - PSR-4 compliant class loading
- **Dependency management** - Automatic download and version resolution
- **Environment consistency** - Same library versions across all environments
- **Security** - Automated vulnerability scanning via `composer audit`
- **Development tools** - Easy integration with testing and code quality tools

## Changes Made

### 1. Created `composer.json`
Defines project dependencies and autoloading configuration:
- PHP requirement: >= 8.0 (compatible with XAMPP PHP 8.0.30)
- 9 production dependencies
- 2 development dependencies
- PSR-4 autoloading for `App\` namespace

### 2. Updated `app/bootstrap.php`
**Before:**
```php
require_once __DIR__ . '/Core/Autoloader.php';
App\Core\Autoloader::register();
```

**After:**
```php
require_once __DIR__ . '/../vendor/autoload.php';
```

### 3. Backed Up Custom Autoloader
- `app/Core/Autoloader.php` → `app/Core/Autoloader.php.bak`
- Can be restored if rollback is needed

### 4. Updated `.gitignore`
Added:
```
/vendor/
composer.lock
```

### 5. Created Local Composer Wrapper
- `composer.bat` - Uses XAMPP PHP path (`C:\xampp\php\php.exe`)
- Allows running Composer without adding PHP to system PATH

## Dependencies Installed

### Production Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| vlucas/phpdotenv | ^5.5 | Environment variable management |
| monolog/monolog | ^2.9 | Structured logging |
| stripe/stripe-php | ^13.0 | Payment processing |
| firebase/php-jwt | ^6.10 | JWT token handling |
| phpmailer/phpmailer | ^6.8 | Email notifications |
| symfony/http-foundation | ^5.4 | HTTP request/response objects |
| ramsey/uuid | ^4.7 | UUID generation |
| guzzlehttp/guzzle | ^7.8 | HTTP client for external APIs |

### Development Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| phpunit/phpunit | ^9.6 | Unit testing framework |
| squizlabs/php_codesniffer | ^3.8 | Code quality and style checking |

## Usage

### Running Composer Commands

Since PHP is not in the system PATH, use the local wrapper:

```bash
# Install dependencies
.\composer.bat install

# Update dependencies
.\composer.bat update

# Regenerate autoloader
.\composer.bat dump-autoload

# Check for security vulnerabilities
.\composer.bat audit

# Show outdated packages
.\composer.bat outdated
```

### Adding New Dependencies

```bash
# Add a package
.\composer.bat require vendor/package-name

# Add a development package
.\composer.bat require --dev vendor/package-name

# Remove a package
.\composer.bat remove vendor/package-name
```

## Project Structure

```
ulimi3/
├── app/
│   ├── bootstrap.php          # Now uses vendor/autoload.php
│   ├── Core/
│   │   ├── Autoloader.php.bak # Backup of custom autoloader
│   │   └── ...
│   └── ...
├── vendor/                    # Composer dependencies (gitignored)
│   ├── autoload.php           # Main autoloader
│   ├── composer/              # Composer internals
│   ├── monolog/               # Logging library
│   ├── stripe/                # Stripe SDK
│   └── ...
├── composer.json              # Dependency configuration
├── composer.lock              # Exact version lock file (gitignored)
├── composer.bat               # Local Composer wrapper
└── .gitignore                 # Updated to ignore vendor/
```

## Backward Compatibility

The integration maintains full backward compatibility:

- **All existing class names unchanged**
- **All existing file structure unchanged**
- **Custom Router, Request, View, Auth classes remain**
- **FileUserStorage remains** (gradual migration path)

### Rollback Plan

If you need to rollback:

1. Restore custom autoloader:
   ```bash
   mv app/Core/Autoloader.php.bak app/Core/Autoloader.php
   ```

2. Revert `app/bootstrap.php`:
   ```php
   require_once __DIR__ . '/Core/Autoloader.php';
   App\Core\Autoloader::register();
   ```

3. Delete Composer files:
   ```bash
   rm composer.json composer.lock composer.bat
   rm -rf vendor/
   ```

## Next Steps (Optional Integration)

The following are optional enhancements using the newly installed dependencies:

### 1. Replace error_log() with Monolog

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('ulimi');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../storage/app.log', Logger::DEBUG));
$logger->info('User logged in', ['user_id' => $userId]);
```

### 2. Use phpdotenv for .env Parsing

```php
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dbHost = $_ENV['DB_HOST'];
```

### 3. Integrate Stripe SDK

```php
use Stripe\Stripe;
use Stripe\Charge;

Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
$charge = Charge::create([
    'amount' => 2000,
    'currency' => 'mwk',
    'source' => $token,
]);
```

### 4. Add PHPMailer for Email

```php
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = $_ENV['SMTP_HOST'];
$mail->addAddress($userEmail);
$mail->Subject = 'Welcome to Ulimi';
$mail->Body = 'Thank you for registering!';
$mail->send();
```

## Troubleshooting

### Composer Not Found

**Error:** `composer : The term 'composer' is not recognized`

**Solution:** Use the local wrapper:
```bash
.\composer.bat <command>
```

### PHP Not in PATH

**Error:** `'php' is not recognized as an internal or external command`

**Solution:** The `composer.bat` wrapper uses the full XAMPP PHP path. If you change XAMPP location, update the batch file:
```batch
@echo OFF
C:\xampp\php\php.exe "%~dp0composer.phar" %*
```

### Autoloader Issues

**Error:** Class not found

**Solution:** Regenerate autoloader:
```bash
.\composer.bat dump-autoload
```

### Dependency Conflicts

**Error:** Your requirements could not be resolved

**Solution:** Check PHP version compatibility:
```bash
C:\xampp\php\php.exe -v
```

Update `composer.json` PHP requirement if needed.

## Security Considerations

### composer.lock in Git

Currently `composer.lock` is gitignored. For production deployments, consider:

**Option 1:** Commit `composer.lock` (recommended for production)
- Ensures exact same versions across all environments
- Remove from `.gitignore`
- Run `composer install` on production

**Option 2:** Keep gitignored (development flexibility)
- Run `composer install` on production
- May get slightly different patch versions

### Security Audits

Run regularly:
```bash
.\composer.bat audit
```

This checks for known vulnerabilities in installed packages.

## Performance

Composer autoloader is optimized:
- **Class map generation** - Faster loading
- **APCu cache** - Caches autoloader in memory (if available)
- **No filesystem checks** - Pre-generated class map

## Testing

Run the autoloader test:
```bash
C:\xampp\php\php.exe -r "require 'vendor/autoload.php'; echo 'Autoloader OK';"
```

## Additional Resources

- [Composer Documentation](https://getcomposer.org/doc/)
- [PSR-4 Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [Packagist Package Repository](https://packagist.org/)

## Support

For issues specific to this project:
1. Check this documentation
2. Review `COMPOSER_SETUP.md` for installation details
3. Check the backup autoloader if rollback is needed

For general Composer issues:
- [Composer Troubleshooting](https://getcomposer.org/doc/articles/troubleshooting.md)
- [Composer GitHub Issues](https://github.com/composer/composer/issues)
