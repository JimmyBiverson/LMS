@props([
    'name' => 'Instructor',
    'designation' => 'Designation',
    'image' => null,
    'url' => '#',
])

<a href="{{ $url }}" class="block group">
    <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 text-center">
        <div class="h-48 bg-gradient-to-b from-primary-100 to-primary-50 flex items-center justify-center">
            <div class="w-24 h-24 rounded-full bg-white/80 flex items-center justify-center">
                <i class="ri-user-smile-line text-5xl text-primary/40"></i>
            </div>
        </div>
        <div class="p-6">
            <h4 class="font-bold text-heading mb-1 group-hover:text-primary transition-colors duration-300">{{ $name }}</h4>
            <p class="text-sm text-heading/60">{{ $designation }}</p>
        </div>
    </div>
</a>