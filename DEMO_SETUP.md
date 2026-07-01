Demo setup and seeding performed locally
======================================

What I did locally:

- Created an SQLite database file at `database/database.sqlite`.
- Generated an application key with `php artisan key:generate`.
- Ran migrations: `php artisan migrate --force`.
- Seeded test users and demo content:
  - `php artisan db:seed --class=PresentationTestUsersSeeder`
  - `php artisan db:seed --class=DemoCoursesSeeder`
  - `php artisan db:seed --class=LessonSeeder`

Verification performed:

- Confirmed public pages return HTTP 200: `/`, `/login`, `/register`, `/courses`, `/courses/web-development-fundamentals`.
- Verified the login/register left-side image exists at `public/lms/frontend/assets/images/auth/auth-loti.svg`.
- Confirmed lesson/video player and instructor lesson management UI exist and are working.

To reproduce locally:

1. Copy `.env.example` to `.env` and update any DB settings if desired.
2. Create SQLite file: `New-Item -Path database/database.sqlite -ItemType File -Force` (PowerShell) or `touch database/database.sqlite` (Linux/macOS).
3. Run `composer install` if vendor is missing.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate`.
6. Run seeders (order matters):
   - `php artisan db:seed --class=PresentationTestUsersSeeder`
   - `php artisan db:seed --class=DatabaseSeeder`
   - `php artisan db:seed --class=DemoCoursesSeeder`

Notes:

- I did not commit the local SQLite database file to the repo.
- If you'd like, I can push this `DEMO_SETUP.md` to a branch and open a PR, or push additional changes you request.
