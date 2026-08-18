<?php

namespace Tests\Unit;

use App\Models\HeroSection;
use App\Models\Page;
use App\Models\Setting;
use App\Models\SiteContent;
use App\Models\Slider;
use App\Services\ContentManagementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ContentManagementServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContentManagementService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ContentManagementService();
        
        // Clear cache before each test
        Cache::flush();
    }

    // ========== Settings Tests ==========

    public function test_gets_all_settings_when_no_keys_provided(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'My LMS']);
        Setting::create(['key' => 'site_logo', 'value' => '/logo.png']);
        Setting::create(['key' => 'theme_color', 'value' => '#3490dc']);

        $settings = $this->service->getSettings();

        $this->assertCount(3, $settings);
        $this->assertEquals('My LMS', $settings['site_name']);
        $this->assertEquals('/logo.png', $settings['site_logo']);
        $this->assertEquals('#3490dc', $settings['theme_color']);
    }

    public function test_gets_specific_settings_when_keys_provided(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'My LMS']);
        Setting::create(['key' => 'site_logo', 'value' => '/logo.png']);
        Setting::create(['key' => 'theme_color', 'value' => '#3490dc']);

        $settings = $this->service->getSettings(['site_name', 'theme_color']);

        $this->assertCount(2, $settings);
        $this->assertEquals('My LMS', $settings['site_name']);
        $this->assertEquals('#3490dc', $settings['theme_color']);
        $this->assertArrayNotHasKey('site_logo', $settings->toArray());
    }

    public function test_settings_are_cached(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'My LMS']);

        // First call - should hit database
        $settings1 = $this->service->getSettings(['site_name']);
        $this->assertEquals('My LMS', $settings1['site_name']);

        // Update database directly (bypassing service)
        Setting::where('key', 'site_name')->update(['value' => 'Updated LMS']);

        // Second call - should return cached value
        $settings2 = $this->service->getSettings(['site_name']);
        $this->assertEquals('My LMS', $settings2['site_name']); // Still cached value
    }

    public function test_update_setting_creates_new_setting(): void
    {
        $result = $this->service->updateSetting('new_key', 'new_value');

        $this->assertTrue($result);
        $this->assertEquals('new_value', Setting::getValue('new_key'));
    }

    public function test_update_setting_updates_existing_setting(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'Old Name']);

        $result = $this->service->updateSetting('site_name', 'New Name');

        $this->assertTrue($result);
        $this->assertEquals('New Name', Setting::getValue('site_name'));
    }

    public function test_update_setting_clears_cache(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'My LMS']);

        // Cache the setting
        $settings1 = $this->service->getSettings(['site_name']);
        $this->assertEquals('My LMS', $settings1['site_name']);

        // Update through service (should clear cache)
        $this->service->updateSetting('site_name', 'Updated LMS');

        // Should get fresh value from database
        $settings2 = $this->service->getSettings(['site_name']);
        $this->assertEquals('Updated LMS', $settings2['site_name']);
    }

    // ========== Page Tests ==========

    public function test_gets_page_by_slug(): void
    {
        $page = Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => 'About our company',
            'status' => 'published'
        ]);

        $result = $this->service->getPage('about-us');

        $this->assertNotNull($result);
        $this->assertEquals($page->id, $result->id);
        $this->assertEquals('About Us', $result->title);
    }

    public function test_returns_null_for_non_existent_page(): void
    {
        $result = $this->service->getPage('non-existent');

        $this->assertNull($result);
    }

    public function test_returns_null_for_unpublished_page(): void
    {
        Page::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page',
            'content' => 'Draft content',
            'status' => 'draft'
        ]);

        $result = $this->service->getPage('draft-page');

        $this->assertNull($result);
    }

    public function test_pages_are_cached(): void
    {
        Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => 'Original content',
            'status' => 'published'
        ]);

        // First call - should hit database
        $page1 = $this->service->getPage('about-us');
        $this->assertEquals('Original content', $page1->content);

        // Update database directly
        Page::where('slug', 'about-us')->update(['content' => 'Updated content']);

        // Second call - should return cached value
        $page2 = $this->service->getPage('about-us');
        $this->assertEquals('Original content', $page2->content);
    }

    public function test_update_or_create_page_creates_new_page(): void
    {
        $page = $this->service->updateOrCreatePage([
            'slug' => 'new-page',
            'title' => 'New Page',
            'content' => 'New content',
            'status' => 'published'
        ]);

        $this->assertNotNull($page);
        $this->assertEquals('new-page', $page->slug);
        $this->assertEquals('New Page', $page->title);
    }

    public function test_update_or_create_page_updates_existing_page(): void
    {
        Page::create([
            'slug' => 'about-us',
            'title' => 'Old Title',
            'content' => 'Old content',
            'status' => 'published'
        ]);

        $page = $this->service->updateOrCreatePage([
            'slug' => 'about-us',
            'title' => 'New Title',
            'content' => 'New content',
            'status' => 'published'
        ]);

        $this->assertEquals('New Title', $page->title);
        $this->assertEquals('New content', $page->content);
        $this->assertEquals(1, Page::where('slug', 'about-us')->count());
    }

    public function test_delete_page_removes_page(): void
    {
        Page::create([
            'slug' => 'about-us',
            'title' => 'About Us',
            'content' => 'Content',
            'status' => 'published'
        ]);

        $result = $this->service->deletePage('about-us');

        $this->assertTrue($result);
        $this->assertEquals(0, Page::where('slug', 'about-us')->count());
    }

    public function test_delete_page_returns_false_for_non_existent_page(): void
    {
        $result = $this->service->deletePage('non-existent');

        $this->assertFalse($result);
    }

    // ========== Hero Section Tests ==========

    public function test_gets_active_hero_sections(): void
    {
        HeroSection::create([
            'title' => 'Hero 1',
            'subtitle' => 'Subtitle 1',
            'description' => 'Description 1',
            'page' => 'home',
            'status' => 'active'
        ]);
        
        HeroSection::create([
            'title' => 'Hero 2',
            'subtitle' => 'Subtitle 2',
            'description' => 'Description 2',
            'page' => 'home',
            'status' => 'inactive'
        ]);

        $heroSections = $this->service->getHeroSections(true);

        $this->assertCount(1, $heroSections);
        $this->assertEquals('Hero 1', $heroSections[0]->title);
    }

    public function test_gets_all_hero_sections_when_active_only_false(): void
    {
        HeroSection::create([
            'title' => 'Hero 1',
            'subtitle' => 'Subtitle 1',
            'description' => 'Description 1',
            'page' => 'home',
            'status' => 'active'
        ]);
        
        HeroSection::create([
            'title' => 'Hero 2',
            'subtitle' => 'Subtitle 2',
            'description' => 'Description 2',
            'page' => 'home',
            'status' => 'inactive'
        ]);

        $heroSections = $this->service->getHeroSections(false);

        $this->assertCount(2, $heroSections);
    }

    public function test_hero_sections_are_cached(): void
    {
        HeroSection::create([
            'title' => 'Hero 1',
            'subtitle' => 'Subtitle 1',
            'description' => 'Description 1',
            'page' => 'home',
            'status' => 'active'
        ]);

        // First call
        $heroSections1 = $this->service->getHeroSections();
        $this->assertCount(1, $heroSections1);

        // Add another active hero section
        HeroSection::create([
            'title' => 'Hero 2',
            'subtitle' => 'Subtitle 2',
            'description' => 'Description 2',
            'page' => 'home',
            'status' => 'active'
        ]);

        // Second call - should return cached value
        $heroSections2 = $this->service->getHeroSections();
        $this->assertCount(1, $heroSections2); // Still cached
    }

    public function test_update_or_create_hero_section_creates_new(): void
    {
        $heroSection = $this->service->updateOrCreateHeroSection([
            'title' => 'New Hero',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'page' => 'home',
            'status' => 'active'
        ]);

        $this->assertNotNull($heroSection);
        $this->assertEquals('New Hero', $heroSection->title);
    }

    public function test_update_or_create_hero_section_updates_existing(): void
    {
        $existing = HeroSection::create([
            'title' => 'Old Hero',
            'subtitle' => 'Old Subtitle',
            'description' => 'Old Description',
            'page' => 'home',
            'status' => 'active'
        ]);

        $heroSection = $this->service->updateOrCreateHeroSection([
            'title' => 'Updated Hero',
            'subtitle' => 'Updated Subtitle',
            'description' => 'Updated Description',
            'page' => 'home',
            'status' => 'active'
        ], $existing->id);

        $this->assertEquals($existing->id, $heroSection->id);
        $this->assertEquals('Updated Hero', $heroSection->title);
    }

    public function test_delete_hero_section_removes_hero_section(): void
    {
        $heroSection = HeroSection::create([
            'title' => 'Hero',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'page' => 'home',
            'status' => 'active'
        ]);

        $result = $this->service->deleteHeroSection($heroSection->id);

        $this->assertTrue($result);
        $this->assertEquals(0, HeroSection::where('id', $heroSection->id)->count());
    }

    // ========== Slider Tests ==========

    public function test_gets_active_sliders(): void
    {
        Slider::create([
            'title' => 'Slider 1',
            'subtitle' => 'Subtitle 1',
            'description' => 'Description 1',
            'btn_text' => 'Learn More',
            'btn_link' => '/courses',
            'order' => 1,
            'status' => 'active'
        ]);
        
        Slider::create([
            'title' => 'Slider 2',
            'subtitle' => 'Subtitle 2',
            'description' => 'Description 2',
            'btn_text' => 'Learn More',
            'btn_link' => '/courses',
            'order' => 2,
            'status' => 'inactive'
        ]);

        $sliders = $this->service->getSliders(true);

        $this->assertCount(1, $sliders);
        $this->assertEquals('Slider 1', $sliders[0]->title);
    }

    public function test_sliders_are_ordered_correctly(): void
    {
        Slider::create([
            'title' => 'Slider 2',
            'order' => 2,
            'status' => 'active'
        ]);
        
        Slider::create([
            'title' => 'Slider 1',
            'order' => 1,
            'status' => 'active'
        ]);
        
        Slider::create([
            'title' => 'Slider 3',
            'order' => 3,
            'status' => 'active'
        ]);

        $sliders = $this->service->getSliders();

        $this->assertEquals('Slider 1', $sliders[0]->title);
        $this->assertEquals('Slider 2', $sliders[1]->title);
        $this->assertEquals('Slider 3', $sliders[2]->title);
    }

    public function test_sliders_are_cached(): void
    {
        Slider::create([
            'title' => 'Slider 1',
            'order' => 1,
            'status' => 'active'
        ]);

        // First call
        $sliders1 = $this->service->getSliders();
        $this->assertCount(1, $sliders1);

        // Add another slider
        Slider::create([
            'title' => 'Slider 2',
            'order' => 2,
            'status' => 'active'
        ]);

        // Second call - should return cached value
        $sliders2 = $this->service->getSliders();
        $this->assertCount(1, $sliders2); // Still cached
    }

    public function test_update_or_create_slider_creates_new(): void
    {
        $slider = $this->service->updateOrCreateSlider([
            'title' => 'New Slider',
            'subtitle' => 'Subtitle',
            'description' => 'Description',
            'btn_text' => 'Learn More',
            'btn_link' => '/courses',
            'order' => 1,
            'status' => 'active'
        ]);

        $this->assertNotNull($slider);
        $this->assertEquals('New Slider', $slider->title);
    }

    public function test_update_or_create_slider_updates_existing(): void
    {
        $existing = Slider::create([
            'title' => 'Old Slider',
            'order' => 1,
            'status' => 'active'
        ]);

        $slider = $this->service->updateOrCreateSlider([
            'title' => 'Updated Slider',
            'order' => 1,
            'status' => 'active'
        ], $existing->id);

        $this->assertEquals($existing->id, $slider->id);
        $this->assertEquals('Updated Slider', $slider->title);
    }

    public function test_delete_slider_removes_slider(): void
    {
        $slider = Slider::create([
            'title' => 'Slider',
            'order' => 1,
            'status' => 'active'
        ]);

        $result = $this->service->deleteSlider($slider->id);

        $this->assertTrue($result);
        $this->assertEquals(0, Slider::where('id', $slider->id)->count());
    }

    // ========== SiteContent Tests ==========

    public function test_gets_site_content_by_key(): void
    {
        SiteContent::create([
            'key' => 'footer_text',
            'value' => 'Copyright 2024',
            'type' => 'text',
            'is_active' => true
        ]);

        $content = $this->service->getSiteContent('footer_text');

        $this->assertEquals('Copyright 2024', $content);
    }

    public function test_returns_default_for_non_existent_site_content(): void
    {
        $content = $this->service->getSiteContent('non_existent', 'default_value');

        $this->assertEquals('default_value', $content);
    }

    public function test_gets_site_content_by_category(): void
    {
        SiteContent::create([
            'key' => 'footer_text',
            'value' => 'Copyright 2024',
            'type' => 'text',
            'category' => 'footer',
            'is_active' => true,
            'display_order' => 1
        ]);
        
        SiteContent::create([
            'key' => 'footer_links',
            'value' => 'Home | About | Contact',
            'type' => 'text',
            'category' => 'footer',
            'is_active' => true,
            'display_order' => 2
        ]);
        
        SiteContent::create([
            'key' => 'header_logo',
            'value' => '/logo.png',
            'type' => 'image',
            'category' => 'header',
            'is_active' => true,
            'display_order' => 1
        ]);

        $footerContent = $this->service->getSiteContentByCategory('footer');

        $this->assertCount(2, $footerContent);
        $this->assertEquals('footer_text', $footerContent[0]->key);
        $this->assertEquals('footer_links', $footerContent[1]->key);
    }

    public function test_site_content_by_category_excludes_inactive(): void
    {
        SiteContent::create([
            'key' => 'footer_text',
            'value' => 'Copyright 2024',
            'type' => 'text',
            'category' => 'footer',
            'is_active' => true,
            'display_order' => 1
        ]);
        
        SiteContent::create([
            'key' => 'footer_old',
            'value' => 'Old footer',
            'type' => 'text',
            'category' => 'footer',
            'is_active' => false,
            'display_order' => 2
        ]);

        $footerContent = $this->service->getSiteContentByCategory('footer', true);

        $this->assertCount(1, $footerContent);
        $this->assertEquals('footer_text', $footerContent[0]->key);
    }

    // ========== Cache Invalidation Tests ==========

    public function test_clear_content_cache_invalidates_all_caches(): void
    {
        // Create test data
        Setting::create(['key' => 'site_name', 'value' => 'My LMS']);
        
        Page::create([
            'slug' => 'about',
            'title' => 'About',
            'content' => 'Content',
            'status' => 'published'
        ]);
        
        HeroSection::create([
            'title' => 'Hero',
            'status' => 'active'
        ]);
        
        Slider::create([
            'title' => 'Slider',
            'order' => 1,
            'status' => 'active'
        ]);

        // Cache all content
        $this->service->getSettings();
        $this->service->getPage('about');
        $this->service->getHeroSections();
        $this->service->getSliders();

        // Update database directly
        Setting::where('key', 'site_name')->update(['value' => 'Updated LMS']);
        Page::where('slug', 'about')->update(['title' => 'Updated About']);
        HeroSection::where('title', 'Hero')->update(['title' => 'Updated Hero']);
        Slider::where('title', 'Slider')->update(['title' => 'Updated Slider']);

        // Clear cache
        $this->service->clearContentCache();

        // Verify fresh data is retrieved
        $settings = $this->service->getSettings();
        $this->assertEquals('Updated LMS', $settings['site_name']);

        $page = $this->service->getPage('about');
        $this->assertEquals('Updated About', $page->title);

        $heroSections = $this->service->getHeroSections();
        $this->assertEquals('Updated Hero', $heroSections[0]->title);

        $sliders = $this->service->getSliders();
        $this->assertEquals('Updated Slider', $sliders[0]->title);
    }
}
