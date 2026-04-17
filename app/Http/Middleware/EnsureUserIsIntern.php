<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsIntern
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if logged-in user is an InternProfile instance
        if ($user instanceof \App\Models\InternProfile) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
