@props([
    'name' => 'Category',
    'courseCount' => '0',
    'url' => '#',
    'icon' => 'ri-bookmark-line',
])

<a href="{{ $url }}" class="block group">
    <div class="bg-white rounded-[20px] p-8 text-center shadow-sm hover:shadow-lg transition-all duration-300 border border-transparent hover:border-primary-100">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-primary-50 flex items-center justify-center group-hover:bg-primary transition-colors duration-300">
            <i class="{{ $icon }} text-2xl text-primary group-hover:text-white transition-colors duration-300"></i>
        </div>
        <h4 class="font-bold text-heading mb-1">{{ $name }}</h4>
        <p class="text-sm text-heading/60">{{ $courseCount }}+ Course Available</p>
    </div>
</a>