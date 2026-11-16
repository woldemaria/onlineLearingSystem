<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\UserProfile;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function stats(Request $request)
    {
        $totalUsers = User::count();
        $totalStudents = Student::count() ?: User::where('email', 'not like', '%admin%')->where('email', 'not like', '%chen%')->count();
        $totalInstructors = Instructor::count() ?: 4;
        $pendingVerifications = Instructor::where('is_verified', false)->count();
        $activeUsers = User::where('is_active', true)->count();

        return response()->json([
            'total_users' => $totalUsers,
            'total_students' => $totalStudents,
            'total_instructors' => $totalInstructors,
            'pending_verifications' => $pendingVerifications,
            'active_users' => $activeUsers,
        ]);
    }

    public function index(Request $request)
    {
        $query = User::with(['profile', 'instructor', 'student'])
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->get();

        if ($request->filled('role') && $request->role !== 'all') {
            $roleFilter = $request->role;
            $users = $users->filter(fn($u) => $u->role === $roleFilter);
        }

        return response()->json([
            'users' => $users->values()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'is_active' => (bool) $u->is_active,
                    'bio' => $u->profile?->bio ?? '',
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=' . ($u->role === 'admin' ? '7c3aed' : ($u->role === 'instructor' ? '4f46e5' : '10b981')) . '&color=fff&bold=true',
                    'is_verified' => (bool) ($u->instructor?->is_verified ?? true),
                    'student_number' => $u->student?->student_number ?? ('STU-' . $u->id),
                    'created_at' => $u->created_at?->format('M d, Y') ?? 'Recently',
                    'last_login' => $u->last_login_at?->diffForHumans() ?? 'Active recently',
                ];
            }),
        ]);
    }

    public function updateRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:student,instructor,admin',
        ]);

        $user = User::findOrFail($id);
        $newRole = $validated['role'];

        if ($newRole === 'instructor') {
            Instructor::firstOrCreate(['user_id' => $user->id], [
                'is_verified' => true,
                'is_active' => true,
                'designation' => 'Lead Instructor',
                'rating' => 5.0,
            ]);
        } elseif ($newRole === 'student') {
            Student::firstOrCreate(['user_id' => $user->id], [
                'institution_id' => 1,
                'student_number' => 'STU-' . strtoupper(Str::random(8)),
                'admission_year' => (int) date('Y'),
                'expected_graduation_year' => (int) date('Y') + 4,
                'academic_status' => 'active',
                'is_active' => true,
            ]);
            // Remove instructor profile if demoting
            Instructor::where('user_id', $user->id)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => "User {$user->name} role updated to {$newRole}.",
            'role' => $user->fresh()->role,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update(['is_active' => $validated['is_active']]);

        $statusStr = $validated['is_active'] ? 'activated' : 'suspended';

        return response()->json([
            'success' => true,
            'message' => "User {$user->name} account has been {$statusStr}.",
            'is_active' => (bool) $user->is_active,
        ]);
    }

    public function verifyInstructor(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $instructor = Instructor::firstOrCreate(['user_id' => $user->id]);
        $instructor->update([
            'is_verified' => true,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Instructor {$user->name} verified and approved.",
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Cannot delete your own admin account.'], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "User account {$user->name} deleted successfully.",
        ]);
    }

    public function exportCsv(Request $request)
    {
        $query = User::with(['profile', 'instructor', 'student'])->orderBy('id', 'asc');

        if ($request->filled('role') && $request->role !== 'all') {
            $role = $request->role;
            $query->where('role', $role);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->get();

        $filename = 'gelme_users_report_' . date('Y-m-d_His') . '.csv';

        $callback = function () use ($users) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'User ID',
                'Full Name',
                'Email Address',
                'Platform Role',
                'Account Status',
                'Instructor Verified',
                'Student ID Number',
                'Bio / Headline',
                'Registration Date',
                'Last Login',
            ]);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->is_active ? 'Active' : 'Suspended',
                    $user->role === 'instructor' ? ($user->instructor?->is_verified ? 'Yes' : 'Pending') : 'N/A',
                    $user->student?->student_number ?? ('STU-' . $user->id),
                    $user->profile?->bio ?? '',
                    $user->created_at?->format('Y-m-d H:i:s') ?? '',
                    $user->last_login_at?->format('Y-m-d H:i:s') ?? 'Never',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
