@extends('layouts.dashboard')

@section('title', 'Zoom Settings')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('zoom.admin.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-heading/50 hover:text-primary mb-4">
        <i class="ri-arrow-left-s-line"></i> Back to Zoom Classroom
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-100 flex items-start gap-4">
            <span class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 inline-flex items-center justify-center shrink-0"><i class="ri-video-on-line text-xl"></i></span>
            <div>
                <h1 class="text-xl font-bold text-heading">Zoom Classroom Settings</h1>
                <p class="text-sm text-gray-500 mt-1">Paste your Zoom Server-to-Server OAuth app credentials below. They are stored encrypted on the meet provider and cached for 10 minutes.</p>
            </div>
        </div>

        @if($provider?->status === 'inactive' || ! $provider)
            <div class="px-6 py-3 bg-amber-50 border-b border-amber-100 text-sm text-amber-800">
                <i class="ri-alert-line mr-1"></i> Zoom is not active yet. Save the credentials and run "Test connection" to activate it.
            </div>
        @endif

        <form method="POST" action="{{ route('zoom.admin.settings.update') }}" class="p-6 space-y-5">
            @csrf
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label for="client_id" class="block text-sm font-semibold text-heading mb-1.5">Client ID <span class="text-red-500">*</span></label>
                    <input id="client_id" name="client_id" type="text" required value="{{ old('client_id', $obfuscated('client_id') ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" autocomplete="off" spellcheck="false">
                    @error('client_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="client_secret" class="block text-sm font-semibold text-heading mb-1.5">Client Secret <span class="text-red-500">*</span></label>
                    <input id="client_secret" name="client_secret" type="password" required value="{{ old('client_secret', $obfuscated('client_secret') ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" autocomplete="off" spellcheck="false">
                    @error('client_secret')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label for="account_id" class="block text-sm font-semibold text-heading mb-1.5">Account ID <span class="text-red-500">*</span></label>
                    <input id="account_id" name="account_id" type="text" required value="{{ old('account_id', $obfuscated('account_id') ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" autocomplete="off" spellcheck="false">
                    @error('account_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="host_email" class="block text-sm font-semibold text-heading mb-1.5">Host Email (optional)</label>
                    <input id="host_email" name="host_email" type="email" value="{{ old('host_email', $config['host_email'] ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" placeholder="The Zoom user who hosts the classes">
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                <div>
                    <label for="meeting_password" class="block text-sm font-semibold text-heading mb-1.5">Default Passcode</label>
                    <input id="meeting_password" name="meeting_password" type="text" value="{{ old('meeting_password', $config['meeting_password'] ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-primary focus:border-primary text-sm" placeholder="Optional">
                </div>
                <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50/60 cursor-pointer mt-5">
                    <input type="checkbox" name="auto_recording" value="1" @checked(old('auto_recording', $config['auto_recording'] ?? false)) class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm font-semibold text-heading">Auto recording</span>
                </label>
                <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50/60 cursor-pointer mt-5">
                    <input type="checkbox" name="waiting_room" value="1" @checked(old('waiting_room', $config['waiting_room'] ?? true)) class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm font-semibold text-heading">Waiting room</span>
                </label>
            </div>

            <p class="text-xs text-gray-400">
                <i class="ri-information-line mr-1"></i>
                Create a Server-to-Server OAuth app in the
                <a href="https://marketplace.zoom.us" target="_blank" rel="noopener" class="text-primary hover:underline">Zoom App Marketplace</a>
                and add the scopes: <code class="px-1.5 py-0.5 bg-gray-100 rounded">meeting:write:admin</code>, <code class="px-1.5 py-0.5 bg-gray-100 rounded">meeting:read:admin</code>, <code class="px-1.5 py-0.5 bg-gray-100 rounded">recording:read:admin</code>, <code class="px-1.5 py-0.5 bg-gray-100 rounded">dashboard:read:admin</code>.
            </p>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-white text-sm font-bold hover:opacity-90">
                    <i class="ri-save-line"></i> Save Credentials
                </button>
                <button type="button"
                        x-data="{ testing: false, result: null }"
                        x-on:click="
                            testing = true; result = null;
                            fetch('{{ route('zoom.admin.settings.test') }}', { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                                .then(async r => { const data = await r.json(); result = { ok: r.ok, message: data.message ?? (r.ok ? 'Connection successful.' : 'Connection failed.'), detail: data.data ? JSON.stringify(data.data) : null }; })
                                .catch(() => result = { ok: false, message: 'Could not reach the server.' })
                                .finally(() => testing = false);
                        "
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-heading/70 hover:bg-gray-50">
                    <i class="ri-plug-line" x-show="!testing"></i>
                    <i class="ri-loader-4-line animate-spin" x-show="testing" x-cloak></i>
                    <span x-text="testing ? 'Testing...' : 'Test Connection'"></span>
                </button>
            </div>

            <div x-show="result" x-cloak
                 class="rounded-lg px-4 py-3 text-sm font-semibold"
                 :class="result && result.ok ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600'">
                <span x-text="result && result.message"></span>
                <code x-show="result && result.detail" class="block mt-1 text-xs font-normal break-all" x-text="result && result.detail"></code>
            </div>
        </form>
    </div>

    @if($config['client_id'] ?? false)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-sm">
            <p class="font-bold text-heading mb-2">Current Configuration</p>
            <ul class="space-y-1 text-gray-500">
                <li><span class="font-semibold text-heading/60">Client ID:</span> {{ $obfuscated('client_id') }}</li>
                <li><span class="font-semibold text-heading/60">Account ID:</span> {{ $obfuscated('account_id') }}</li>
                <li><span class="font-semibold text-heading/60">Host Email:</span> {{ $config['host_email'] ?? '—' }}</li>
                <li><span class="font-semibold text-heading/60">Provider status:</span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold {{ $provider?->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $provider?->status === 'active' ? 'Active' : 'Inactive' }}
                    </span>
                </li>
            </ul>
        </div>
    @endif
</div>
@endsection
