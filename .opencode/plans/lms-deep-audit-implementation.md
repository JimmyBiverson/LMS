# LMS Deep System Audit Implementation Plan

## Blocked: Edit permissions restricted to `.opencode/plans/` only
**Please update permissions to allow full implementation, or I'll detail everything here for manual execution.**

---

## Phase 1: Critical Bug Fixes (15 items)

### 1.1 Fix course card thumbnail fallback
**File:** `resources/views/components/course-card.blade.php`  
**Change line 89:** Replace `"LMS Premium Course"` text with `"Premium Course"` (simpler, more professional)

### 1.2 Fix course detail page thumbnail fallback  
**File:** `resources/views/courses/show.blade.php`  
**Change line 256:** Replace `"LMS Premium Course"` text with `"Premium Course"`

### 1.3 Fix blog card image rendering
**File:** `resources/views/components/blog-card.blade.php`  
**Change lines 10-13:** Replace static gradient div with conditional image/fallback:
```blade
<div class="h-52 overflow-hidden">
    @if($image)
        <img src="{{ asset('storage/' . $image) }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    @else
        <div class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
            <i class="ri-file-text-line text-6xl text-primary/20"></i>
        </div>
    @endif
</div>
```

### 1.4 Remove static FAQ placeholders
**File:** `resources/views/courses/show.blade.php`  
**Change lines 105-127:** Replace hardcoded 3 static FAQs with:
```blade
@empty
<p class="text-sm text-heading/60">No FAQs available for this course.</p>
@endforelse
```

### 1.5 Fix categories page — use dynamic DB data
**File:** `resources/views/categories.blade.php`  
**Change lines 15-20:** Replace hardcoded static grid with:
```blade
@php $categories = \App\Models\Category::withCount('courses')->get(); @endphp
@forelse($categories as $cat)
<x-category-card name="{{ $cat->name }}" courseCount="{{ $cat->courses_count }}" url="/courses?categories={{ $cat->id }}" icon="ri-bookmark-line" />
@empty
<p class="col-span-full text-center text-heading/50 text-sm">No categories available yet.</p>
@endforelse
```

### 1.6 Fix admin financial static tables
**File:** `resources/views/admin/financial/sale.blade.php`  
**Change:** Replace hardcoded `@for($i=1;$i<=5;$i++)` with real query or "coming soon" message

**File:** `resources/views/admin/financial/offline.blade.php`  
**Change:** Same approach — replace mock data

### 1.7 Fix legal page placeholder text
**File:** `resources/views/terms-conditions.blade.php` — Replace `[your LMS website name]` with `config('app.name')`  
**File:** `resources/views/privacy-policy.blade.php` — Replace `[Your LMS Website Name]` with `config('app.name')`

### 1.8 Fix placeholder avatar in instructor/students
**File:** `resources/views/instructor/students.blade.php`  
**Change line 22:** Replace `placehold.co` URL with:
```blade
<div class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center text-primary text-xs font-bold">
    {{ substr($student->first_name ?? 'U', 0, 1) }}{{ substr($student->last_name ?? '', 0, 1) }}
</div>
```

### 1.9 Remove console.log statements
**File:** `resources/views/components/course-card.blade.php:61` — Remove `console.log('Autoplay blocked')`  
**File:** `resources/views/components/video-player.blade.php:147` — Remove `console.log('Preview limit reached...')`

### 1.10 Add "Continue Learning" action column to dashboard table
**File:** `resources/views/dashboard/index.blade.php`  
**Add column after "Status":** Link to resume lesson using `$e->course?->getResumeLesson()`

### 1.11 Fix N+1 query in dashboard progress sidebar
**File:** `resources/views/dashboard/index.blade.php:81`  
**Move outside loop:** Pre-compute progress in route handler (like `my-enrolled-course` does)

### 1.12 Fix instructor earnings — wrong status filter
**File:** `routes/web.php` line 351  
**Change:** `"Active"` → `"in_progress"`

### 1.13 Fix org financial — wrong status filter
**File:** `routes/web.php` line 419  
**Change:** `"Active"` → `"in_progress"`

### 1.14 Remove duplicate instructor sidebar link
**File:** `resources/views/components/instructor-sidebar.blade.php`  
**Remove lines 40-42:** Delete the duplicate "Profile" link (keep "Settings" only)

### 1.15 Implement quiz edit question functionality
**File:** `resources/views/quizzes/edit.blade.php`  
**Replace** `alert('Edit functionality coming soon!')` with actual inline edit form

---

## Phase 2: Enrollment & Progress Fixes

### 2.1 Fix Paystack coupon session key
**File:** `app/Http/Controllers/PaymentController.php`  
**In `initiatePaystack()`:** Store coupon code with key `'paystack_coupon'` (not just `'coupon_code'`)

### 2.2 Improve getResumeLesson logic
**File:** `app/Models/Course.php:158-195`  
**Change:** Prefer returning the first uncompleted lesson over the most recently touched lesson when the touched lesson is already completed

### 2.3 Add YouTube/Vimeo progress tracking
**File:** `resources/views/components/video-player.blade.php`  
**Add** YouTube IFrame API and Vimeo Froogaloop API integration for position tracking and auto-completion

---

## Phase 3: Hero & Homepage Refinement

### 3.1 Polish hero section
**File:** `resources/views/home.blade.php`  
- Reduce extra padding in sections  
- Better typography hierarchy  
- Standardize section heading patterns  
- Add `data-aos` scroll reveal attributes

### 3.2 Standardize section spacing
**File:** `resources/views/home.blade.php`  
- Consistent `py-16 lg:py-20` padding across all sections  
- Uniform heading/subheading pattern

---

## Phase 4: Frontend Animations

### 4.1 Add AOS animation library
**File:** `resources/views/layouts/app.blade.php`  
Add `<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">` to `<head>`  
Add `<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>` + `AOS.init({duration:800,once:true})` before `</body>`

### 4.2 Add scroll-reveal to homepage sections
**File:** `resources/views/home.blade.php`  
Add `data-aos="fade-up"` `data-aos-delay="..."` to:
- Hero content
- Section headings
- Course cards (staggered delay)
- Testimonial cards
- Stat counters
- Instructor cards
- Blog cards

### 4.3 Add entrance animations to dashboard
**File:** `resources/views/dashboard/index.blade.php`  
Add `data-aos="fade-up"` to stat cards and table sections

---

## Phase 5: Professional Polish

### 5.1 Fix footer tagline
**File:** `resources/views/components/footer.blade.php`  
Replace placeholder tagline with: `"Empowering learners across Uganda with quality education and skill development."`

### 5.2 Remove "Coming soon" alerts
**File:** `resources/views/components/video-player.blade.php`  
Replace `alert()` with proper disabled state

---

## File-by-File Change Summary

| File | Changes |
|---|---|
| `resources/views/components/course-card.blade.php` | Fix thumbnail fallback text, remove console.log |
| `resources/views/components/blog-card.blade.php` | Add image rendering with fallback |
| `resources/views/components/video-player.blade.php` | Remove console.log, fix coming-soon alerts |
| `resources/views/components/instructor-sidebar.blade.php` | Remove duplicate Profile link |
| `resources/views/components/footer.blade.php` | Fix placeholder tagline |
| `resources/views/courses/show.blade.php` | Remove static FAQs, improve thumbnail fallback |
| `resources/views/categories.blade.php` | Dynamic DB-driven categories |
| `resources/views/dashboard/index.blade.php` | Add Continue Learning column, fix N+1 queries |
| `resources/views/instructor/students.blade.php` | Replace placeholder avatar |
| `resources/views/admin/financial/sale.blade.php` | Replace mock data |
| `resources/views/admin/financial/offline.blade.php` | Replace mock data |
| `resources/views/terms-conditions.blade.php` | Fix placeholder text |
| `resources/views/privacy-policy.blade.php` | Fix placeholder text |
| `resources/views/quizzes/edit.blade.php` | Implement edit question |
| `resources/views/layouts/app.blade.php` | Add AOS CDN |
| `routes/web.php` | Fix Active→in_progress bugs |
| `app/Models/Course.php` | Improve getResumeLesson logic |
| `app/Http/Controllers/PaymentController.php` | Fix coupon session key |
| `resources/views/home.blade.php` | Polish hero, add animations |

---

## Verification Steps

1. `php artisan test` — all tests must pass
2. `npm run build` — no Vite errors
3. Browse homepage — verify hero, animations, course cards
4. Register as student → enroll → complete lesson → verify progress → logout → login → verify persistence
5. Login as instructor → create course → add lessons
6. Login as admin → verify all CRUD pages
7. Check browser console — no JS errors
8. Check all empty states have proper fallbacks
