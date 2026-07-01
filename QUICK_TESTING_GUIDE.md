# Quick LMS Testing Guide

## Test Credentials (All use Password@123)

### 👤 Student
- **Email:** student1@lms.test
- **Name:** Alice Nakato
- **Dashboard:** [http://localhost:8000/dashboard](http://localhost:8000/dashboard)

### 👨‍🏫 Instructor
- **Email:** instructor@lms.test
- **Name:** Dr. Sarah Katende
- **Dashboard:** [http://localhost:8000/instructor](http://localhost:8000/instructor)

### 🏢 Organization
- **Email:** organization@lms.test
- **Name:** Makerere University IT Department
- **Dashboard:** [http://localhost:8000/org](http://localhost:8000/org)

### 🔐 Admin
- **Email:** admin@lms.test
- **Name:** Admin User
- **Dashboard:** [http://localhost:8000/admin](http://localhost:8000/admin)

---

## Quick Page Verification Script

Run these commands in your terminal to test the system is working:

```bash
# Start the Laravel development server
php artisan serve

# In another terminal, test the API endpoints
# Test home page
curl http://localhost:8000/

# Test login
curl -X POST http://localhost:8000/login \
  -d "email=student1@lms.test&password=Password@123"

# Test instructor dashboard
curl http://localhost:8000/instructor

# Test admin dashboard  
curl http://localhost:8000/admin

# Test API documentation (if exists)
curl http://localhost:8000/api/

# Check database
php artisan tinker
# In tinker shell:
User::count()
Course::count()
Enrollment::count()
```

---

## Manual Testing Checklist

### As Student (student1@lms.test)

**Page Access:**
- [ ] Navigate to `/dashboard` - Should show enrolled courses
- [ ] Click "My Enrolled Courses" - Should list courses
- [ ] Click on a course - Should show lessons
- [ ] Click on a lesson - Should show video/document

**Features:**
- [ ] Click on a lesson - Video should auto-play or show
- [ ] Scroll down - Should see lesson description
- [ ] Click "Mark as Complete" button - Should track progress
- [ ] Check Course Curriculum sidebar - Should show all lessons
- [ ] Click "Quizzes" in dashboard - Should show available quizzes
- [ ] Take a quiz - Should be able to answer questions
- [ ] Submit quiz - Should show results
- [ ] Check "Assignments" - Should show assignment list
- [ ] Check "Certificates" - Should show earned certificates
- [ ] Check "Support Tickets" - Should allow creating tickets

---

### As Instructor (instructor@lms.test)

**Page Access:**
- [ ] Navigate to `/instructor` - Should show dashboard stats
- [ ] Click "All Courses" - Should list your courses
- [ ] Click "Create Course" - Should show course form
- [ ] Click "Earnings" - Should show revenue
- [ ] Click "Payouts" - Should show payout requests
- [ ] Click "Students" - Should show enrolled students
- [ ] Click "Assignments" - Should show all assignments
- [ ] Click "Quiz" - Should show all quizzes

**Features:**
- [ ] Create a new course - Form should have all fields
- [ ] Upload a lesson video - Should support MP4/MOV/AVI/WebM/OGG
- [ ] Upload a lesson document - Should support PDF/DOC
- [ ] Create a quiz - Should allow adding questions
- [ ] Create an assignment - Should set deadline & instructions
- [ ] View assignment submissions - Should show student work
- [ ] Grade a submission - Should allow score entry & feedback

---

### As Organization (organization@lms.test)

**Page Access:**
- [ ] Navigate to `/org` - Should show organization dashboard
- [ ] Click "All Courses" - Should show managed courses
- [ ] Click "Instructors" - Should show team members
- [ ] Click "Students" - Should show all students
- [ ] Click "Financial" - Should show revenue reports
- [ ] Click "Reviews" - Should show student feedback

**Features:**
- [ ] Create a course - Should work with org's details
- [ ] Add an instructor - Should assign team member
- [ ] View sales history - Should show revenue & dates
- [ ] Manage payouts - Should allow withdrawal requests

---

### As Admin (admin@lms.test)

**Page Access:**
- [ ] Navigate to `/admin` - Should show all stats
- [ ] Click "All Courses" - Should list all courses for approval
- [ ] Click "Instructors" - Should list all instructors
- [ ] Click "Students" - Should list all students
- [ ] Click "Organizations" - Should list all organizations
- [ ] Click "Payment Methods" - Should show payment gateways
- [ ] Click "Sale History" - Should show all transactions
- [ ] Click "Coupons" - Should allow coupon management
- [ ] Click "Blogs" - Should allow blog management
- [ ] Click "Settings" - Should allow system configuration

**Features:**
- [ ] Add payment method - Should support multiple gateways
- [ ] Create coupon - Should allow discount codes
- [ ] Approve course - If course is pending
- [ ] View financial reports - Should show all revenue
- [ ] Manage users - Should allow enabling/disabling accounts

---

## Database Verification

Connect to database and verify:

```sql
-- Check test users were created
SELECT email, role, status FROM users LIMIT 10;

-- Check courses exist
SELECT id, title, user_id FROM courses LIMIT 5;

-- Check enrollments
SELECT user_id, course_id FROM enrollments LIMIT 5;

-- Check lessons
SELECT id, course_id, title FROM lessons LIMIT 5;

-- Check quiz results
SELECT user_id, quiz_id, score FROM quiz_results LIMIT 5;
```

---

## Common Issues & Fixes

### Issue: "Page not found"
**Fix:** Make sure Laravel is running: `php artisan serve`

### Issue: "Unauthorized" when accessing dashboard
**Fix:** 
1. Make sure you're logged in
2. Check user has correct role in database
3. Clear browser cookies and login again

### Issue: Video not playing
**Fix:**
1. Check file is in `/storage/app/public/lessons/videos/`
2. Run: `php artisan storage:link`
3. Check file format is supported (MP4, WebM, OGG)

### Issue: Sidebar links not working
**Fix:**
1. Check route exists in `routes/web.php`
2. Check user role matches route middleware
3. Run: `php artisan route:clear`

### Issue: CSS/JS not loading
**Fix:**
```bash
npm install
npm run build
```

---

## Performance Optimization

To ensure excellent performance:

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize autoloader
composer dump-autoload --optimize

# Run optimization
php artisan optimize

# For production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Next Steps for Presentation

1. **Run the server:** `php artisan serve`
2. **Test each role:** Login as each user type
3. **Check all sidebar pages:** Click through each menu item
4. **Test core features:** Upload course, take quiz, submit assignment
5. **Verify performance:** Check page load times
6. **Take screenshots:** Capture key pages for presentation

---

## Reference

- Complete system audit: `COMPLETE_SYSTEM_AUDIT.md`
- Routes file: `routes/web.php`
- Test users seeder: `database/seeders/PresentationTestUsersSeeder.php`
- Video player component: `resources/views/components/video-player.blade.php`
- Lesson page: `resources/views/courses/lesson.blade.php`
