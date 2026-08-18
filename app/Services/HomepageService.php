<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\HeroSection;
use App\Models\SiteContent;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class HomepageService
{
    private const CACHE_TTL = 3600;

    private function safeRemember(string $key, \Closure $callback): Collection
    {
        try {
            $value = Cache::get($key);
            if ($value instanceof Collection) {
                foreach ($value as $item) {
                    if ($item instanceof \__PHP_Incomplete_Class) {
                        Cache::forget($key);
                        $newValue = $callback();
                        Cache::put($key, $newValue, self::CACHE_TTL);
                        return $newValue;
                    }
                }
                return $value;
            }
        } catch (\Throwable $e) {
            Cache::forget($key);
        }

        try {
            $value = $callback();
            Cache::put($key, $value, self::CACHE_TTL);
            return $value;
        } catch (\Throwable $e) {
            Cache::forget($key);
            return collect();
        }
    }

    public function getHeroSections(): Collection
    {
        return $this->safeRemember('homepage.hero_sections', fn () =>
            HeroSection::where('page', 'home')->where('status', 'active')->get()
        );
    }

    public function getSliders(): Collection
    {
        return $this->safeRemember('homepage.sliders', fn () =>
            Slider::where('status', 'active')->orderBy('order')->get()
        );
    }

    public function getCourses(): Collection
    {
        return $this->safeRemember('homepage.courses', fn () =>
            Course::with('lessons', 'level', 'tags')
                ->withCount(['enrollments', 'quizzes', 'assignments'])
                ->where('status', 'Active')
                ->latest()->take(8)->get()
        );
    }

    public function getFeaturedCourses(): Collection
    {
        return $this->safeRemember('homepage.featured_courses', fn () =>
            Course::with('lessons', 'level', 'tags')
                ->withCount(['enrollments', 'quizzes', 'assignments'])
                ->where('status', 'Active')
                ->where('is_featured', true)
                ->latest()->take(4)->get()
        );
    }

    public function getCategories(): Collection
    {
        return $this->safeRemember('homepage.categories', fn () =>
            Category::withCount('courses')->where('status', 'active')->latest()->take(4)->get()
        );
    }

    public function getTestimonials(): Collection
    {
        return $this->safeRemember('homepage.testimonials', fn () =>
            Testimonial::where('status', 'active')->latest()->take(3)->get()
        );
    }

    public function getBundles(): Collection
    {
        return $this->safeRemember('homepage.bundles', function () {
            if (!Schema::hasTable('bundles') || !Schema::hasTable('bundle_course')) {
                app(DatabaseSchemaRepairService::class)->ensureBundleTables();
            }

            return Bundle::withCount('courses')->where('status', 'active')->latest()->take(4)->get();
        });
    }

    public function getInstructors(): Collection
    {
        return $this->safeRemember('homepage.instructors', function () {
            if (Schema::hasTable('users')) {
                app(DatabaseSchemaRepairService::class)->ensureUserProfileColumns();
            }

            $query = User::query();
            if (Schema::hasColumn('users', 'role')) {
                $query->where('role', 'instructor');
            }
            if (Schema::hasColumn('users', 'status')) {
                $query->where('status', 'active');
            }

            return $query->latest()->take(4)->get();
        });
    }

    public function getBlogs(): Collection
    {
        return $this->safeRemember('homepage.blogs', fn () =>
            Blog::with('category', 'author')->where('status', 'published')->latest()->take(3)->get()
        );
    }

    public function getStats(): array
    {
        return Cache::remember('homepage.stats', self::CACHE_TTL, function () {
            return [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalInstructors' => User::where('role', 'instructor')->count(),
                'totalCourses' => Course::where('status', 'Active')->count(),
                'totalEnrollments' => \App\Models\Enrollment::count(),
            ];
        });
    }

    public function getSiteContentByCategory(string $category): Collection
    {
        return $this->safeRemember("homepage.site_content.{$category}", fn () =>
            SiteContent::byCategory($category)->active()->orderBy('sort_order')->get()
        );
    }

    public function clearCache(): void
    {
        $keys = [
            'homepage.hero_sections',
            'homepage.sliders',
            'homepage.featured_courses',
            'homepage.courses',
            'homepage.categories',
            'homepage.testimonials',
            'homepage.bundles',
            'homepage.instructors',
            'homepage.blogs',
            'homepage.stats',
        ];
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
