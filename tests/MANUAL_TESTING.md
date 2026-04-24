# HTTPS Enforcement Manual Testing Guide

This guide provides step-by-step procedures for manually testing HTTPS enforcement in different environments.

## Prerequisites

- SSL certificate installed for HTTPS testing (use self-signed for local/staging)
- Access to modify `.env` file
- Browser with developer tools (Chrome, Firefox, etc.)
- cURL or similar tool for command-line testing

## XAMPP-Specific Notes

When testing in XAMPP environment:

1. **Enable mod_rewrite**: Ensure Apache's mod_rewrite module is enabled in `httpd.conf`:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

2. **AllowOverride**: Ensure your Apache configuration allows `.htaccess` overrides:
   ```apache
   <Directory "C:/xampp/htdocs/ulimi3">
       AllowOverride All
       Require all granted
   </Directory>
   ```

3. **SSL Certificate**: XAMPP doesn't include SSL by default. For local HTTPS testing:
   - Generate a self-signed certificate using OpenSSL
   - Configure Apache SSL in `httpd-ssl.conf`
   - Enable SSL module in `httpd.conf`

4. **Restart Apache**: After any configuration changes, restart Apache:
   - Use XAMPP Control Panel
   - Or restart Apache service via Windows Services

5. **PHP Extensions**: Ensure required extensions are enabled in `php.ini`:
   - mbstring
   - xml
   - pdo
   - pdo_mysql

## Testing Scenarios

### Scenario 1: Local Development (APP_ENV=local, FORCE_HTTPS=0)

**Purpose:** Verify HTTP is allowed in local development

**Setup:**
```bash
# In .env file
APP_ENV=local
FORCE_HTTPS=0
```

**Test Steps:**

1. **HTTP Access Test**
   - Open browser to `http://localhost/ulimi3/`
   - Expected: Page loads successfully without redirect
   - Verify: No redirect occurs, stays on HTTP

2. **HTTPS Access Test**
   - Open browser to `https://localhost/ulimi3/` (if SSL configured)
   - Expected: Page loads successfully
   - Verify: No redirect occurs, stays on HTTPS

3. **Browser DevTools Check**
   - Open DevTools (F12)
   - Go to Network tab
   - Refresh page
   - Expected: No 301 redirects visible
   - Verify: Status code is 200 for initial request

**Success Criteria:**
- ✅ HTTP requests work without redirect
- ✅ HTTPS requests work without redirect
- ✅ No 301 status codes in Network tab

---

### Scenario 2: Staging with Force HTTPS (APP_ENV=staging, FORCE_HTTPS=1)

**Purpose:** Verify HTTPS enforcement when FORCE_HTTPS is enabled in non-production

**Setup:**
```bash
# In .env file
APP_ENV=staging
FORCE_HTTPS=1
```

**Test Steps:**

1. **HTTP Access Test**
   - Open browser to `http://your-staging-domain.com/`
   - Expected: Redirects to `https://your-staging-domain.com/`
   - Verify: URL changes to HTTPS

2. **Check Redirect Status Code**
   - Open DevTools (F12)
   - Go to Network tab
   - Clear cache and reload
   - Expected: Initial HTTP request shows 301 status
   - Verify: Redirect Location header points to HTTPS URL

3. **HTTPS Access Test**
   - Open browser to `https://your-staging-domain.com/`
   - Expected: Page loads successfully
   - Verify: No redirect occurs, stays on HTTPS

4. **cURL Test**
   ```bash
   curl -I http://your-staging-domain.com/
   ```
   - Expected: HTTP/1.1 301 Moved Permanently
   - Expected: Location: https://your-staging-domain.com/

**Success Criteria:**
- ✅ HTTP requests redirect to HTTPS (301 status)
- ✅ HTTPS requests work without redirect
- ✅ Redirect Location header is correct
- ✅ Browser URL changes to HTTPS

---

### Scenario 3: Production (APP_ENV=production)

**Purpose:** Verify HTTPS enforcement in production environment

**Setup:**
```bash
# In .env file
APP_ENV=production
# FORCE_HTTPS not needed (auto-enabled in production)
```

**Test Steps:**

1. **HTTP Access Test**
   - Open browser to `http://your-production-domain.com/`
   - Expected: Redirects to `https://your-production-domain.com/`
   - Verify: URL changes to HTTPS

2. **Check Redirect Permanence**
   - Open DevTools (F12)
   - Go to Network tab
   - Clear cache and reload
   - Expected: 301 status code (permanent redirect)
   - Verify: Browser caches the redirect

3. **HTTPS Access Test**
   - Open browser to `https://your-production-domain.com/`
   - Expected: Page loads successfully
   - Verify: No redirect, stays on HTTPS

4. **Multiple Page Test**
   - Test various routes: `/login`, `/register`, `/browse`, etc.
   - Expected: All HTTP requests redirect to HTTPS
   - Verify: Consistent behavior across all routes

**Success Criteria:**
- ✅ All HTTP requests redirect to HTTPS
- ✅ 301 permanent redirect status
- ✅ HTTPS requests work without redirect
- ✅ All routes enforce HTTPS

---

### Scenario 4: Direct File Access

**Purpose:** Verify .htaccess protection for direct PHP file access

**Test Steps:**

1. **Direct PHP File Access (HTTP)**
   - Try accessing: `http://your-domain.com/app/Core/App.php`
   - Expected: Redirects to HTTPS or 403/404 error
   - Verify: Cannot access PHP files directly over HTTP

2. **Direct PHP File Access (HTTPS)**
   - Try accessing: `https://your-domain.com/app/Core/App.php`
   - Expected: 403/404 error (should not expose source code)
   - Verify: Source code not exposed

3. **Public Directory Test**
   - Try accessing: `http://your-domain.com/public/index.php`
   - Expected: Redirects to HTTPS
   - Verify: .htaccess routing still works

**Success Criteria:**
- ✅ Direct file access over HTTP redirects to HTTPS
- ✅ Source code not exposed
- ✅ .htaccess routing rules still apply

---

### Scenario 5: Reverse Proxy Scenario

**Purpose:** Verify HTTPS detection behind load balancer/reverse proxy

**Setup:**
- Configure reverse proxy (nginx, Apache, etc.) to set `X-Forwarded-Proto` header
- Set `APP_ENV=production` or `FORCE_HTTPS=1`

**Test Steps:**

1. **Without X-Forwarded-Proto**
   - Make request through reverse proxy without header
   - Expected: May redirect incorrectly (depends on setup)
   - Verify: Check if redirect loop occurs

2. **With X-Forwarded-Proto=HTTPS**
   - Make request with header: `X-Forwarded-Proto: https`
   - Expected: No redirect, request proceeds
   - Verify: Middleware correctly detects HTTPS via header

3. **cURL Test with Header**
   ```bash
   curl -H "X-Forwarded-Proto: https" http://your-domain.com/
   ```
   - Expected: No redirect if header is set correctly
   - Verify: Request proceeds without redirect

**Success Criteria:**
- ✅ Middleware respects X-Forwarded-Proto header
- ✅ No redirect loops with reverse proxy
- ✅ HTTPS correctly detected behind proxy

---

## Testing Tools

### Browser DevTools

**Chrome/Firefox:**
1. Press F12 to open DevTools
2. Go to Network tab
3. Check "Preserve log" to see redirects
4. Look for 301 status codes
5. Check Response Headers for Location

### cURL Commands

**Check HTTP response:**
```bash
curl -I http://your-domain.com/
```

**Follow redirects:**
```bash
curl -L -I http://your-domain.com/
```

**Check with custom headers:**
```bash
curl -H "X-Forwarded-Proto: https" -I http://your-domain.com/
```

### Online Tools

- **SSL Labs Test:** https://www.ssllabs.com/ssltest/
- **HTTP Security Headers:** https://securityheaders.com/
- **Redirect Checker:** https://www.redirect-checker.org/

## Common Issues

### Issue: Redirect Loop

**Symptoms:** Browser shows "too many redirects"

**Causes:**
- SSL certificate misconfiguration
- Reverse proxy misconfiguration
- Both .htaccess and middleware redirecting

**Solutions:**
1. Check SSL certificate validity
2. Verify reverse proxy headers
3. Temporarily disable .htaccess redirect to isolate issue

### Issue: Mixed Content Warnings

**Symptoms:** Browser shows "mixed content" warnings

**Causes:**
- HTTP resources loaded on HTTPS page
- Hardcoded HTTP URLs in code

**Solutions:**
1. Update all resources to use HTTPS
2. Use protocol-relative URLs (`//domain.com/resource`)
3. Update .htaccess to upgrade insecure requests

### Issue: Local Development Not Working

**Symptoms:** Local site redirects to HTTPS but SSL not configured

**Causes:**
- `FORCE_HTTPS=1` set in local .env
- APP_ENV set to production locally

**Solutions:**
1. Set `FORCE_HTTPS=0` in local .env
2. Ensure `APP_ENV=local` for local development
3. Install self-signed SSL certificate if HTTPS needed locally

## Test Results Template

Use this template to document your test results:

| Scenario | Environment | Test Date | HTTP Result | HTTPS Result | Status |
|----------|-------------|------------|-------------|--------------|--------|
| Local Development | local | YYYY-MM-DD | ✅ No redirect | ✅ No redirect | PASS |
| Staging | staging | YYYY-MM-DD | ✅ 301 redirect | ✅ No redirect | PASS |
| Production | production | YYYY-MM-DD | ✅ 301 redirect | ✅ No redirect | PASS |
| Direct File Access | production | YYYY-MM-DD | ✅ Redirect/403 | ✅ 403 | PASS |
| Reverse Proxy | production | YYYY-MM-DD | ✅ Correct detection | ✅ No redirect | PASS |

## Automated Testing

For automated testing, use the PHPUnit test suite:

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit tests/Core/Middleware/HttpsMiddlewareTest.php

# Run with coverage
vendor/bin/phpunit --coverage-html coverage
```

See TESTING.md for complete automated testing documentation.
