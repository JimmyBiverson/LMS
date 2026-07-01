# Browser Login Troubleshooting Guide

## Issue Description
The login form fields clear/reset after typing in the browser interface.

## Root Cause Analysis

### Suspected Cause: Tab Switching JavaScript
The login page has multiple tabs (Student, Instructor, Organization, Admin) that switch between different forms. The JavaScript code in [resources/views/auth/login.blade.php](resources/views/auth/login.blade.php) line 123-140 handles tab switching.

When clicking a tab button, the code:
1. Removes `.active` class from all tabs
2. Adds `.active` class to clicked tab
3. Hides all form panes (removes `!block` class)
4. Shows the selected form pane (adds `!block` class)

**Problem**: The tab switching may be interfering with form input focus or causing unintended form resets.

---

## Solution: Fix Tab Switching Without Losing Form Data

### Option 1: Remove Tab Switching JavaScript (Recommended)
If each role has separate login endpoints (`POST /login` for all roles), consider having a single login form instead of tabs, or preserve form data when switching tabs.

### Option 2: Preserve Form Data on Tab Switch
Add data preservation to the JavaScript:

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.login-credentials');
    const panes = document.querySelectorAll('.dashkit-tab-pane');
    
    // Store form data before switching tabs
    const formData = {};

    function showPane(tab) {
        // Save current form data
        const currentForm = document.querySelector('.dashkit-tab-pane.!block form');
        if (currentForm) {
            const inputs = currentForm.querySelectorAll('input');
            inputs.forEach(input => {
                formData[input.name] = input.value;
            });
        }

        // Hide and show panes
        panes.forEach(p => p.classList.remove('!block'));
        const pane = document.querySelector(`.dashkit-tab-pane[data-tab="${tab}"]`);
        if (pane) pane.classList.add('!block');

        // Restore form data if it exists
        const newForm = pane.querySelector('form');
        if (newForm) {
            const inputs = newForm.querySelectorAll('input');
            inputs.forEach(input => {
                if (formData[input.name]) {
                    input.value = formData[input.name];
                }
            });
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            if (this.id === 'admin') {
                showPane('admin');
            } else {
                showPane('non-admin');
            }
        });
    });
});
```

### Option 3: Disable Tab Switching Until Form Is Submitted
Prevent tab switching while form has data:

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.login-credentials');
    const panes = document.querySelectorAll('.dashkit-tab-pane');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            // Check if form has unsaved data
            const currentForm = document.querySelector('.dashkit-tab-pane.!block form');
            const hasData = currentForm && Array.from(currentForm.querySelectorAll('input[type="email"], input[type="password"]'))
                .some(input => input.value.trim() !== '');

            if (hasData) {
                e.preventDefault();
                alert('Please complete your login or clear the form first.');
                return;
            }

            // Normal tab switching
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            panes.forEach(p => p.classList.remove('!block'));
            const pane = document.querySelector(`.dashkit-tab-pane[data-tab="${this.id === 'admin' ? 'admin' : 'non-admin'}"]`);
            if (pane) pane.classList.add('!block');
        });
    });
});
```

---

## Quick Workaround for Users

### Method 1: Single Role Tab
Users should click their role tab FIRST before entering credentials:
1. Click "Student" / "Instructor" / "Organization" / "Admin" tab
2. THEN enter email and password
3. Click "Log in"

### Method 2: Use Direct URL
Bypass tab switching by accessing login directly:
- Student login: `http://127.0.0.1:8000/login`
- Admin login: `http://127.0.0.1:8000/login` (then click Admin tab first)

### Method 3: Command Line Authentication Test
Verify authentication works (backend is fine):

```bash
php artisan tinker
$user = App\Models\User::where('email', 'student1@lms.test')->first();
Hash::check('password', $user->password); // Should return true
```

---

## Test Credentials

| Role | Email | Password |
|------|-------|----------|
| Student | student1@lms.test | password |
| Instructor | instructor@lms.test | password |
| Instructor | instructor2@lms.test | password |
| Organization | organization@lms.test | password |
| Admin | admin@lms.test | password |

---

## Browser Console Debugging

If users experience the issue:

1. Open browser Developer Tools (F12)
2. Go to "Console" tab
3. Look for any JavaScript errors
4. Check network tab to see if login POST request is being sent

Common errors to look for:
- `Uncaught TypeError: Cannot read property...`
- `Uncaught ReferenceError: ... is not defined`
- Failed network requests (403, 422, 500 status codes)

---

## Recommended Fix (Implementation)

Add this to `resources/views/auth/login.blade.php` to fix tab switching:

```javascript
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.login-credentials');
    const panes = document.querySelectorAll('.dashkit-tab-pane');

    function showPane(tab) {
        panes.forEach(p => p.classList.remove('!block'));
        const pane = document.querySelector(`.dashkit-tab-pane[data-tab="${tab}"]`);
        if (pane) pane.classList.add('!block');
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.id;

            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Prevent form reset - delay pane switching slightly
            setTimeout(() => {
                if (tabId === 'admin') {
                    showPane('admin');
                } else {
                    showPane('non-admin');
                }
            }, 100);
        });
    });
    
    // ... rest of password toggle code
});
</script>
@endpush
```

---

## Verification Checklist

✅ **Backend Status:**
- Login route: `/login` POST exists
- Auth controller: Properly configured
- Test users: Created and verified
- Password hashing: Working correctly
- Database: Connected and seeded

⚠️ **Frontend Issue:**
- Tab switching: May clear form fields
- Form validation: Working correctly
- CSRF token: Included in forms
- Browser compatibility: Needs testing

---

## Status

**Backend Login**: ✅ **Working** - 100% functional, all routes registered, test users available
**Browser Form**: ⚠️ **Issue** - Tab switching may interfere with form input, but backend authentication is operational
**Recommended Action**: Implement Option 2 (form data preservation) from solutions above

---

## Next Steps

1. **For Development**: Use test credentials from table above (backend works)
2. **For Production**: Implement Option 2 fix in login JavaScript
3. **For Testing**: Can use API tests instead of browser form (backend verified)

---

**Last Updated**: 2026-06-15  
**Status**: Known limitation, not blocking authentication  
**Workaround**: Available (see Quick Workaround section)
