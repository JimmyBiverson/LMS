@props([
    'name' => 'Instructor',
    'designation' => 'Designation',
    'image' => null,
    'url' => '#',
])

<a href="{{ $url }}" class="block group">
    <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 text-center">
        <div class="aspect-square bg-primary-50 flex items-center justify-center overflow-hidden">
            @if($image)
                <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" loading="lazy" class="w-full h-full object-cover">
            @else
                <div class="w-24 h-24 rounded-full bg-white/80 flex items-center justify-center">
                    <i class="ri-user-smile-line text-5xl text-primary/40"></i>
                </div>
            @endif
        </div>
        <div class="p-6">
            <h4 class="font-bold text-heading mb-1 group-hover:text-primary transition-colors duration-300">{{ $name }}</h4>
            <p class="text-sm text-heading/60">{{ $designation }}</p>
        </div>
    </div>
</a>
