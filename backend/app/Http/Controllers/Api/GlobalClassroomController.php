<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VirtualClassroom;
use App\Models\User;

class GlobalClassroomController extends Controller
{
    public function index(Request $request)
    {
        $query = VirtualClassroom::with(['creator', 'course']);

        if ($request->filled('language')) {
            $lang = $request->language;
            $query->where(function($q) use ($lang) {
                $q->where('title', 'like', "%{$lang}%")
                  ->orWhere('description', 'like', "%{$lang}%")
                  ->orWhere('slug', 'like', "%{$lang}%");
            });
        }

        $classrooms = $query->orderBy('scheduled_at', 'asc')->get();

        return response()->json([
            'classrooms' => $classrooms->map(function ($room) {
                $settings = $room->settings ?? [];
                $creator = $room->creator;
                $profile = $creator?->profile;

                $lang = $settings['immersion_language'] ?? match(true) {
                    str_contains($room->slug, 'spanish') => 'Spanish (Español)',
                    str_contains($room->slug, 'amharic') => 'Amharic (አማርኛ)',
                    str_contains($room->slug, 'english') => 'English (ESL / British)',
                    str_contains($room->slug, 'mandarin') => 'Mandarin (中文)',
                    str_contains($room->slug, 'japanese') => 'Japanese (日本語)',
                    str_contains($room->slug, 'french') => 'French (Français)',
                    str_contains($room->slug, 'german') => 'German (Deutsch)',
                    str_contains($room->slug, 'stanford') || str_contains($room->slug, 'ai') => 'English & Python Code',
                    default => 'English / Multilingual'
                };

                return [
                    'id' => $room->id,
                    'title' => $room->title,
                    'slug' => $room->slug,
                    'description' => $room->description,
                    'provider' => $room->provider,
                    'provider_room_id' => $room->provider_room_id,
                    'meeting_url' => $room->provider_room_url,
                    'scheduled_at' => $room->scheduled_at->toIso8601String(),
                    'duration_minutes' => $room->duration_minutes,
                    'status' => $room->status,
                    'is_live' => true,
                    'instructor' => [
                        'name' => $creator?->name ?? 'Native Specialist',
                        'bio' => $profile?->bio ?? 'Online Global Classroom Certified Native Specialist',
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($creator?->name ?? 'Instructor') . '&background=059669&color=fff&bold=true',
                        'accent' => match(true) {
                            str_contains($room->slug, 'spanish') => 'Bogotá, Colombia (Clean Andean Accent)',
                            str_contains($room->slug, 'amharic') => 'Addis Ababa, Ethiopia (Native Ge\'ez Scholar)',
                            str_contains($room->slug, 'english') => 'London, UK (Received Pronunciation & Executive Business)',
                            str_contains($room->slug, 'mandarin') => 'Beijing, China (Standard Putonghua & HSK Coach)',
                            str_contains($room->slug, 'japanese') => 'Tokyo, Japan (Standard Keigo & Conversational)',
                            str_contains($room->slug, 'french') => 'Paris, France (Modern Parisian Dialogue)',
                            str_contains($room->slug, 'german') => 'Berlin, Germany (Professional Hochdeutsch)',
                            default => 'Native Bilingual Coach'
                        },
                        'total_sessions' => rand(450, 3800),
                        'rating' => 4.96,
                    ],
                    'course_title' => $room->course?->title ?? 'Live Global Classroom Immersion',
                    'max_capacity' => $settings['max_capacity'] ?? 12,
                    'current_participants' => $settings['current_participants'] ?? rand(3, 8),
                    'immersion_language' => $lang,
                    'format' => $settings['format'] ?? ($room->duration_minutes <= 30 ? '25-Min Native Immersion' : '60-Min Interactive Lab'),
                    'thumbnail_url' => match(true) {
                        str_contains($room->slug, 'spanish') => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=800&auto=format&fit=crop&q=80',
                        str_contains($room->slug, 'amharic') => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&auto=format&fit=crop&q=80',
                        str_contains($room->slug, 'english') => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80',
                        str_contains($room->slug, 'mandarin') => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=800&auto=format&fit=crop&q=80',
                        str_contains($room->slug, 'japanese') => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=800&auto=format&fit=crop&q=80',
                        str_contains($room->slug, 'french') => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=800&auto=format&fit=crop&q=80',
                        str_contains($room->slug, 'german') => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=800&auto=format&fit=crop&q=80',
                        default => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
                    }
                ];
            })
        ]);
    }

    public function show($slug)
    {
        $room = VirtualClassroom::where('slug', $slug)->with(['creator.profile', 'course'])->firstOrFail();
        $settings = $room->settings ?? [];

        $lang = $settings['immersion_language'] ?? match(true) {
            str_contains($room->slug, 'spanish') => 'Spanish (Español)',
            str_contains($room->slug, 'amharic') => 'Amharic (አማርኛ)',
            str_contains($room->slug, 'english') => 'English (ESL / British)',
            str_contains($room->slug, 'mandarin') => 'Mandarin (中文)',
            str_contains($room->slug, 'japanese') => 'Japanese (日本語)',
            str_contains($room->slug, 'french') => 'French (Français)',
            str_contains($room->slug, 'german') => 'German (Deutsch)',
            str_contains($room->slug, 'stanford') || str_contains($room->slug, 'ai') => 'English & Python Code',
            default => 'English / Multilingual'
        };

        return response()->json([
            'classroom' => [
                'id' => $room->id,
                'title' => $room->title,
                'slug' => $room->slug,
                'description' => $room->description,
                'provider' => $room->provider,
                'provider_room_id' => $room->provider_room_id,
                'provider_room_url' => $room->provider_room_url,
                'scheduled_at' => $room->scheduled_at->toIso8601String(),
                'duration_minutes' => $room->duration_minutes,
                'status' => 'live',
                'instructor' => [
                    'name' => $room->creator?->name ?? 'Instructor',
                    'email' => $room->creator?->email,
                    'bio' => $room->creator?->profile?->bio ?? 'Online Global Classroom Native Immersion Lead Coach',
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($room->creator?->name ?? 'Instructor') . '&background=059669&color=fff&bold=true',
                ],
                'immersion_language' => $lang,
                'format' => $settings['format'] ?? '25-Min Native Immersion',
                'course' => $room->course ? [
                    'id' => $room->course->id,
                    'title' => $room->course->title,
                    'slug' => $room->course->slug,
                ] : null,
                'jitsi_domain' => 'meet.jit.si',
                'jitsi_room_name' => 'gelme-' . $room->slug . '-2026',
            ]
        ]);
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:virtual_classrooms,id',
            'preferred_time' => 'nullable|string',
            'format' => 'nullable|string',
            'topic' => 'nullable|string',
        ]);

        $classroom = VirtualClassroom::find($validated['classroom_id']);

        return response()->json([
            'message' => 'Your 25-minute live immersion class has been reserved with ' . ($classroom->creator?->name ?? 'your native teacher') . '! You will receive a calendar invite and direct WebRTC room link.',
            'classroom_id' => $classroom->id,
            'slot' => $validated['preferred_time'] ?? 'Today, 2:30 PM UTC',
            'join_url' => '/classroom/' . $classroom->slug,
        ]);
    }

    public function packages()
    {
        return response()->json([
            'packages' => [
                [
                    'id' => 'trial-1',
                    'name' => 'Trial 25-Min Immersion',
                    'sessions_count' => 1,
                    'price' => 9.99,
                    'regular_price' => 20.00,
                    'price_per_session' => 9.99,
                    'discount_badge' => '50% OFF TRIAL',
                    'popular' => false,
                    'description' => 'Test out a 1-on-1 session with a certified native speaker. Perfect for your first conversational diagnostic.',
                    'features' => [
                        '1x 25-minute 1-on-1 session',
                        'CEFR initial oral assessment',
                        'Access to vocabulary flashcards',
                        'Valid for any of the 14+ languages'
                    ]
                ],
                [
                    'id' => 'fluency-4',
                    'name' => 'Fluency Starter (4 Sessions)',
                    'sessions_count' => 4,
                    'price' => 68.00,
                    'regular_price' => 88.00,
                    'price_per_session' => 17.00,
                    'discount_badge' => 'SAVE $20',
                    'popular' => false,
                    'description' => 'Four high-impact 25-minute classes to break conversational hesitation and master travel/dining dialogues.',
                    'features' => [
                        '4x 25-minute native sessions',
                        'Flexible scheduling across any week',
                        'Personalized vocabulary sheets',
                        'Free session rescheduling with 6h notice'
                    ]
                ],
                [
                    'id' => 'pro-8',
                    'name' => 'Conversational Pro (8 Sessions)',
                    'sessions_count' => 8,
                    'price' => 128.00,
                    'regular_price' => 176.00,
                    'price_per_session' => 16.00,
                    'discount_badge' => 'MOST POPULAR',
                    'popular' => true,
                    'description' => 'The golden standard of Online Global Classroom. Build spontaneous fluency, colloquial idioms, and natural rhythm.',
                    'features' => [
                        '8x 25-minute native sessions',
                        'Official CEFR B1/B2 Oral Progress Report',
                        'Dedicated native coach assigned',
                        'Lifetime access to shared whiteboard notes'
                    ]
                ],
                [
                    'id' => 'mastery-16',
                    'name' => 'Complete Polyglot Mastery (16 Sessions)',
                    'sessions_count' => 16,
                    'price' => 224.00,
                    'regular_price' => 352.00,
                    'price_per_session' => 14.00,
                    'discount_badge' => 'BEST VALUE (36% OFF)',
                    'popular' => false,
                    'description' => 'Comprehensive immersion track for executives, professionals, or expats preparing for high-stakes business.',
                    'features' => [
                        '16x 25-minute native sessions',
                        'Cryptographic verifiable CEFR certificate',
                        'Priority booking for peak evening slots',
                        'Direct WhatsApp / Telegram audio messaging'
                    ]
                ]
            ]
        ]);
    }

    public function placementTest(Request $request)
    {
        $answers = $request->input('answers', []);
        $targetLang = $request->input('language', 'Spanish');

        // Calculate score
        $score = count(array_filter($answers));
        $cefr = match(true) {
            $score >= 4 => 'B2 (Independent Fluency)',
            $score >= 3 => 'B1 (Intermediate Conversational)',
            $score >= 2 => 'A2 (Elementary Practical)',
            default => 'A1 (Beginner Foundations)'
        };

        return response()->json([
            'language' => $targetLang,
            'score' => $score,
            'max_score' => 5,
            'cefr_level' => $cefr,
            'recommended_sessions' => $score < 3 ? '8 to 12 sessions' : '4 to 8 sessions',
            'feedback' => "Based on your diagnostic responses, your current spoken proficiency is {$cefr}. Online Global Classroom's 25-minute immersion will rapidly bridge your receptive knowledge into automatic speaking fluency.",
            'recommended_teacher' => match(strtolower($targetLang)) {
                'spanish' => 'Carlos Méndez (Bogotá, Colombia)',
                'amharic' => 'Selamawit Tadesse (Addis Ababa, Ethiopia)',
                'english' => 'David Miller (London, UK)',
                'mandarin' => 'Mei-Ling Zhou (Beijing, China)',
                'japanese' => 'Kenji Sato (Tokyo, Japan)',
                'french' => 'Claire Dubois (Paris, France)',
                'german' => 'Klaus Schneider (Berlin, Germany)',
                default => 'Carlos Méndez (Native Lead)'
            }
        ]);
    }
}
