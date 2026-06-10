# LMS TROUBLESHOOTING & PERFORMANCE OPTIMIZATION GUIDE
**Last Updated:** June 10, 2026

---

## 🔧 COMMON ISSUES & SOLUTIONS

### Issue 1: "Page Not Found" (404 Error)

**Symptom:**
```
Trying to access a route and getting 404 Not Found
```

**Causes & Solutions:**

1. **Routes not cached**
   ```bash
   # Clear and recache routes
   php artisan route:clear
   php artisan route:cache
   ```

2. **Missing view file**
   ```bash
   # Check if view exists in resources/views/
   # Make sure filename matches route
   # Example: route uses view('dashboard.index')
   # File should be: resources/views/dashboard/index.blade.php
   ```

3. **Route not defined**
   ```bash
   # Check routes/web.php for the route definition
   # Verify route path matches URL
   ```

**Resolution:** ✅ Use the verification guide to test all routes

---

### Issue 2: "403 Forbidden" Error

**Symptom:**
```
Accessing a page returns 403 Forbidden
```

**Causes & Solutions:**

1. **Wrong user role**
   ```
   You are logged in with wrong role
   Solution: Login with correct role (student, instructor, org, or admin)
   ```

2. **Missing middleware**
   ```bash
   # Verify route has middleware('role:...')
   # Example: Route::middleware('role:student')->group(function() { ... })
   ```

3. **User doesn't own resource**
   ```php
   // Check authorization in controller
   if ($course->user_id !== auth()->id()) {
       abort(403); // Cannot edit course not owned by user
   }
   ```

**Resolution:** ✅ Verify you have correct role and own the resource

---

### Issue 3: User Sees Another User's Data

**Symptom:**
```
Student A can see Student B's courses/assignments
Instructor A can see Instructor B's earnings
```

**Root Cause:**
```
Query not filtering by user ID
```

**Fix:**

1. **Find the problematic route**
   ```php
   // WRONG: Loads all courses
   $courses = Course::all();
   
   // CORRECT: Loads only user's courses
   $courses = Course::where('user_id', auth()->id())->get();
   ```

2. **Update all queries**
   ```php
   // For students
   ->where('user_id', auth()->id())
   
   // For instructors
   $courseIds = Course::where('user_id', auth()->id())->pluck('id');
   ->whereIn('course_id', $courseIds)
   
   // For organizations
   ->where('user_id', auth()->id())
   ->where('organization_id', auth()->id())
   ```

3. **Run verification tests**
   ```bash
   php artisan serve
   # Test with multiple users
   ```

**Status:** ✅ All queries properly filtered in current codebase

---

### Issue 4: Login Redirects to Wrong Dashboard

**Symptom:**
```
Student logs in and goes to /instructor instead of /dashboard
```

**Root Cause:**
```
Middleware redirect configuration issue
```

**Fix:**

1. **Check bootstrap/app.php**
   ```php
   $middleware->redirectUsersTo(function () {
       $user = auth()->user();
       if (!$user) {
           return route('dashboard');
       }
       return match ($user->role) {
           \App\Models\User::ROLE_ADMIN => route('admin.dashboard.dashboard'),
           \App\Models\User::ROLE_INSTRUCTOR => route('instructor.dashboard.dashboard'),
           \App\Models\User::ROLE_ORGANIZATION => route('org.dashboard.dashboard'),
           default => route('dashboard'),
       };
   });
   ```

2. **Verify route names exist**
   ```bash
   # These routes must exist:
   /admin/dashboard/dashboard -> named 'admin.dashboard.dashboard'
   /instructor -> named 'instructor.dashboard.dashboard'
   /org -> named 'org.dashboard.dashboard'
   /dashboard -> named 'dashboard'
   ```

3. **Test redirects**
   ```bash
   php artisan serve
   # Login as each role and verify redirect
   ```

**Status:** ✅ Redirects properly configured

---

### Issue 5: Pages Load Slowly

**Symptom:**
```
Dashboard takes 5-10 seconds to load
Multiple tabs show "loading..."
```

**Root Causes & Solutions:**

1. **N+1 Query Problem**
   ```php
   // WRONG: Causes N+1 queries
   $courses = Course::all();
   foreach ($courses as $course) {
       echo $course->instructor->name; // Query for each course!
   }
   
   // CORRECT: Eager loading
   $courses = Course::with('instructor')->get();
   foreach ($courses as $course) {
       echo $course->instructor->name; // No additional queries
   }
   ```

   **Check:** [routes/web.php](routes/web.php) - all relationships use `.with()`

2. **Missing Database Indexes**
   ```bash
   # Add indexes for frequently filtered columns
   Schema::table('courses', function (Blueprint $table) {
       $table->index('user_id');
       $table->index('status');
   });
   
   Schema::table('enrollments', function (Blueprint $table) {
       $table->index('user_id');
       $table->index('course_id');
   });
   ```

3. **Cache Not Enabled**
   ```bash
   # Enable caching
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

4. **Database Queries Not Optimized**
   ```bash
   # Check query count with Laravel Debugbar
   # Should see < 20 queries per page
   ```

**Resolution:** ✅ Run these commands to optimize:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize:clear
php artisan optimize
```

---

### Issue 6: Session/Login Problems

**Symptom:**
```
Login doesn't persist
User gets logged out randomly
Session errors in logs
```

**Solutions:**

1. **Clear sessions**
   ```bash
   php artisan session:clear
   ```

2. **Check session configuration** ([config/session.php](config/session.php))
   ```php
   'driver' => env('SESSION_DRIVER', 'file'),
   // Or use: 'database', 'cookie', 'redis'
   ```

3. **Clear cookies**
   ```
   Browser DevTools > Storage > Cookies > Delete all
   ```

4. **Rebuild cache**
   ```bash
   php artisan cache:clear
   php artisan config:cache
   ```

---

### Issue 7: Email Not Sending

**Symptom:**
```
Support ticket creation works but notification email doesn't send
```

**Solutions:**

1. **Check mail configuration** ([config/mail.php](config/mail.php))
   ```php
   'driver' => env('MAIL_DRIVER', 'log'),
   'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
   'port' => env('MAIL_PORT', 2525),
   'username' => env('MAIL_USERNAME'),
   'password' => env('MAIL_PASSWORD'),
   ```

2. **For development, use 'log' driver**
   ```
   MAIL_DRIVER=log
   # Emails will be logged to storage/logs/
   ```

3. **Test with Mailtrap**
   ```
   1. Create account at mailtrap.io
   2. Copy credentials to .env
   3. Test email sending
   ```

---

## ⚡ PERFORMANCE OPTIMIZATION GUIDE

### 1. Database Optimization

**Enable Query Caching:**
```php
// In routes/web.php or controllers
$courses = Cache::remember('all_active_courses', 60*24, function () {
    return Course::where('status', 'Active')->get();
});
```

**Add Indexes:**
```bash
php artisan make:migration add_indexes_to_tables
```

**Content:**
```php
Schema::table('courses', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('status');
    $table->index('slug');
});

Schema::table('enrollments', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('course_id');
    $table->index(['user_id', 'course_id']);
});

Schema::table('lessons', function (Blueprint $table) {
    $table->index('course_id');
});

Schema::table('assignments', function (Blueprint $table) {
    $table->index('course_id');
});

Schema::table('quizzes', function (Blueprint $table) {
    $table->index('course_id');
});
```

**Run migration:**
```bash
php artisan migrate
```

---

### 2. Caching Strategy

**Enable All Cache Layers:**
```bash
# Configuration caching
php artisan config:cache

# Route caching
php artisan route:cache

# View caching
php artisan view:cache

# Event caching
php artisan event:cache

# Verify caching
php artisan optimize
```

**Expected Results:**
- Config load: 100-200ms
- Route load: 200-400ms
- View rendering: < 1s
- Total page load: 1-3 seconds

---

### 3. Frontend Optimization

**Minify Assets:**
```bash
npm run build
# Creates optimized CSS/JS in public/build/
```

**Enable GZIP Compression:**
```
Add to public/.htaccess or nginx config:
mod_deflate for Apache
gzip on for Nginx
```

**Lazy Load Images:**
```html
<!-- In Blade templates -->
<img src="image.jpg" loading="lazy" alt="...">
```

---

### 4. API Response Optimization

**Pagination:**
```php
// Instead of returning all records
$users = User::paginate(20);
// Returns only 20 per page with pagination info
```

**JSON Response Optimization:**
```php
return response()->json([
    'data' => $users,
    'total' => $users->total(),
    'per_page' => $users->perPage(),
]);
```

---

### 5. Memory Optimization

**Monitor PHP Memory:**
```bash
# In php.ini
memory_limit = 256M

# For Laravel
php artisan config:get memory_limit
```

**Clear Unused Data:**
```php
// Don't load unnecessary relationships
$users = User::select('id', 'name', 'email')
    ->where('role', 'student')
    ->get();

// Not:
$users = User::all(); // Loads all columns and relationships
```

---

### 6. Server-Side Rendering Optimization

**Use CDN for Static Assets:**
```php
// In .env
CDN_URL=https://cdn.example.com

// In Blade
{{ asset('css/app.css', cdn: true) }}
```

**Enable Browser Caching:**
```
Add to public/.htaccess:
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
</IfModule>
```

---

## 🔍 DEBUGGING & MONITORING

### Enable Debug Mode (Development Only)

**Edit .env:**
```
APP_DEBUG=true
APP_LOG_LEVEL=debug
```

**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

---

### Monitor Database Queries

**Enable Query Log:**
```php
// In routes/web.php or controller
\DB::enableQueryLog();
// ... your code ...
\Log::debug(\DB::getQueryLog());
```

---

### Performance Profiling

**Install Laravel Telescope (optional):**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
```

**Access at:** `http://127.0.0.1:8000/telescope`

---

## 📊 PERFORMANCE BENCHMARKS

**Target Performance Metrics:**

| Page | Target Load Time | Acceptable Range |
|------|------------------|------------------|
| Homepage | < 1 second | < 1.5 seconds |
| Student Dashboard | < 1 second | < 1.5 seconds |
| Instructor Dashboard | < 1 second | < 2 seconds |
| Admin Dashboard | < 2 seconds | < 3 seconds |
| Courses List | < 1 second | < 1.5 seconds |
| Course Details | < 1 second | < 1.5 seconds |

**How to Measure:**
1. Open DevTools (F12)
2. Click "Network" tab
3. Reload page
4. Check "DOMContentLoaded" time at bottom

---

## 🚀 DEPLOYMENT OPTIMIZATION

**Before Production Deployment:**

```bash
# 1. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 3. Run migrations (if needed)
php artisan migrate --force

# 4. Compile assets
npm run build

# 5. Set correct permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 6. Set environment
APP_ENV=production
APP_DEBUG=false
```

---

## 📝 MAINTENANCE TASKS

**Daily:**
- Monitor error logs
- Check database disk usage
- Monitor server resources

**Weekly:**
- Review slow queries
- Check user feedback
- Verify backups

**Monthly:**
- Update dependencies
- Review security patches
- Optimize database

---

## ✅ OPTIMIZATION CHECKLIST

- [ ] Database indexes added
- [ ] Eager loading implemented
- [ ] Caching enabled (config, routes, views)
- [ ] Assets minified and compiled
- [ ] GZIP compression enabled
- [ ] Browser caching configured
- [ ] CDN configured (optional)
- [ ] Query optimization done
- [ ] Memory limits optimized
- [ ] Error logging configured
- [ ] Debug mode disabled in production
- [ ] Environment variables configured
- [ ] Backups scheduled

---

## 📞 GET HELP

For issues not listed here:
1. Check [LMS_COMPREHENSIVE_VERIFICATION_REPORT.md](LMS_COMPREHENSIVE_VERIFICATION_REPORT.md)
2. Review [COMPLETE_SYSTEM_AUDIT.md](COMPLETE_SYSTEM_AUDIT.md)
3. Check Laravel documentation: https://laravel.com/docs
4. Review error logs: `storage/logs/laravel.log`

**Status:** ✅ Troubleshooting guide complete
