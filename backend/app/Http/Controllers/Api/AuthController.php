<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProfile;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string|in:student,instructor',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'profileable_type' => User::class,
            'profileable_id' => $user->id,
            'legal_name' => $user->name,
            'preferred_name' => explode(' ', $user->name)[0],
            'bio' => 'New learner on GELME Global Platform.',
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $validated['role'] ?? 'student',
            ],
            'token' => 'gelme_token_' . bin2hex(random_bytes(16)),
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials provided.',
            ], 422);
        }

        $profile = UserProfile::where('profileable_type', User::class)
            ->where('profileable_id', $user->id)
            ->first();

        $role = 'student';
        if (str_contains($user->email, 'admin')) {
            $role = 'admin';
        } elseif (str_contains($user->email, 'chen') || str_contains($user->email, 'mendez')) {
            $role = 'instructor';
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'bio' => $profile?->bio ?? '',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff',
            ],
            'token' => 'gelme_session_' . bin2hex(random_bytes(24)),
        ]);
    }

    public function me(Request $request)
    {
        $user = User::first(); // default demo fallback
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'student',
            ],
        ]);
    }
}
