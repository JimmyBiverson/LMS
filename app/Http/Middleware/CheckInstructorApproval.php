<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstructorApproval
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'instructor' && !$user->is_approved) {
            return redirect()->route('instructor.pending-approval');
        }

        return $next($request);
    }
}
