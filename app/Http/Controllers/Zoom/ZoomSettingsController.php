<?php

namespace App\Http\Controllers\Zoom;

use App\Exceptions\ZoomNotConfiguredException;
use App\Http\Controllers\Controller;
use App\Models\MeetProvider;
use App\Services\Zoom\ZoomApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class ZoomSettingsController extends Controller
{
    public function __construct(protected ZoomApiService $api)
    {
    }

    public function edit(): View
    {
        $provider = $this->provider();

        $config = $provider?->config ?? [];

        $obfuscated = function (string $key) use ($config) {
            $value = $config[$key] ?? null;

            if (! $value) {
                return null;
            }

            if (is_array($value)) {
                return '********';
            }

            return substr($value, 0, 3).'********'.substr($value, -3);
        };

        return view('admin.zoom.settings', [
            'provider' => $provider,
            'config' => $config,
            'obfuscated' => $obfuscated,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        $validated = $request->validate([
            'client_id' => ['required', 'string', 'max:255'],
            'client_secret' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'string', 'max:255'],
            'host_email' => ['nullable', 'email', 'max:255'],
            'meeting_password' => ['nullable', 'string', 'max:32'],
            'auto_recording' => ['nullable', 'boolean'],
            'waiting_room' => ['nullable', 'boolean'],
        ]);

        $provider = $this->provider() ?? $this->createProvider();

        $config = $provider->config ?? [];

        foreach (['client_id', 'client_secret', 'account_id', 'host_email', 'meeting_password'] as $key) {
            if ($request->has($key)) {
                $config[$key] = trim((string) $request->input($key));
            }
        }

        $config['auto_recording'] = $request->boolean('auto_recording');
        $config['waiting_room'] = $request->boolean('waiting_room');

        $provider->config = $config;
        $provider->status = 'active';
        $provider->save();

        $this->api->forgetCachedConfig();

        return redirect()->route('zoom.admin.settings')
            ->with('success', 'Zoom credentials saved. Use "Test connection" to verify them.');
    }

    public function test(Request $request): JsonResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        try {
            $result = $this->api->test();

            return response()->json([
                'success' => true,
                'message' => 'Connection successful.',
                'data' => $result,
            ]);
        } catch (ZoomNotConfiguredException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            logger()->error('Zoom test connection failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Connection failed: '.$e->getMessage(),
            ], 422);
        }
    }

    protected function provider(): ?MeetProvider
    {
        return MeetProvider::where('slug', 'zoom')->first();
    }

    protected function createProvider(): MeetProvider
    {
        return MeetProvider::create([
            'name' => 'Zoom',
            'slug' => 'zoom',
            'description' => 'Zoom server-to-server OAuth integration for the classroom.',
            'config' => [],
            'status' => 'inactive',
        ]);
    }
}
