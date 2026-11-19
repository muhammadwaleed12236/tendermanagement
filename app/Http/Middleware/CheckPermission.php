<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (!$user->isActive()) {
            abort(403, 'Your account has been deactivated. Please contact the administrator.');
        }

        if (!$user->hasPermission($permission)) {
            abort(403, "You do not have permission [{$permission}] to access this section.");
        }

        return $next($request);
    }
}
