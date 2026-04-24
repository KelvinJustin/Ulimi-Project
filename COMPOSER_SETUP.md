# Composer Setup Instructions

Composer is not currently installed on your system. Follow these steps to install it and complete the integration.

## Step 1: Install Composer (Windows)

### Option A: Using the Installer (Recommended)
1. Download the Windows installer from: https://getcomposer.org/Composer-Setup.exe
2. Run the installer
3. It will automatically add Composer to your PATH
4. Verify installation by opening a new terminal and running:
   ```
   composer --version
   ```

### Option B: Manual Installation
1. Download the latest Composer.phar from: https://getcomposer.org/composer-stable.phar
2. Create a new folder: `C:\ProgramData\ComposerSetup\bin`
3. Move `composer.phar` to that folder
4. Add `C:\ProgramData\ComposerSetup\bin` to your system PATH
5. Create a batch file `composer.bat` in that folder with:
   ```
   @php "%~dp0composer.phar" %*
   ```
6. Restart your terminal and verify with `composer --version`

## Step 2: Install Project Dependencies

Once Composer is installed, navigate to the project directory and run:

```bash
cd c:\xampp\htdocs\ulimi3
composer install
```

This will:
- Download all dependencies to the `vendor/` directory
- Generate `composer.lock` file
- Create the optimized autoloader

## Step 3: Verify Installation

After installation, verify the autoloader works:

```bash
composer dump-autoload
```

Then test the application by accessing it in your browser.

## What Has Been Done

The following changes have already been completed:

1. ✅ Created `composer.json` with marketplace-appropriate dependencies
2. ✅ Updated `app/bootstrap.php` to use Composer autoloader
3. ✅ Backed up `app/Core/Autoloader.php` to `Autoloader.php.bak`
4. ✅ Updated `.gitignore` to exclude `/vendor/` and `composer.lock`

## Dependencies Included

- **vlucas/phpdotenv** - Environment variable management
- **monolog/monolog** - Structured logging
- **stripe/stripe-php** - Payment processing
- **firebase/php-jwt** - JWT token handling
- **phpmailer/phpmailer** - Email notifications
- **symfony/http-foundation** - HTTP request/response objects
- **ramsey/uuid** - UUID generation
- **guzzlehttp/guzzle** - HTTP client

## Development Dependencies

- **phpunit/phpunit** - Testing framework
- **squizlabs/php_codesniffer** - Code quality tool

## Next Steps After Installation

1. Gradually integrate new dependencies (optional):
   - Replace `error_log()` with Monolog
   - Use phpdotenv for .env parsing
   - Integrate Stripe SDK for payments
   - Add PHPMailer for email notifications

2. For legacy files (optional):
   - Add `require_once __DIR__ . '/../vendor/autoload.php';` to `api/seller-products.php`
   - Add to `public/add-to-cart.php` if needed

## Rollback Plan

If you need to rollback, simply:
1. Restore: `mv app/Core/Autoloader.php.bak app/Core/Autoloader.php`
2. Revert `app/bootstrap.php` to use Autoloader instead of vendor/autoload.php
3. Delete `composer.json`, `composer.lock`, and `vendor/` directory
