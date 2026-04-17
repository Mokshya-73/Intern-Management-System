<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roleId)
    {
        $user = Auth::user();

        if (!$user || !isset($user->role_id)) {
            abort(403, 'Unauthorized');
        }

        if ((string) $user->role_id !== (string) $roleId) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
