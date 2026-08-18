<?php

namespace App\Http\Controllers;

use App\Services\HomepageService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly HomepageService $homepageService,
    ) {}

    public function __invoke(): View
    {
        $courses = $this->homepageService->getCourses();
        $featuredCourses = $this->homepageService->getFeaturedCourses();
        $categories = $this->homepageService->getCategories();
        $testimonials = $this->homepageService->getTestimonials();
        $bundles = $this->homepageService->getBundles();
        $instructors = $this->homepageService->getInstructors();
        $blogs = $this->homepageService->getBlogs();
        $stats = $this->homepageService->getStats();
        $sliders = $this->homepageService->getSliders();
        $heroSections = $this->homepageService->getHeroSections();

        return view('home', compact(
            'courses', 'featuredCourses', 'categories', 'testimonials',
            'bundles', 'instructors', 'blogs', 'stats', 'sliders', 'heroSections'
        ));
    }
}
