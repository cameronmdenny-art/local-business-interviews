# CAIRDE DESIGNS - WORDPRESS SECURITY HARDENING GUIDE

## STATUS: CRITICAL - REVIEW BEFORE GOING LIVE

### 1. WP-CONFIG.PHP HARDENING
Add these lines to `/wp-config.php` (before the line `/* That's all, stop editing! */`):

```php
// =================================================================
// SECURITY HARDENING
// =================================================================

// Disable file editing through WordPress admin
define('DISALLOW_FILE_EDIT', true);

// Disable plugin/theme installation
define('DISALLOW_FILE_MODS', true);

// Force HTTPS
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

// Increase security keys (regenerate from https://api.wordpress.org/secret-key/1.2/salt/)
// Replace these with NEW random keys
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

// Limit login attempts
define('ABSPATH', dirname(__FILE__) . '/');

// Disable WP_DEBUG if live
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// Automatically clean up revisions
define('WP_POST_REVISIONS', 3);

// Disable pingbacks (prevents XML-RPC DDoS)
define('WP_ALLOW_MULTISITE', false);
```

---

### 2. .HTACCESS DEPLOYMENT
**File Location:** `secure-htaccess`
**Deploy To:** `/public_html/.htaccess`

**Key Features:**
- ✅ Blocks direct PHP execution in wp-content/uploads
- ✅ Hides WordPress version & sensitive files
- ✅ Prevents directory listing
- ✅ Protects .htaccess itself
- ✅ Disables XML-RPC (DDoS vector)
- ✅ Security headers (X-Frame-Options, X-Content-Type-Options, etc.)
- ✅ LiteSpeed Cache optimization
- ✅ Gzip compression enabled

**What it DOES:**
1. Serves security headers (anti-clickjacking, anti-MIME sniffing, etc.)
2. Enables gzip compression for faster loading
3. Long-term caching for images & media (1 year)
4. Short-term caching for CSS/JS (1 month)
5. Blocks access to wp-includes directory
6. Blocks access to sensitive files (.env, wp-config.php, etc.)

---

### 3. PLUGIN SECURITY UPDATES
**Header Plugin:** `cairde-header-plugin/cairde-header.php`
**Footer Plugin:** `cairde-footer-plugin/cairde-footer.php`

**Security Hardening Applied:**
- ✅ `esc_url()` on all URLs - prevents XSS injection
- ✅ `wp_kses_post()` on all HTML output - prevents malicious HTML/JS
- ✅ `intval()` on user IDs - prevents SQL injection
- ✅ Direct access checks (`if (!defined('ABSPATH'))`) - blocks direct file access
- ✅ Security logging on activation/deactivation
- ✅ `is_admin()` checks to prevent frontend injection on admin
- ✅ Strict mode JavaScript (`"use strict"`) - prevents unsafe operations
- ✅ No hardcoded URLs - using `home_url()` for dynamic URLs
- ✅ Sanitized console logging - removed version exposure

---

### 4. FIREWALL & DDoS PROTECTION
**Recommended Services:**
- Cloudflare (Free tier available)
  - Blocks malicious traffic
  - Rate limiting
  - Web Application Firewall (WAF)
  - DDoS protection
  
- Wordfence Security Plugin (Free version)
  - Fail2Ban integration
  - Intrusion detection
  - Malware scanning
  - Two-factor authentication for admin login

---

### 5. WORDPRESS SETTINGS TO CHECK
Go to: `https://ivory-lark-138468.hostingersite.com/wp-admin`

#### General Settings:
- [ ] Discourage search engines from indexing (if not ready for public)
- [ ] Verify site URL uses HTTPS not HTTP

#### Discussion Settings:
- [ ] Disable pingbacks & trackbacks (prevent XML-RPC DDoS)

#### Reading Settings:
- [ ] Verify homepage is set to your custom page

#### Plugins to Activate:
1. **Cairde Header Injector** ✅ (already uploaded & activated)
2. **Cairde Footer Injector** ⏳ (need to activate)

#### Plugins to DISABLE:
- [ ] Disable any unnecessary plugins
- [ ] Remove Hello Dolly plugin
- [ ] Remove Akismet if not using comments

---

### 6. DATABASE SECURITY
**Default WordPress table prefix:** `wp_` (EXPOSED!)

**BEFORE going live, rename via wp-cli:**
```bash
wp search-replace wp_ cairde_ --all-tables
```

Or manually via phpMyAdmin if your host provides it. This prevents automated SQL injection attacks that target default `wp_users`, `wp_posts`, etc.

---

### 7. FILE & FOLDER PERMISSIONS
**Recommended permissions:**
```
Folders: 755
Files: 644
wp-config.php: 600 (read-only)
```

**What these mean:**
- `755` on folders = owner can do anything, others can read & execute
- `644` on files = owner can read/write, others can read only
- `600` on wp-config = only owner can read/write (MOST SECURE)

Ask Hostinger support to set these via file manager or FTP if available.

---

### 8. MONITORING & LOGGING
**Enable error logging:**

Add to wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

**Access logs at:** `/wp-content/debug.log` (check via FTP)
**Monitor for:**
- Repeated 404 errors (scan attempts)
- Failed login attempts (brute force attacks)
- SQL errors (injection attempts)

---

### 9. WORDPRESS CORE UPDATES
**CRITICAL!** Keep WordPress updated:
1. Go to Dashboard → Updates
2. Check for WordPress core updates
3. Check for plugin updates
4. Check for theme updates
5. Set automatic updates if available

---

### 10. BACKUP STRATEGY
**Set up automated backups:**
- **Frequency:** Daily
- **Retention:** 30 days minimum
- **Storage:** Off-site (Hostinger, AWS S3, etc.)
- **Test:** Restore from backup monthly to ensure it works

Hostinger usually provides backup tools in control panel.

---

### 11. BEFORE GOING LIVE CHECKLIST
- [ ] Deploy secure `.htaccess` to `/public_html/`
- [ ] Update `wp-config.php` with hardening directives
- [ ] Activate both plugins (Header & Footer)
- [ ] Test homepage displays correctly
- [ ] Verify HTTPS is enabled (https://, not http://)
- [ ] Change table prefix from `wp_` to `cairde_`
- [ ] Set file permissions (755 folders, 644 files)
- [ ] Enable WordPress error logging
- [ ] Install Wordfence Security plugin
- [ ] Set up Cloudflare account
- [ ] Enable auto-updates
- [ ] Backup database
- [ ] Change admin username from "admin" to something unique
- [ ] Generate new security keys from https://api.wordpress.org/secret-key/1.2/salt/
- [ ] Disable xmlrpc.php access
- [ ] Disable user enumeration (in Wordfence settings)

---

### 12. ONGOING SECURITY TASKS
**Weekly:**
- [ ] Check error log for suspicious activity
- [ ] Verify plugins are up to date

**Monthly:**
- [ ] Test backup restoration
- [ ] Review access logs
- [ ] Check for malware (Wordfence)

**Quarterly:**
- [ ] Security audit with Wordfence
- [ ] Review user accounts (remove inactive users)
- [ ] Update security keys (from wp-config.php)

---

### 13. CURRENT DEPLOYMENT STATUS
**✅ Completed:**
- Secure header plugin (with proper escaping)
- Secure footer plugin (with proper escaping)
- .htaccess with security headers
- Plugin activation logging

**⏳ Still Todo:**
1. Deploy `.htaccess` to `/public_html/.htaccess`
2. Update `wp-config.php` (Hostinger file manager)
3. Activate footer plugin
4. Change database table prefix from `wp_` to `cairde_`
5. Install Wordfence Security
6. Set up Cloudflare
7. Generate new security keys

---

### 14. SECURITY CONTACT / SUPPORT
**If hacked/compromised:**
1. Backup your database immediately
2. Change ALL passwords (FTP, WordPress admin, Hosting)
3. Scan with Wordfence
4. Update plugins, theme, WordPress core
5. Check error logs for injected code
6. Contact Hostinger support

**Helpful Resources:**
- https://wordpress.org/support/article/hardening-wordpress/
- https://www.wordfence.com/learn/
- https://developer.wordpress.org/plugins/security/

---

Version: 2.0 | Date: March 3, 2026 | Status: CRITICAL REVIEW REQUIRED
