# HTTPS Enforcement Testing Documentation

## Automated Tests

Due to XAMPP environment limitations and the complexity of mocking PHP superglobals (`$_SERVER`) and header functions, the automated tests focus on basic functionality:

### Unit Tests (`tests/Core/Middleware/HttpsMiddlewareTest.php`)

- **testMiddlewareCanBeInstantiated**: Verifies middleware can be instantiated
- **testSetShouldExitMethod**: Verifies the test helper method works
- **testAllowsHttpsInLocalDevelopment**: Verifies HTTPS is allowed in local development
- **testAllowsHttpInLocalDevelopment**: Verifies HTTP is allowed in local development
- **testDetectsReverseProxyHttps**: Verifies reverse proxy header detection

### Integration Tests (`tests/Integration/HttpsEnforcementIntegrationTest.php`)

- **testMiddlewareCanBeInstantiated**: Verifies middleware instantiation
- **testServiceProviderRegistration**: Verifies service provider registration
- **testMiddlewareHasSetShouldExitMethod**: Verifies test helper method

### Running Automated Tests

```bash
# Run all tests
vendor/bin/phpunit

# Run unit tests only
vendor/bin/phpunit tests/Core/

# Run integration tests only
vendor/bin/phpunit tests/Integration/
```

## Test Limitations

The automated tests have the following limitations:

1. **PHP Superglobal Mocking**: PHP's `$_SERVER` superglobal is difficult to mock reliably in PHPUnit tests
2. **Header Function Mocking**: The `header()` function cannot be easily mocked without using runkit/uopz extensions
3. **Exit Function Mocking**: The `exit()` function cannot be mocked without extensions
4. **Environment Detection**: The middleware reads environment variables at runtime, which can be inconsistent in test environments
5. **Database Dependencies**: Integration tests requiring database connections are excluded to avoid XAMPP setup complexity

## Manual Testing

For comprehensive HTTPS enforcement testing, use the manual testing procedures documented in `tests/MANUAL_TESTING.md`. This covers:

- Local development testing
- Staging environment testing
- Production environment testing
- Direct file access testing
- Reverse proxy scenarios
- Browser DevTools verification
- cURL command-line testing

## CI/CD Integration

The GitHub Actions workflow (`.github/workflows/tests.yml`) is configured to run automated tests on:
- Push to main/develop branches
- Pull requests to main/develop branches
- Multiple PHP versions (8.0, 8.1, 8.2)
- Multiple OS (Ubuntu, Windows)

## Test Environment Setup

The test environment is configured in `tests/bootstrap.php`:

```php
// Sets test environment
$_ENV['APP_ENV'] = 'testing';
$_ENV['FORCE_HTTPS'] = '1';

// Skips loading app/config/app.php to avoid conflicts
// Sets test-specific config values for app, security, rate_limit, database
```

## XAMPP-Specific Notes

When testing in XAMPP:

1. **SSL Configuration**: XAMPP may not have SSL configured by default. Install a self-signed certificate for HTTPS testing.
2. **Apache Configuration**: Ensure `.htaccess` rules are enabled in Apache configuration (`AllowOverride All`).
3. **PHP Extensions**: Ensure required PHP extensions are enabled (mbstring, xml, pdo, etc.).
4. **Session Handling**: Tests skip session start to avoid conflicts in XAMPP environment.
5. **File Permissions**: Ensure XAMPP has read/write permissions for the project directory.

## Production Testing

For production testing:

1. **Staging First**: Always test HTTPS enforcement in a staging environment before production.
2. **SSL Certificate**: Ensure valid SSL certificate is installed.
3. **Load Balancer**: If behind a load balancer, verify `X-Forwarded-Proto` header is set correctly.
4. **Browser Testing**: Test in multiple browsers (Chrome, Firefox, Safari, Edge).
5. **Mobile Testing**: Test on mobile devices to ensure HTTPS redirects work correctly.

## Troubleshooting

### Tests Fail with "Cannot open file"

Ensure you're running tests from the project root directory:
```bash
cd c:\xampp\htdocs\ulimi3
vendor/bin/phpunit
```

### Config Values Not Reading Correctly

The test bootstrap sets environment variables before loading the app bootstrap. If config values are incorrect, check:
1. `tests/bootstrap.php` is setting the correct environment
2. `app/bootstrap.php` is skipping `app/config/app.php` when `APP_ENV=testing`

### Integration Tests Fail with Database Errors

The simplified integration tests avoid database dependencies. If you need full integration testing:
1. Set up a test database
2. Add database config to `tests/bootstrap.php`
3. Restore the original integration tests

## Next Steps

To improve automated test coverage:

1. Install PHP extensions for function mocking (runkit or uopz)
2. Use a dedicated testing library like Mockery for better mocking capabilities
3. Set up a test database for full integration testing
4. Use Docker for consistent test environments across different machines
