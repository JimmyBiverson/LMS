# LMS Project Conventions

## Tech Stack
- **PHP**: 8.3+
- **Framework**: Laravel 13.8
- **Frontend**: Tailwind CSS 4, Alpine.js 3.14.8 (CDN), Remixicon
- **Build**: Vite 8 + laravel-vite-plugin + @tailwindcss/vite
- **Database**: SQLite (dev/testing), MySQL (production optional)
- **Auth**: Session-based (web), Sanctum tokens (API)
- **PDF**: barryvdh/laravel-dompdf
- **Payments**: Paystack
- **Real-time**: Laravel Reverb (planned)
- **Cache/Queue/Session**: Database-driven

## Roles
- `student` - dashboard prefix `/dashboard`
- `instructor` - dashboard prefix `/instructor`
- `organization` - dashboard prefix `/org`
- `admin` - dashboard prefix `/admin`
- `staff` - limited admin access

## Routes
- All routes in `routes/web.php` (main), `routes/api.php`, `routes/console.php`
- Role-based middleware: `->middleware('role:'.User::ROLE_STUDENT)`
- Use `redirectToDashboard()` for post-login/register redirects

## Naming Conventions
- Models: `PascalCase`, singular
- Controllers: `PascalCase`, organized by role in sub-namespaces
- Views: `snake_case.blade.php`, organized by role/feature
- Migrations: `YYYY_MM_DD_HHMMSS_descriptive_name.php`
- Services: `PascalCase` with `Service` suffix in `app/Services/`

## Service Layer
Services exist in `app/Services/` but not all are wired to controllers. Wire them during feature work:
- `ContentManagementService` - homepage/content CRUD
- `ProfileManagementService` - profile/image/password
- `AssignmentEnhancementService` - assignments with late penalty/file validation
- `QuizEnhancementService` - quizzes with auto-grading/timer
- `CourseEnhancementService` - course preview videos/thumbnails
- `ActivityMonitoringService` - admin activity tracking

## Testing
- PHPUnit with in-memory SQLite
- Always use `composer test` (not `php artisan test`) — the composer script runs `config:clear` first, preventing cached config from overriding PHPUnit's `:memory:` database setting
- Factories in `database/factories/`
- Use `RefreshDatabase` trait in tests
- 311 tests passed (890 assertions)

## Key Commands
- `php artisan serve` - start dev server
- `composer test` - run all tests (safe: clears config first)
- `php artisan test` - run all tests (only after `php artisan config:clear`)
- `php artisan migrate:fresh --seed` - reset DB with seed data
- `npm run build` - build frontend assets
- `npm run dev` - Vite dev server
