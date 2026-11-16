<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\VirtualClassroom;
use App\Models\Enrollment;

class InstructorController extends Controller
{
    public function stats(Request $request)
    {
        $totalStudents = Course::sum('current_enrollments') ?: 280000;
        $activeCourses = Course::where('status', 'active')->count();
        $upcomingLive = VirtualClassroom::count();

        return response()->json([
            'stats' => [
                'total_students' => $totalStudents,
                'active_courses' => $activeCourses,
                'total_earnings' => '$' . number_format($totalStudents * 0.42, 2),
                'upcoming_live_sessions' => $upcomingLive,
                'average_rating' => 4.94,
                'monthly_growth' => '+24.6%',
            ],
            'recent_enrollments' => [
                ['student' => 'Alex Rivera', 'course' => 'Complete Full-Stack Bootcamp 2026', 'time' => '8 mins ago', 'amount' => '$14.99'],
                ['student' => 'Selamawit Bekele', 'course' => 'Conversational Spanish 25-Min Native Immersion', 'time' => '19 mins ago', 'amount' => '$19.99'],
                ['student' => 'Liam O\'Connor', 'course' => 'Advanced AI & Deep Learning Specialization', 'time' => '42 mins ago', 'amount' => '$79.00'],
                ['student' => 'Chen Wei', 'course' => 'Cybersecurity & Ethical Hacking Masterclass', 'time' => '1 hour ago', 'amount' => '$16.99'],
                ['student' => 'Aisha Al-Hassan', 'course' => 'Amharic Language & Ethiopian Heritage Immersion', 'time' => '2 hours ago', 'amount' => '$18.50'],
            ]
        ]);
    }
}
