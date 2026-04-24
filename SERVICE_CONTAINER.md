# Service Container Implementation Documentation

## Overview

This document describes the implementation of a PSR-11 compliant dependency injection (DI) container for the Ulimi Agricultural Marketplace project. The container improves testability, maintainability, and aligns the project with industry standards.

## What Was Changed

### New Files

1. **app/Core/Container.php**
   - PSR-11 compliant dependency injection container
   - Features:
     - Service registration (bind)
     - Singleton registration (singleton)
     - Automatic dependency resolution (autowiring)
     - Factory function support
     - Instance injection for testing

2. **app/Core/ServiceProvider.php**
   - Centralized service registration
   - Registers core singleton services (Database, Config, Auth)
   - Registers all controllers as transient services

### Modified Files

1. **app/Core/App.php**
   - Initializes the container on application startup
   - Passes container to Router
   - Provides static access to container instance for testing

2. **app/Core/Router.php**
   - Accepts Container in constructor
   - Provides `getContainer()` method for route handlers
   - Enables controller resolution via container

3. **app/Routes/WebRoutes.php**
   - Updated to use container for controller resolution
   - All route handlers now use `$container->get(Controller::class)` instead of direct instantiation

4. **Controllers (Updated with Constructor Injection)**
   - **AuthController**: Injects User model
   - **DashboardController**: Injects User and Listing models
   - **ProductController**: Injects Listing and Commodity models
   - **BrowseController**: Injects Listing model
   - **ApiController**: Injects Listing model
   - **CartController**: No changes (uses Database directly)

5. **composer.json**
   - Added `psr/container: ^2.0` dependency
   - Updated `firebase/php-jwt` from ^6.10 to ^7.0 (security fix)

### Deleted Files

1. **app/Services/StripeService.php**
   - Removed as unused service

## Why This Change?

### Industry Standards Compliance
- **PSR-11**: The container implements the PHP-FIG standard for DI containers
- **Constructor Injection**: Preferred over property/setter injection for better immutability
- **Service Locator Anti-pattern**: Avoided - controllers receive dependencies via constructor

### Benefits
- **Testability**: Easier to mock dependencies in unit tests
- **Maintainability**: Clear dependency graph in constructors
- **Flexibility**: Easy to swap implementations (e.g., different User model for testing)
- **No Breaking Changes**: Backward compatible with existing code

### Design Decisions

**Models Not Injected via Container**
- Models (User, Listing, Commodity) are simple data access objects (DAOs)
- They don't have complex dependencies
- Direct instantiation is appropriate for this use case
- Industry standard: Services use DI, DAOs can be instantiated directly

**Singleton vs Transient**
- Core services (Database, Config, Auth) are singletons - one instance per application
- Controllers are transient - new instance per request (stateless)

## How It Works

### Container Resolution Flow

1. **Application Startup** (`app/Core/App.php`)
   ```php
   $container = $this->getContainer(); // Creates or returns existing container
   $router = new Router($container); // Pass container to router
   ```

2. **Service Registration** (`app/Core/ServiceProvider.php`)
   ```php
   $container->singleton(Database::class, function () {
       return Database::pdo();
   });
   $container->bind(\App\Controllers\AuthController::class);
   ```

3. **Route Handling** (`app/Routes/WebRoutes.php`)
   ```php
   $container = $router->getContainer();
   $router->get('/register', fn() => $container->get(AuthController::class)->showRegister());
   ```

4. **Controller Instantiation**
   - Container auto-resolves constructor dependencies
   - If User model is in constructor, container creates it
   - Controllers receive their dependencies ready to use

### Example: AuthController

**Before:**
```php
final class AuthController
{
    public function register(Request $request): void
    {
        $userModel = new User(); // Direct instantiation
        if ($userModel->findByEmail($email)) {
            // ...
        }
    }
}
```

**After:**
```php
final class AuthController
{
    private User $userModel;

    public function __construct(User $userModel = null)
    {
        $this->userModel = $userModel ?? new User(); // Fallback for backward compatibility
    }

    public function register(Request $request): void
    {
        if ($this->userModel->findByEmail($email)) {
            // ...
        }
    }
}
```

## Usage

### For Developers

**Adding a New Controller:**
1. Create the controller with constructor injection for dependencies
2. Register in `app/Core/ServiceProvider.php`:
   ```php
   $container->bind(\App\Controllers\YourController::class);
   ```
3. Use in routes:
   ```php
   $router->get('/your-route', fn() => $container->get(YourController::class)->yourMethod());
   ```

**Adding a New Service:**
1. Create the service class
2. Register in `app/Core/ServiceProvider.php`:
   ```php
   // For singleton (one instance per app)
   $container->singleton(YourService::class, function () {
       return new YourService($container->get(Database::class));
   });
   
   // For transient (new instance each time)
   $container->bind(YourService::class);
   ```

### For Testing

**Mocking Dependencies:**
```php
$mockUserModel = $this->createMock(User::class);
$controller = new AuthController($mockUserModel);
```

**Setting Custom Container:**
```php
$testContainer = new Container();
$testContainer->instance(User::class, $mockUserModel);
App::setContainer($testContainer);
```

## Security Update

### CVE-2025-45769 Fix
- **Package**: firebase/php-jwt
- **Issue**: Weak encryption in versions < 7.0.0
- **Fix**: Updated from v6.11.1 to v7.0.5
- **Status**: Resolved - no security advisories found

## Testing

All syntax checks passed:
- `app/Core/Container.php` ✓
- `app/Core/ServiceProvider.php` ✓
- `app/Core/App.php` ✓
- `app/Core/Router.php` ✓
- `app/Controllers/AuthController.php` ✓
- `app/Controllers/DashboardController.php` ✓
- `app/Controllers/ProductController.php` ✓
- `app/Controllers/BrowseController.php` ✓
- `app/Controllers/ApiController.php` ✓
- `app/Routes/WebRoutes.php` ✓

Container functionality verified:
- ✓ Singleton services (Database, Config, Auth)
- ✓ Transient controllers
- ✓ Dependency injection
- ✓ PSR-11 compliance
- ✓ Auto-resolution

## Migration Notes

### Backward Compatibility
- All existing code continues to work
- Controllers have fallback to direct instantiation (`?? new Model()`)
- No breaking changes to public APIs

### Future Improvements
- Consider removing fallback direct instantiation in controllers
- Add interface-based dependency injection for better abstraction
- Implement service provider interfaces for modular registration
- Add container caching for performance optimization

## References

- [PSR-11: Container Interface](https://www.php-fig.org/psr/psr-11/)
- [PHP-FIG Standards](https://www.php-fig.org/psr/)
- [Dependency Injection Best Practices](https://martinfowler.com/articles/injection.html)
