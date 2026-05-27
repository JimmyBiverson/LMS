<?php

namespace App\Http\Controllers;

use App\Models\{Blog, BlogCategory, Category, ContactMessage, Faq, HeroSection, Page, Slider, Subject, Testimonial, User, Enrollment, Certificate};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCrudController extends Controller
{
    // ─── Category CRUD ───────────────────────────────────────────────
    public function categories(): View
    {
        $categories = Category::withCount('subjects')->latest()->get();
        return view('admin.category', compact('categories'));
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        Category::create($validated);
        return back()->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);
        return back()->with('success', 'Category updated successfully!');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }

    // ─── Subject CRUD ────────────────────────────────────────────────
    public function subjects(): View
    {
        $subjects = Subject::with('category')->latest()->get();
        $categories = Category::where('status', 'active')->get();
        return view('admin.subject', compact('subjects', 'categories'));
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        Subject::create($validated);
        return back()->with('success', 'Subject created successfully!');
    }

    public function updateSubject(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $subject->update($validated);
        return back()->with('success', 'Subject updated successfully!');
    }

    public function destroySubject(Subject $subject): RedirectResponse
    {
        $subject->delete();
        return back()->with('success', 'Subject deleted successfully!');
    }

    // ─── FAQ CRUD ────────────────────────────────────────────────────
    public function faqs(): View
    {
        $faqs = Faq::orderBy('order')->get();
        return view('admin.faq', compact('faqs'));
    }

    public function storeFaq(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        Faq::create($validated);
        return back()->with('success', 'FAQ created successfully!');
    }

    public function updateFaq(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        $faq->update($validated);
        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroyFaq(Faq $faq): RedirectResponse
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }

    // ─── Slider CRUD ─────────────────────────────────────────────────
    public function sliders(): View
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.slider', compact('sliders'));
    }

    public function storeSlider(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        Slider::create($validated);
        return back()->with('success', 'Slider created successfully!');
    }

    public function updateSlider(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        $slider->update($validated);
        return back()->with('success', 'Slider updated successfully!');
    }

    public function destroySlider(Slider $slider): RedirectResponse
    {
        $slider->delete();
        return back()->with('success', 'Slider deleted successfully!');
    }

    // ─── Testimonial CRUD ────────────────────────────────────────────
    public function testimonials(): View
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonial', compact('testimonials'));
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|in:active,inactive',
        ]);
        Testimonial::create($validated);
        return back()->with('success', 'Testimonial created successfully!');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|in:active,inactive',
        ]);
        $testimonial->update($validated);
        return back()->with('success', 'Testimonial updated successfully!');
    }

    public function destroyTestimonial(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();
        return back()->with('success', 'Testimonial deleted successfully!');
    }

    // ─── Hero Section CRUD ───────────────────────────────────────────
    public function heros(): View
    {
        $heros = HeroSection::latest()->get();
        return view('admin.hero', compact('heros'));
    }

    public function storeHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'page' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);
        HeroSection::create($validated);
        return back()->with('success', 'Hero section created successfully!');
    }

    public function updateHero(Request $request, HeroSection $heroSection): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'page' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);
        $heroSection->update($validated);
        return back()->with('success', 'Hero section updated successfully!');
    }

    public function destroyHero(HeroSection $heroSection): RedirectResponse
    {
        $heroSection->delete();
        return back()->with('success', 'Hero section deleted successfully!');
    }

    // ─── Blog CRUD ───────────────────────────────────────────────────
    public function blogs(): View
    {
        $blogs = Blog::with('category', 'author')->latest()->get();
        $categories = BlogCategory::where('status', 'active')->get();
        return view('admin.blog.index', compact('blogs', 'categories'));
    }

    public function storeBlog(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        Blog::create($validated);
        return back()->with('success', 'Blog created successfully!');
    }

    public function updateBlog(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $blog->update($validated);
        return back()->with('success', 'Blog updated successfully!');
    }

    public function destroyBlog(Blog $blog): RedirectResponse
    {
        $blog->delete();
        return back()->with('success', 'Blog deleted successfully!');
    }

    public function blogCategories(): View
    {
        $categories = BlogCategory::withCount('blogs')->latest()->get();
        return view('admin.blog.category', compact('categories'));
    }

    public function storeBlogCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        BlogCategory::create($validated);
        return back()->with('success', 'Blog category created successfully!');
    }

    public function updateBlogCategory(Request $request, BlogCategory $blogCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $blogCategory->update($validated);
        return back()->with('success', 'Blog category updated successfully!');
    }

    public function destroyBlogCategory(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->delete();
        return back()->with('success', 'Blog category deleted successfully!');
    }

    // ─── Pages CRUD ──────────────────────────────────────────────────
    public function pages(): View
    {
        $pages = Page::latest()->get();
        return view('admin.page', compact('pages'));
    }

    public function storePage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        Page::create($validated);
        return back()->with('success', 'Page created successfully!');
    }

    public function updatePage(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $page->update($validated);
        return back()->with('success', 'Page updated successfully!');
    }

    public function destroyPage(Page $page): RedirectResponse
    {
        $page->delete();
        return back()->with('success', 'Page deleted successfully!');
    }

    // ─── User Management (Instructors, Students, Orgs) ──────────────
    public function instructors(): View
    {
        $users = User::where('role', User::ROLE_INSTRUCTOR)->latest()->get();
        return view('admin.instructors', compact('users'));
    }

    public function students(): View
    {
        $users = User::where('role', User::ROLE_STUDENT)->latest()->get();
        return view('admin.students', compact('users'));
    }

    public function organizations(): View
    {
        $users = User::where('role', User::ROLE_ORGANIZATION)->latest()->get();
        return view('admin.organizations', compact('users'));
    }

    // ─── Enrollments (All) ──────────────────────────────────────────
    public function allEnrollments(): View
    {
        $enrollments = Enrollment::with('user', 'course')->latest()->get();
        return view('admin.enrollment.all', compact('enrollments'));
    }

    // ─── Certificates (All) ─────────────────────────────────────────
    public function certificates(): View
    {
        $certificates = Certificate::with('course')->latest()->get();
        return view('admin.certificate.index', compact('certificates'));
    }

    // ─── Contact Messages ──────────────────────────────────────────
    public function contactMessages(): View
    {
        $messages = ContactMessage::latest()->get();
        return view('admin.contact', compact('messages'));
    }

    public function markAsRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['is_read' => true]);
        return back()->with('success', 'Message marked as read.');
    }
}
