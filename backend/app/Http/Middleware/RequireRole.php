<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!empty($roles) && !$user->hasRole($roles)) {
            return response()->json([
                'message' => 'Forbidden. Insufficient permissions. Required: ' . implode(' or ', $roles),
            ], 403);
        }

        return $next($request);
    }
}
