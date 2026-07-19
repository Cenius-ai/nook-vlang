<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsModerator
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isModerator()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            abort(403, 'This area is restricted to moderators.');
        }
        return $next($request);
    }
}
