<form method="POST" action="/admin/settings/school" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><i class="ri-information-line text-primary"></i> School Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">School Name</label>
                        <input name="school_name" value="{{ old('school_name', $school->school_name ?? config('app.name')) }}" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">School Email</label>
                        <input name="school_email" type="email" value="{{ old('school_email', $school->school_email ?? '') }}" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">School Phone</label>
                        <input name="school_phone" value="{{ old('school_phone', $school->school_phone ?? '') }}" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Default Language</label>
                        <select name="language" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                            <option value="en" @selected(($school->language ?? 'en') === 'en')>English</option>
                            <option value="ar" @selected(($school->language ?? 'en') === 'ar')>Arabic</option>
                            <option value="es" @selected(($school->language ?? 'en') === 'es')>Spanish</option>
                            <option value="bn" @selected(($school->language ?? 'en') === 'bn')>Bengali</option>
                            @foreach($languages as $lang)
                            <option value="{{ $lang->code }}" @selected(($school->language ?? 'en') === $lang->code)>{{ $lang->name }}</option>
                            @endforeach
                        </select></div>
                    <div class="md:col-span-2"><label class="block text-sm font-semibold text-heading mb-1">School Address</label>
                        <textarea name="school_address" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">{{ old('school_address', $school->school_address ?? '') }}</textarea></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><i class="ri-money-dollar-circle-line text-primary"></i> Currency & Localization</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">Currency Symbol</label>
                        <input name="currency_symbol" value="{{ old('currency_symbol', $school->currency_symbol ?? '$') }}" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Currency Code</label>
                        <input name="currency_code" value="{{ old('currency_code', $school->currency_code ?? 'USD') }}" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Currency Position</label>
                        <select name="currency_position" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                            <option value="left" @selected(($school->currency_position ?? 'left') === 'left')>Left ($100)</option>
                            <option value="right" @selected(($school->currency_position ?? 'left') === 'right')>Right (100$)</option>
                        </select></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Timezone</label>
                        <select name="timezone" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm focus:outline-none focus:border-primary">
                            @foreach($timezones as $tz)
                            <option value="{{ $tz->name }}" @selected(($school->timezone ?? 'UTC') === $tz->name)>{{ $tz->name }}</option>
                            @endforeach
                            <option value="UTC" @selected(($school->timezone ?? 'UTC') === 'UTC')>UTC</option>
                            <option value="America/New_York" @selected(($school->timezone ?? 'UTC') === 'America/New_York')>America/New_York</option>
                            <option value="Europe/London" @selected(($school->timezone ?? 'UTC') === 'Europe/London')>Europe/London</option>
                            <option value="Africa/Kampala" @selected(($school->timezone ?? 'UTC') === 'Africa/Kampala')>Africa/Kampala</option>
                            <option value="Africa/Nairobi" @selected(($school->timezone ?? 'UTC') === 'Africa/Nairobi')>Africa/Nairobi</option>
                            <option value="Africa/Lagos" @selected(($school->timezone ?? 'UTC') === 'Africa/Lagos')>Africa/Lagos</option>
                            <option value="Asia/Dubai" @selected(($school->timezone ?? 'UTC') === 'Asia/Dubai')>Asia/Dubai</option>
                            <option value="Asia/Kolkata" @selected(($school->timezone ?? 'UTC') === 'Asia/Kolkata')>Asia/Kolkata</option>
                        </select></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><i class="ri-palette-line text-primary"></i> Theme & Appearance</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="block text-sm font-semibold text-heading mb-1">Primary Color</label>
                        <input name="primary_color" type="color" value="{{ old('primary_color', $school->primary_color ?? '#5F3EED') }}" class="w-full h-12 rounded-lg border border-heading/10 cursor-pointer"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Secondary Color</label>
                        <input name="secondary_color" type="color" value="{{ old('secondary_color', $school->secondary_color ?? '#F4B826') }}" class="w-full h-12 rounded-lg border border-heading/10 cursor-pointer"></div>
                    <div><label class="block text-sm font-semibold text-heading mb-1">Accent Color</label>
                        <input name="accent_color" type="color" value="{{ old('accent_color', $school->accent_color ?? '#1AEBC5') }}" class="w-full h-12 rounded-lg border border-heading/10 cursor-pointer"></div>
                </div>
                <div class="mt-4"><label class="block text-sm font-semibold text-heading mb-1">Custom CSS</label>
                    <textarea name="custom_css" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-heading/10 text-sm font-mono focus:outline-none focus:border-primary">{{ old('custom_css', $school->custom_css ?? '') }}</textarea>
                    <p class="text-xs text-heading/50 mt-1">Add custom CSS to override theme styles (advanced).</p></div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><i class="ri-image-line text-primary"></i> Branding</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-2">Site Logo</label>
                        @if($school->site_logo)
                        <div class="mb-2 p-3 bg-gray-50 rounded-lg flex items-center justify-center">
                            <img src="{{ asset('storage/'.$school->site_logo) }}" alt="Logo" class="max-h-16">
                        </div>
                        @endif
                        <input type="file" name="site_logo" accept="image/*" class="w-full px-4 py-2.5 border border-heading/10 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-heading mb-2">Favicon</label>
                        @if($school->favicon)
                        <div class="mb-2 p-3 bg-gray-50 rounded-lg flex items-center justify-center">
                            <img src="{{ asset('storage/'.$school->favicon) }}" alt="Favicon" class="h-10">
                        </div>
                        @endif
                        <input type="file" name="favicon" accept=".ico,.png,.svg" class="w-full px-4 py-2.5 border border-heading/10 rounded-lg text-sm">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><i class="ri-video-line text-primary"></i> Slider Video</h3>
                <div>
                    <label class="block text-sm font-semibold text-heading mb-2">Homepage Hero Video</label>
                    @if($school->slider_video)
                    <div class="mb-2">
                        <video class="w-full rounded-lg" controls>
                            <source src="{{ asset('storage/'.$school->slider_video) }}" type="video/mp4">
                        </video>
                    </div>
                    @endif
                    <input type="file" name="slider_video" accept="video/mp4,video/webm,video/ogg" class="w-full px-4 py-2.5 border border-heading/10 rounded-lg text-sm">
                    <p class="text-xs text-heading/50 mt-1">This video plays when users click the play button on the homepage slider. Max 100MB.</p>
                </div>
            </div>

            <button type="submit" class="w-full px-8 py-3 bg-primary text-white font-bold rounded-full hover:opacity-90 transition-all">
                <i class="ri-save-line"></i> Save All Settings
            </button>
        </div>
    </div>
</form>
