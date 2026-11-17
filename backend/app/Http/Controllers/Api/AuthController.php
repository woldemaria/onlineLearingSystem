<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserSession;
use App\Models\Instructor;
use App\Models\Student;

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

        $requestedRole = $validated['role'] ?? 'student';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $requestedRole,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        UserProfile::create([
            'profileable_type' => User::class,
            'profileable_id' => $user->id,
            'legal_name' => $user->name,
            'preferred_name' => explode(' ', $user->name)[0],
            'bio' => $requestedRole === 'instructor'
                ? 'Certified Instructor on GELME Super Platform.'
                : 'Active Global Student & Lifelong Learner.',
        ]);

        if ($requestedRole === 'instructor') {
            Instructor::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'is_verified' => true,
                'is_active' => true,
                'designation' => 'Lead Instructor',
                'rating' => 5.0,
                'bio' => 'Certified Master Educator on GELME.',
            ]);
        } else {
            Student::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'institution_id' => 1,
                'student_number' => 'STU-' . strtoupper(Str::random(8)),
                'admission_year' => (int) date('Y'),
                'expected_graduation_year' => (int) date('Y') + 4,
                'academic_status' => 'active',
                'enrollment_status' => 'active',
                'is_active' => true,
            ]);
        }

        // Issue authentic UserSession token
        $token = 'gelme_sec_' . bin2hex(random_bytes(32));
        UserSession::create([
            'user_id' => $user->id,
            'token' => $token,
            'device_name' => $request->header('User-Agent', 'Web Browser'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_current' => true,
            'last_activity_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $role = $user->role;

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff',
            ],
            'token' => $token,
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
                'message' => 'Invalid email or password credentials provided.',
            ], 422);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account is deactivated. Please contact platform support.',
            ], 403);
        }

        $profile = UserProfile::where('profileable_type', User::class)
            ->where('profileable_id', $user->id)
            ->first();

        // Invalidate old current sessions for this device and create new session
        UserSession::where('user_id', $user->id)->update(['is_current' => false]);

        $token = 'gelme_sec_' . bin2hex(random_bytes(32));
        UserSession::create([
            'user_id' => $user->id,
            'token' => $token,
            'device_name' => $request->header('User-Agent', 'Web Browser'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_current' => true,
            'last_activity_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'bio' => $profile?->bio ?? '',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff',
            ],
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $profile = UserProfile::where('profileable_type', User::class)
            ->where('profileable_id', $user->id)
            ->first();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'bio' => $profile?->bio ?? '',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff',
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            UserSession::where('token', $token)->update([
                'revoked_at' => now(),
                'is_current' => false,
            ]);
        }

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }
}
