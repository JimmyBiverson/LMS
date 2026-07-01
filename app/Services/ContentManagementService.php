<?php

namespace App\Services;

use App\Models\HeroSection;
use App\Models\Page;
use App\Models\Setting;
use App\Models\SiteContent;
use App\Models\Slider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Content Management Service
 * 
 * Centralized service for managing all dynamic content with Redis caching.
 * Handles site settings, pages, hero sections, and sliders with automatic
 * cache invalidation on updates.
 */
class ContentManagementService
{
    /**
     * Cache TTL for different content types (in seconds)
     */
    private const CACHE_TTL_SETTINGS = 86400; // 24 hours
    private const CACHE_TTL_PAGES = 3600; // 1 hour
    private const CACHE_TTL_HERO_SECTIONS = 1800; // 30 minutes
    private const CACHE_TTL_SLIDERS = 1800; // 30 minutes
    
    /**
     * Cache key prefixes
     */
    private const CACHE_KEY_SETTINGS = 'content:settings';
    private const CACHE_KEY_PAGE = 'content:page';
    private const CACHE_KEY_HERO_SECTIONS = 'content:hero_sections';
    private const CACHE_KEY_SLIDERS = 'content:sliders';
    
    /**
     * Get site settings by keys.
     * 
     * If keys array is empty, returns all settings.
     * Results are cached for performance.
     * 
     * @param array $keys Optional array of setting keys to retrieve
     * @return Collection Collection of Setting models or key-value pairs
     */
    public function getSettings(array $keys = []): Collection
    {
        $cacheKey = self::CACHE_KEY_SETTINGS . ':' . md5(json_encode($keys));
        
        return Cache::remember($cacheKey, self::CACHE_TTL_SETTINGS, function () use ($keys) {
            if (empty($keys)) {
                // Return all settings as key-value pairs
                return Setting::all()->pluck('value', 'key');
            }
            
            // Return specific settings as key-value pairs
            return Setting::whereIn('key', $keys)->get()->pluck('value', 'key');
        });
    }
    
    /**
     * Update a single setting value.
     * 
     * Creates the setting if it doesn't exist, updates if it does.
     * Automatically invalidates relevant caches.
     * 
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return bool Success status
     */
    public function updateSetting(string $key, mixed $value): bool
    {
        try {
            Setting::setValue($key, $value);
            
            // Invalidate all settings caches
            $this->clearSettingsCache();
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to update setting', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
    
    /**
     * Get a page by its slug.
     * 
     * Results are cached for performance. Returns null if page not found.
     * 
     * @param string $slug Page slug
     * @return Page|null The page model or null
     */
    public function getPage(string $slug): ?Page
    {
        $cacheKey = self::CACHE_KEY_PAGE . ':' . $slug;
        
        return Cache::remember($cacheKey, self::CACHE_TTL_PAGES, function () use ($slug) {
            return Page::where('slug', $slug)
                ->where('status', 'published')
                ->first();
        });
    }
    
    /**
     * Get all hero sections.
     * 
     * @param bool $activeOnly If true, returns only active hero sections
     * @return Collection Collection of HeroSection models
     */
    public function getHeroSections(bool $activeOnly = true): Collection
    {
        $cacheKey = self::CACHE_KEY_HERO_SECTIONS . ':' . ($activeOnly ? 'active' : 'all');
        
        return Cache::remember($cacheKey, self::CACHE_TTL_HERO_SECTIONS, function () use ($activeOnly) {
            $query = HeroSection::query();
            
            if ($activeOnly) {
                $query->where('status', 'active');
            }
            
            return $query->orderBy('id')->get();
        });
    }
    
    /**
     * Get all sliders.
     * 
     * Results are ordered by the 'order' column and cached for performance.
     * 
     * @param bool $activeOnly If true, returns only active sliders
     * @return Collection Collection of Slider models
     */
    public function getSliders(bool $activeOnly = true): Collection
    {
        $cacheKey = self::CACHE_KEY_SLIDERS . ':' . ($activeOnly ? 'active' : 'all');
        
        return Cache::remember($cacheKey, self::CACHE_TTL_SLIDERS, function () use ($activeOnly) {
            $query = Slider::query();
            
            if ($activeOnly) {
                $query->where('status', 'active');
            }
            
            return $query->orderBy('order')->get();
        });
    }
    
    /**
     * Get site content by key.
     * 
     * This method retrieves content from the SiteContent model,
     * which is useful for more complex content types.
     * 
     * @param string $key Content key
     * @param mixed $default Default value if not found
     * @return mixed Content value
     */
    public function getSiteContent(string $key, mixed $default = null): mixed
    {
        $cacheKey = 'site_content:' . $key;
        
        return Cache::remember($cacheKey, self::CACHE_TTL_SETTINGS, function () use ($key, $default) {
            return SiteContent::getByKey($key, $default);
        });
    }
    
    /**
     * Get site content by category.
     * 
     * Retrieves all content items from a specific category,
     * ordered by display_order.
     * 
     * @param string $category Category name
     * @param bool $activeOnly If true, returns only active content
     * @return Collection Collection of SiteContent models
     */
    public function getSiteContentByCategory(string $category, bool $activeOnly = true): Collection
    {
        $cacheKey = 'site_content:category:' . $category . ':' . ($activeOnly ? 'active' : 'all');
        
        return Cache::remember($cacheKey, self::CACHE_TTL_SETTINGS, function () use ($category, $activeOnly) {
            return SiteContent::getByCategory($category, $activeOnly);
        });
    }
    
    /**
     * Clear all content-related caches.
     * 
     * This method invalidates all cached content including settings,
     * pages, hero sections, sliders, and site content.
     * 
     * @return void
     */
    public function clearContentCache(): void
    {
        $this->clearSettingsCache();
        $this->clearPagesCache();
        $this->clearHeroSectionsCache();
        $this->clearSlidersCache();
        $this->clearSiteContentCache();
    }
    
    /**
     * Clear only settings cache.
     * 
     * @return void
     */
    private function clearSettingsCache(): void
    {
        $patterns = [
            self::CACHE_KEY_SETTINGS . ':*',
        ];
        
        foreach ($patterns as $pattern) {
            $this->clearCacheByPattern($pattern);
        }
    }
    
    /**
     * Clear only pages cache.
     * 
     * @return void
     */
    private function clearPagesCache(): void
    {
        $this->clearCacheByPattern(self::CACHE_KEY_PAGE . ':*');
    }
    
    /**
     * Clear only hero sections cache.
     * 
     * @return void
     */
    private function clearHeroSectionsCache(): void
    {
        $this->clearCacheByPattern(self::CACHE_KEY_HERO_SECTIONS . ':*');
    }
    
    /**
     * Clear only sliders cache.
     * 
     * @return void
     */
    private function clearSlidersCache(): void
    {
        $this->clearCacheByPattern(self::CACHE_KEY_SLIDERS . ':*');
    }
    
    /**
     * Clear site content cache.
     * 
     * @return void
     */
    private function clearSiteContentCache(): void
    {
        $this->clearCacheByPattern('site_content:*');
    }
    
    /**
     * Clear cache keys matching a pattern.
     * 
     * This is a helper method for clearing cache keys that match
     * a wildcard pattern. Note: Pattern matching is driver-dependent.
     * 
     * @param string $pattern Cache key pattern with wildcard (*)
     * @return void
     */
    private function clearCacheByPattern(string $pattern): void
    {
        // For database/file cache drivers, we need to manually find and delete keys
        // For Redis, we could use SCAN or DEL with patterns
        // This is a simplified implementation that works across drivers
        
        try {
            $cacheStore = Cache::getStore();
            
            // Check if we're using Redis
            if (method_exists($cacheStore, 'getRedis')) {
                $redis = $cacheStore->getRedis();
                $prefix = config('cache.prefix');
                $fullPattern = $prefix . $pattern;
                
                // Use Redis SCAN to find and delete keys
                $cursor = 0;
                do {
                    $keys = $redis->scan($cursor, ['match' => $fullPattern, 'count' => 100]);
                    if ($keys !== false && count($keys[1]) > 0) {
                        foreach ($keys[1] as $key) {
                            // Remove prefix before calling forget
                            $keyWithoutPrefix = substr($key, strlen($prefix));
                            Cache::forget($keyWithoutPrefix);
                        }
                    }
                    $cursor = $keys[0] ?? 0;
                } while ($cursor !== 0);
            } else {
                // For non-Redis drivers, clear entire cache
                // This is less efficient but ensures cache invalidation works
                Cache::flush();
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to clear cache by pattern', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
            
            // Fallback: flush entire cache
            Cache::flush();
        }
    }
    
    /**
     * Update or create a page.
     * 
     * @param array $data Page data
     * @return Page Created or updated page
     */
    public function updateOrCreatePage(array $data): Page
    {
        $page = Page::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );
        
        $this->clearPagesCache();
        
        return $page;
    }
    
    /**
     * Update or create a hero section.
     * 
     * @param array $data Hero section data
     * @param int|null $id Hero section ID (null to create new)
     * @return HeroSection Created or updated hero section
     */
    public function updateOrCreateHeroSection(array $data, ?int $id = null): HeroSection
    {
        if ($id) {
            $heroSection = HeroSection::findOrFail($id);
            $heroSection->update($data);
        } else {
            $heroSection = HeroSection::create($data);
        }
        
        $this->clearHeroSectionsCache();
        
        return $heroSection;
    }
    
    /**
     * Update or create a slider.
     * 
     * @param array $data Slider data
     * @param int|null $id Slider ID (null to create new)
     * @return Slider Created or updated slider
     */
    public function updateOrCreateSlider(array $data, ?int $id = null): Slider
    {
        if ($id) {
            $slider = Slider::findOrFail($id);
            $slider->update($data);
        } else {
            $slider = Slider::create($data);
        }
        
        $this->clearSlidersCache();
        
        return $slider;
    }
    
    /**
     * Delete a page.
     * 
     * @param string $slug Page slug
     * @return bool Success status
     */
    public function deletePage(string $slug): bool
    {
        $page = Page::where('slug', $slug)->first();
        
        if ($page) {
            $page->delete();
            $this->clearPagesCache();
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete a hero section.
     * 
     * @param int $id Hero section ID
     * @return bool Success status
     */
    public function deleteHeroSection(int $id): bool
    {
        $heroSection = HeroSection::find($id);
        
        if ($heroSection) {
            $heroSection->delete();
            $this->clearHeroSectionsCache();
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete a slider.
     * 
     * @param int $id Slider ID
     * @return bool Success status
     */
    public function deleteSlider(int $id): bool
    {
        $slider = Slider::find($id);
        
        if ($slider) {
            $slider->delete();
            $this->clearSlidersCache();
            return true;
        }
        
        return false;
    }
}
