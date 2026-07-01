<form method="POST" action="/admin/theme-setting" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-heading mb-1">Primary Color</label><input name="primary_color" type="color" class="w-full h-12 rounded-lg border border-heading/10 cursor-pointer" value="#5F3EED"></div>
                <div><label class="block text-sm font-semibold text-heading mb-1">Secondary Color</label><input name="secondary_color" type="color" class="w-full h-12 rounded-lg border border-heading/10 cursor-pointer" value="#F4B826"></div>
            </div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Site Name</label><input name="site_name" type="text" value="{{ old('site_name', config('app.name')) }}" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
            <div><label class="block text-sm font-semibold text-heading mb-1">Site Description</label><textarea name="site_description" rows="3" class="w-full px-4 py-3 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('site_description') }}</textarea></div>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">Save Settings</button>
        </form>
