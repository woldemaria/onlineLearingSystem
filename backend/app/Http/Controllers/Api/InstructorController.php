<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\User;
use App\Models\VirtualClassroom;
use App\Models\Instructor;

class InstructorController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();
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

    public function courses(Request $request)
    {
        $courses = Course::with(['category', 'institution', 'modules.lessons'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'courses' => $courses->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'slug' => $c->slug,
                    'type' => $c->type,
                    'price' => (float) $c->price,
                    'enrollments_count' => $c->current_enrollments,
                    'modules_count' => $c->modules->count(),
                    'lessons_count' => $c->modules->sum(fn($m) => $m->lessons->count()),
                    'status' => $c->status,
                    'thumbnail_url' => $c->thumbnail_path ?: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80',
                    'created_at' => $c->created_at?->diffForHumans() ?? 'recently',
                ];
            })
        ]);
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:512000', // up to 500MB
            'type' => 'nullable|string|in:video,document,image,auto',
        ]);

        $file = $request->file('file');
        $mime = $file->getMimeType();
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $size = $file->getSize();

        // Determine media type
        $type = $request->type ?? 'auto';
        if ($type === 'auto') {
            if (str_starts_with($mime, 'video/') || in_array($extension, ['mp4', 'webm', 'mov', 'm4v', 'mkv'])) {
                $type = 'video';
            } elseif ($mime === 'application/pdf' || in_array($extension, ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt'])) {
                $type = 'document';
            } else {
                $type = 'image';
            }
        }

        $folder = match ($type) {
            'video' => 'courses/videos',
            'document' => 'courses/materials',
            default => 'courses/thumbnails',
        };

        // Store file in public disk
        $path = $file->store($folder, 'public');
        $publicUrl = '/storage/' . $path;

        $userId = $request->user()?->id ?? 1;

        $media = Media::create([
            'user_id' => $userId,
            'type' => $type,
            'disk' => 'public',
            'file_path' => $path,
            'file_name' => $originalName,
            'mime_type' => $mime,
            'size' => $size,
            'title' => pathinfo($originalName, PATHINFO_FILENAME),
            'is_public' => true,
            'visibility' => 'public',
            'cdn_url' => $publicUrl,
            'storage_provider' => 'local',
            'processing_status' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' uploaded successfully',
            'media_id' => $media->id,
            'type' => $type,
            'file_name' => $originalName,
            'mime_type' => $mime,
            'size' => $size,
            'size_formatted' => round($size / (1024 * 1024), 2) . ' MB',
            'url' => $publicUrl,
        ], 201);
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:self_paced,cohort_based,live_virtual',
            'level' => 'nullable|string|in:beginner,intermediate,advanced,all_levels',
            'price' => 'required|numeric|min:0',
            'category_slug' => 'nullable|string',
            'video_url' => 'nullable|string',
            'pdf_url' => 'nullable|string',
            'pdf_name' => 'nullable|string',
            'thumbnail_url' => 'nullable|string',
            'duration_hours' => 'nullable|integer',
            'modules' => 'nullable|array',
        ]);

        $user = $request->user();
        $slug = Str::slug($validated['title']) . '-' . rand(100, 999);

        $category = null;
        if (!empty($validated['category_slug'])) {
            $category = CourseCategory::where('slug', $validated['category_slug'])->first();
        }
        if (!$category) {
            $category = CourseCategory::first();
        }

        $course = Course::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'subtitle' => $validated['subtitle'] ?? 'Comprehensive professional mastery course.',
            'description' => $validated['description'] ?? 'Learn core practical skills and get hands-on experience through project-based learning.',
            'type' => $validated['type'],
            'level' => $validated['level'] ?? 'all_levels',
            'status' => 'active',
            'visibility' => 'public',
            'is_published' => true,
            'is_listed' => true,
            'is_certified' => true,
            'price' => $validated['price'],
            'currency' => 'USD',
            'is_free' => ((float) $validated['price'] <= 0),
            'institution_id' => 1,
            'category_id' => $category?->id,
            'creator_id' => $user?->id ?? 1,
            'duration_hours' => $validated['duration_hours'] ?? 8,
            'current_enrollments' => 1,
            'thumbnail_path' => $validated['thumbnail_url'] ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80',
            'preview_video_path' => $validated['video_url'] ?? null,
        ]);

        $hasCustomModules = !empty($validated['modules']) && count($validated['modules']) > 0;
        $firstLessonId = null;

        if ($hasCustomModules) {
            foreach ($validated['modules'] as $mIndex => $m) {
                $moduleTitle = !empty($m['title']) ? $m['title'] : ('Module ' . ($mIndex + 1));
                $courseModule = CourseModule::create([
                    'course_id' => $course->id,
                    'title' => $moduleTitle,
                    'slug' => Str::slug($moduleTitle) . '-' . ($mIndex + 1),
                    'order' => $mIndex + 1,
                    'is_published' => true,
                ]);

                $lessons = $m['lessons'] ?? [];
                foreach ($lessons as $lIndex => $l) {
                    $lessonTitle = !empty($l['title']) ? $l['title'] : ("Lesson " . ($mIndex + 1) . "." . ($lIndex + 1));
                    $videoUrl = $l['video_url'] ?? $validated['video_url'] ?? null;
                    $pdfUrl = $l['pdf_url'] ?? $validated['pdf_url'] ?? null;
                    $pdfName = $l['pdf_name'] ?? $validated['pdf_name'] ?? 'Resource Attachment.pdf';

                    $lesson = Lesson::create([
                        'course_id' => $course->id,
                        'module_id' => $courseModule->id,
                        'title' => $lessonTitle,
                        'slug' => Str::slug($lessonTitle) . '-' . ($lIndex + 1) . '-' . rand(10, 99),
                        'type' => !empty($videoUrl) ? 'video' : 'document',
                        'external_url' => $videoUrl ?: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                        'duration_minutes' => !empty($l['duration_minutes']) ? (int) $l['duration_minutes'] : 20,
                        'order' => $lIndex + 1,
                        'is_published' => true,
                        'is_free_preview' => ($mIndex === 0 && $lIndex === 0),
                        'content' => [
                            'pdf_url' => $pdfUrl,
                            'pdf_name' => $pdfName,
                            'description' => $l['description'] ?? 'Comprehensive lesson lecture, hands-on instructions, and downloadable study materials.',
                        ],
                    ]);

                    if (!$firstLessonId) {
                        $firstLessonId = $lesson->id;
                    }
                }
            }
        } else {
            // Automatically create Module 1 and initial Lesson
            $module = CourseModule::create([
                'course_id' => $course->id,
                'title' => 'Module 1: Foundations & Core Concepts',
                'slug' => 'module-1-foundations-' . rand(100, 999),
                'order' => 1,
                'is_published' => true,
            ]);

            $lesson = Lesson::create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'title' => '1.1 Comprehensive Overview & Guided Project',
                'slug' => 'overview-guided-project-' . rand(100, 999),
                'type' => !empty($validated['video_url']) ? 'video' : 'document',
                'external_url' => $validated['video_url'] ?? 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'duration_minutes' => 25,
                'order' => 1,
                'is_published' => true,
                'is_free_preview' => true,
                'content' => [
                    'pdf_url' => $validated['pdf_url'] ?? null,
                    'pdf_name' => $validated['pdf_name'] ?? 'Course Resource Guide.pdf',
                    'description' => 'Core lecture materials, guided walkthrough, and downloadable resource slides.',
                ],
            ]);
            $firstLessonId = $lesson->id;
        }

        return response()->json([
            'success' => true,
            'message' => 'Course created and published successfully!',
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'type' => $course->type,
                'price' => (float) $course->price,
                'thumbnail_url' => $course->thumbnail_path,
                'lesson_id' => $firstLessonId,
            ],
        ], 201);
    }

    public function saveCurriculum(Request $request, $id)
    {
        $validated = $request->validate([
            'modules' => 'required|array',
            'modules.*.title' => 'required|string',
            'modules.*.lessons' => 'nullable|array',
        ]);

        $course = Course::findOrFail($id);

        // Delete existing modules and lessons for fresh sync
        $existingModuleIds = CourseModule::where('course_id', $course->id)->pluck('id');
        Lesson::whereIn('module_id', $existingModuleIds)->delete();
        CourseModule::where('course_id', $course->id)->delete();

        $firstLessonId = null;
        foreach ($validated['modules'] as $mIndex => $m) {
            $courseModule = CourseModule::create([
                'course_id' => $course->id,
                'title' => $m['title'],
                'slug' => Str::slug($m['title']) . '-' . ($mIndex + 1),
                'order' => $mIndex + 1,
                'is_published' => true,
            ]);

            $lessons = $m['lessons'] ?? [];
            foreach ($lessons as $lIndex => $l) {
                $lessonTitle = !empty($l['title']) ? $l['title'] : ("Lesson " . ($mIndex + 1) . "." . ($lIndex + 1));
                $videoUrl = $l['video_url'] ?? null;
                $pdfUrl = $l['pdf_url'] ?? null;
                $pdfName = $l['pdf_name'] ?? 'Lecture Material.pdf';

                $lesson = Lesson::create([
                    'course_id' => $course->id,
                    'module_id' => $courseModule->id,
                    'title' => $lessonTitle,
                    'slug' => Str::slug($lessonTitle) . '-' . ($lIndex + 1) . '-' . rand(10, 99),
                    'type' => !empty($videoUrl) ? 'video' : 'document',
                    'external_url' => $videoUrl ?: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                    'duration_minutes' => !empty($l['duration_minutes']) ? (int) $l['duration_minutes'] : 20,
                    'order' => $lIndex + 1,
                    'is_published' => true,
                    'is_free_preview' => ($mIndex === 0 && $lIndex === 0),
                    'content' => [
                        'pdf_url' => $pdfUrl,
                        'pdf_name' => $pdfName,
                        'description' => $l['description'] ?? 'Comprehensive lesson lecture, hands-on instructions, and downloadable study materials.',
                    ],
                ]);

                if (!$firstLessonId) {
                    $firstLessonId = $lesson->id;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Course curriculum updated successfully!',
            'first_lesson_id' => $firstLessonId,
        ]);
    }

    public function activateInstructor(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $instructor = Instructor::firstOrCreate([
            'user_id' => $user->id,
        ], [
            'is_verified' => true,
            'is_active' => true,
            'designation' => 'Lead Instructor & Course Creator',
            'rating' => 5.0,
            'bio' => 'Certified Master Educator on GELME Super Platform.',
            'verification_status' => 'approved',
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Instructor account activated successfully for {$user->name}.",
            'role' => $user->fresh()->role,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->fresh()->role,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff',
            ]
        ]);
    }
}
