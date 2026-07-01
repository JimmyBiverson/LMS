@props([
    'slug' => '#',
    'category' => 'Category',
    'author' => 'Admin',
    'date' => 'Date',
    'title' => 'Blog Title',
    'image' => null,
])

<article class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
    <div class="h-52 overflow-hidden">
        @if($image)
            <img src="{{ asset('storage/' . $image) }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                <i class="ri-file-text-line text-6xl text-primary/20"></i>
            </div>
        @endif
    </div>
    <div class="p-6">
        <div class="flex items-center gap-3 text-xs text-heading/60 mb-3">
            <span class="px-3 py-1 rounded-full bg-primary-50 text-primary font-semibold">{{ $category }}</span>
            <span>{{ $author }}</span>
            <span>{{ $date }}</span>
        </div>
        <h3 class="font-bold text-heading mb-4 line-clamp-2 group-hover:text-primary transition-colors duration-300">
            <a href="/blogs/{{ $slug }}">{{ $title }}</a>
        </h3>
        <a href="/blogs/{{ $slug }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:gap-3 transition-all duration-300">
            View Detail <i class="ri-arrow-right-line"></i>
        </a>
    </div>
</article>