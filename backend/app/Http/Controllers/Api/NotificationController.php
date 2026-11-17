<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = $user ? $user->id : 4; // Default to student if not logged in

        // If no notifications exist for this user, seed sensible LMS defaults
        if (Notification::where('user_id', $userId)->count() === 0) {
            $this->seedInitialNotifications($userId);
        }

        $notifications = Notification::where('user_id', $userId)
            ->where('is_ignored', false)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        $unreadCount = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->where('is_ignored', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'data' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $userId = $user ? $user->id : 4;

        $notification = Notification::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
        }

        $unreadCount = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $userId = $user ? $user->id : 4;

        Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $userId = $user ? $user->id : 4;

        $notification = Notification::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->delete();
        }

        return response()->json([
            'success' => true,
        ]);
    }

    private function seedInitialNotifications($userId)
    {
        $seeds = [
            [
                'user_id' => $userId,
                'type' => 'achievement',
                'channel' => 'in_app',
                'title' => '🏆 Verifiable Credential Issued!',
                'message' => 'Congratulations! Your certificate for "Python for AI & Data Science" has been cryptographically signed and registered on the ledger.',
                'action_url' => '/verify/GELME-CERT-2026-X892K',
                'action_label' => 'View Credential',
                'priority' => 1,
                'status' => true,
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $userId,
                'type' => 'classroom',
                'channel' => 'in_app',
                'title' => '🌐 Upcoming Live Immersion Session',
                'message' => 'Your 25-minute 1-on-1 Spanish immersion with Carlos Méndez is scheduled for tomorrow at 10:00 AM UTC.',
                'action_url' => '/classroom/spanish-immersion',
                'action_label' => 'Join Classroom Hub',
                'priority' => 2,
                'status' => true,
                'created_at' => now()->subHours(6),
            ],
            [
                'user_id' => $userId,
                'type' => 'qa',
                'channel' => 'in_app',
                'title' => '💬 Instructor Answered Your Question',
                'message' => 'Dr. Chen replied to your discussion thread: "Handling state hydration across Pinia SSR stores".',
                'action_url' => '/learn/full-stack-web-development-masterclass',
                'action_label' => 'Open Discussion',
                'priority' => 3,
                'status' => true,
                'created_at' => now()->subDay(),
            ],
            [
                'user_id' => $userId,
                'type' => 'announcement',
                'channel' => 'in_app',
                'title' => '📢 New Deep Learning Module Released',
                'message' => 'Prof. Andrew Ng has just uploaded 4 new hands-on lectures and WebGL visualizations on Attention Mechanisms.',
                'action_url' => '/courses/generative-ai-and-large-language-models',
                'action_label' => 'Explore Module',
                'priority' => 4,
                'status' => true,
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($seeds as $seed) {
            Notification::create($seed);
        }
    }
}
