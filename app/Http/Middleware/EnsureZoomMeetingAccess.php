<?php

namespace App\Http\Middleware;

use App\Models\ZoomMeeting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureZoomMeetingAccess
{
    /**
     * Resolve the meeting from the route and authorize the requested ability.
     * The resolved model is written back to the route so implicit binding is not
     * queried twice.
     */
    public function handle(Request $request, Closure $next, string $ability = 'view'): Response
    {
        $route = $request->route();
        $value = $route?->parameter('meeting');

        $meeting = $value instanceof ZoomMeeting
            ? $value
            : ZoomMeeting::with(['course', 'lesson', 'instructor'])->find($value);

        abort_unless($meeting, 404);

        $user = $request->user();

        abort_unless($user && $user->can($ability, $meeting), 403);

        $route?->setParameter('meeting', $meeting);

        return $next($request);
    }
}
