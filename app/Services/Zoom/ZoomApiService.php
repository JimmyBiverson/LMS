<?php

namespace App\Services\Zoom;

use App\Exceptions\ZoomNotConfiguredException;
use App\Models\MeetProvider;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Thin, stateless client for the Zoom Server-to-Server OAuth API.
 *
 * Credentials are resolved from the "zoom" meet provider row (editable in the
 * admin panel) and fall back to env() values. The access token is cached so we
 * never hit the token endpoint more than once per ~58 minutes.
 */
class ZoomApiService
{
    protected const BASE_URL = 'https://api.zoom.us/v2';
    protected const TOKEN_URL = 'https://zoom.us/oauth/token';
    protected const CONFIG_CACHE_KEY = 'zoom.provider_config';
    protected const TOKEN_CACHE_KEY = 'zoom.access_token';

    public function isConfigured(): bool
    {
        $config = $this->config();

        return (bool) ($config['client_id'] ?? null)
            && (bool) ($config['client_secret'] ?? null)
            && (bool) ($config['account_id'] ?? null);
    }

    /**
     * Resolve credentials: stored meet provider config first, env fallback.
     */
    public function config(): array
    {
        return Cache::remember(self::CONFIG_CACHE_KEY, 600, function () {
            $provider = MeetProvider::query()->where('slug', 'zoom')->first();
            $stored = $provider?->config ?: [];

            return array_merge([
                'client_id' => config('services.zoom.client_id'),
                'client_secret' => config('services.zoom.client_secret'),
                'account_id' => config('services.zoom.account_id'),
                'host_email' => config('services.zoom.host_email'),
                'auto_recording' => (bool) config('services.zoom.auto_recording', false),
                'waiting_room' => (bool) config('services.zoom.waiting_room', true),
                'meeting_password' => config('services.zoom.meeting_password'),
            ], $stored);
        });
    }

    public static function forgetCachedConfig(): void
    {
        Cache::forget(self::CONFIG_CACHE_KEY);
        Cache::forget(self::TOKEN_CACHE_KEY);
    }

    public function hostEmail(): ?string
    {
        return $this->config()['host_email'] ?? null;
    }

    public function autoRecordingEnabled(): bool
    {
        return (bool) ($this->config()['auto_recording'] ?? false);
    }

    public function accessToken(): string
    {
        return Cache::remember(self::TOKEN_CACHE_KEY, 3500, function () {
            $config = $this->config();

            if (! $config['client_id'] || ! $config['client_secret'] || ! $config['account_id']) {
                throw new ZoomNotConfiguredException(
                    'Zoom is not configured. Add your Server-to-Server OAuth credentials in Admin > Zoom Classroom > Settings.'
                );
            }

            $response = Http::asForm()->timeout(15)->post(self::TOKEN_URL, [
                'grant_type' => 'account_credentials',
                'account_id' => $config['account_id'],
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
            ]);

            if (! $response->successful()) {
                logger()->error('Zoom token request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new ZoomNotConfiguredException(
                    'Could not authenticate with Zoom ('.$response->status().'). Check the credentials in Zoom Classroom settings.'
                );
            }

            return $response->json('access_token');
        });
    }

    public function createMeeting(array $payload): array
    {
        $userId = $payload['host_email'] ?? $this->hostEmail() ?? 'me';

        $data = $this->request('post', "/users/{$this->userId($userId)}/meetings", $payload);

        return $data;
    }

    public function updateMeeting(string $zoomMeetingId, array $payload): void
    {
        $this->request('patch', "/meetings/{$zoomMeetingId}", $payload);
    }

    public function deleteMeeting(string $zoomMeetingId): void
    {
        try {
            $this->request('delete', "/meetings/{$zoomMeetingId}");
        } catch (\Throwable $e) {
            logger()->warning("Zoom delete meeting {$zoomMeetingId} failed: ".$e->getMessage());
        }
    }

    public function getMeeting(string $zoomMeetingId): array
    {
        return $this->request('get', "/meetings/{$zoomMeetingId}");
    }

    /**
     * Participants of a past meeting. Accepts the numeric meeting id; the API
     * accepts a meeting UUID (URL-encoded) or numeric id.
     */
    public function listPastMeetingParticipants(string $meetingId): array
    {
        return $this->request('get', '/past_meetings/'.urlencode($meetingId).'/participants', [], [
            'page_size' => 300,
        ])['participants'] ?? [];
    }

    public function listMeetingRecordings(string $zoomMeetingId): array
    {
        return $this->request('get', "/meetings/{$zoomMeetingId}/recordings");
    }

    public function getAccountInfo(): array
    {
        return $this->request('get', '/users/me');
    }

    public function test(): array
    {
        $token = $this->accessToken();
        $account = $this->getAccountInfo();

        return [
            'token' => $token,
            'account' => [
                'id' => $account['id'] ?? null,
                'first_name' => $account['first_name'] ?? null,
                'last_name' => $account['last_name'] ?? null,
                'email' => $account['email'] ?? null,
                'personal_meeting_url' => $account['personal_meeting_url'] ?? null,
            ],
        ];
    }

    protected function userId(string $value): string
    {
        // A valid Zoom user id or email can be used directly.
        return $value;
    }

    /**
     * Perform an authenticated API request and surface sensible errors.
     */
    protected function request(string $method, string $path, array $payload = [], array $query = []): array
    {
        $token = $this->accessToken();

        $http = Http::withToken($token)
            ->acceptJson()
            ->timeout(20)
            ->baseUrl(self::BASE_URL);

        if (! empty($query)) {
            $http = $http->withQuery($query);
        }

        $response = $method === 'get'
            ? $http->get($path)
            : $http->{$method}($path, $payload);

        $this->assertSuccessful($response, $path);

        return $response->json() ?? [];
    }

    protected function assertSuccessful(Response $response, string $path): void
    {
        if ($response->successful()) {
            return;
        }

        logger()->error('Zoom API request failed', [
            'path' => $path,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new ZoomNotConfiguredException(
            'Zoom API error on '.$path.' ('.$response->status().'): '.mb_substr($response->json('message') ?: 'unknown error', 0, 200)
        );
    }
}
