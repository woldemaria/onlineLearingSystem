<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Unauthenticated. Valid Bearer token required.',
            ], 401);
        }

        $session = UserSession::with('user')
            ->where('token', $token)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$session || !$session->user || !$session->user->is_active) {
            return response()->json([
                'message' => 'Unauthenticated. Session is expired or invalid.',
            ], 401);
        }

        // Update session activity
        $session->update([
            'last_activity_at' => now(),
        ]);

        // Attach authenticated user to request and Auth facade
        $user = $session->user;
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        Auth::setUser($user);

        return $next($request);
    }
}
