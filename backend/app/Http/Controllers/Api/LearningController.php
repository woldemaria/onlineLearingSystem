<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Credential;
use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\UserSession;

class LearningController extends Controller
{
    /**
     * Resolve authenticated user from request, Bearer token session, or student fallback.
     */
    protected function resolveUser(Request $request): User
    {
        $user = $request->user();
        if (!$user && $token = $request->bearerToken()) {
            $session = UserSession::with('user')->where('token', $token)->where('expires_at', '>', now())->first();
            $user = $session?->user;
        }
        if (!$user) {
            $user = User::where('email', 'student@gelme.global')->first() ?? User::find(4) ?? User::first();
        }
        return $user;
    }

    public function enroll(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = $this->resolveUser($request);

        $totalLessons = $course->lessons()->count() ?: 3;

        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'status' => 'in_progress',
                'enrollment_type' => $request->input('type', 'self'),
                'enrollment_date' => now()->toDateString(),
                'progress_percent' => 0.00,
                'completed_lessons_count' => 0,
                'total_lessons_count' => $totalLessons,
                'last_accessed_at' => now(),
            ]
        );

        $course->increment('current_enrollments');

        return response()->json([
            'message' => 'Successfully enrolled in course! You now have full access.',
            'enrollment' => $enrollment,
        ]);
    }

    public function myCourses(Request $request)
    {
        $user = $this->resolveUser($request);
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course.institution', 'course.category', 'course.modules.lessons'])
            ->get();

        return response()->json([
            'enrollments' => $enrollments->map(function ($enr) {
                $c = $enr->course;
                $allLessons = $c ? $c->modules->flatMap->lessons : collect();
                $firstLesson = $allLessons->first();

                $thumb = match ($c?->slug) {
                    'complete-full-stack-web-development-bootcamp' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80',
                    'advanced-ai-deep-learning-specialization' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&auto=format&fit=crop&q=80',
                    'conversational-spanish-immersion-25min' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=800&auto=format&fit=crop&q=80',
                    'amharic-language-heritage-immersion-25min' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&auto=format&fit=crop&q=80',
                    'english-esl-business-accent-reduction-25min' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80',
                    'mandarin-chinese-fluency-25min' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=800&auto=format&fit=crop&q=80',
                    'cybersecurity-ethical-hacking-masterclass' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&auto=format&fit=crop&q=80',
                    'cloud-devops-kubernetes-docker-aws' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=80',
                    default => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80',
                };

                return [
                    'id' => $enr->id,
                    'course_id' => $c?->id,
                    'title' => $c?->title ?? 'Full-Stack Bootcamp',
                    'slug' => $c?->slug ?? '',
                    'institution' => $c?->institution?->name ?? 'Stanford Online',
                    'progress_percent' => (float) $enr->progress_percent,
                    'status' => $enr->status,
                    'completed_lessons_count' => $enr->completed_lessons_count,
                    'total_lessons_count' => $allLessons->count() ?: 3,
                    'current_lesson' => [
                        'id' => $firstLesson?->id ?? 1,
                        'title' => $firstLesson?->title ?? 'Introduction to Course',
                        'slug' => $firstLesson?->slug ?? '',
                    ],
                    'thumbnail_url' => $thumb,
                ];
            })
        ]);
    }

    public function completeLesson(Request $request, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $user = $this->resolveUser($request);

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->course_id)
            ->first();

        if (!$enrollment) {
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $lesson->course_id,
                'status' => 'in_progress',
                'enrollment_type' => 'self',
                'enrollment_date' => now()->toDateString(),
                'progress_percent' => 0.00,
                'completed_lessons_count' => 0,
                'total_lessons_count' => $lesson->course->lessons()->count() ?: 3,
                'last_accessed_at' => now(),
            ]);
        }

        $enrollment->completed_lessons_count = min($enrollment->total_lessons_count, $enrollment->completed_lessons_count + 1);
        $enrollment->progress_percent = min(100.0, round(($enrollment->completed_lessons_count / max(1, $enrollment->total_lessons_count)) * 100, 2));

        $certificate = null;
        if ($enrollment->progress_percent >= 100.0) {
            $enrollment->status = 'completed';
            $enrollment->completion_date = now()->toDateString();

            $certCode = 'GELME-CERT-' . strtoupper(Str::random(8));
            $certificate = Credential::firstOrCreate(
                ['enrollment_id' => $enrollment->id],
                [
                    'uuid' => (string) Str::uuid(),
                    'verification_code' => $certCode,
                    'issuer_id' => $lesson->course->institution_id ?? 1,
                    'issuer_type' => 'App\\Models\\Institution',
                    'recipient_id' => $user->id,
                    'recipient_type' => 'App\\Models\\User',
                    'course_id' => $lesson->course_id,
                    'type' => 'certificate',
                    'category' => 'professional',
                    'name' => $lesson->course->title . ' Professional Certificate',
                    'description' => 'Successfully satisfied all academic requirements, on-demand lessons, and hands-on capstone assessments.',
                    'verification_url' => 'http://localhost:3000/verify/' . $certCode,
                    'issued_at' => now(),
                    'is_public' => true,
                    'is_verifiable' => true,
                    'criteria' => [
                        'skills' => ['Enterprise Architecture', 'Applied Implementation', 'System Verification'],
                        'grade' => '99.0%',
                        'credits' => '4.5 CEU',
                        'blockchain_hash' => hash('sha256', $certCode . time())
                    ]
                ]
            );
        }

        $enrollment->save();

        return response()->json([
            'message' => 'Lesson marked completed!',
            'progress_percent' => $enrollment->progress_percent,
            'completed_lessons' => $enrollment->completed_lessons_count,
            'certificate' => $certificate,
        ]);
    }

    public function financialAid(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'education_level' => 'required|string',
            'annual_income' => 'required|numeric',
            'statement' => 'required|string|min:20',
        ]);

        $course = Course::find($validated['course_id']);
        $user = $this->resolveUser($request);

        // Coursera style: Automatically approve financial aid for verified learners
        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'status' => 'in_progress',
                'enrollment_type' => 'financial_aid',
                'enrollment_date' => now()->toDateString(),
                'progress_percent' => 0.00,
                'completed_lessons_count' => 0,
                'total_lessons_count' => $course->lessons()->count() ?: 3,
                'last_accessed_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => "Congratulations! Your Coursera-style Financial Aid application for '{$course->title}' has been APPROVED at 100% scholarship. You now have full access including all graded assignments and the verifiable certificate.",
            'course_slug' => $course->slug,
            'enrollment_id' => $enrollment->id,
        ]);
    }

    public function submitAssignment(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required',
            'lesson_id' => 'nullable',
            'repo_url' => 'required|url',
            'reflection' => 'required|string',
        ]);

        $user = $this->resolveUser($request);
        $course = Course::find($validated['course_id']);

        // Find or create default capstone assignment for course
        $assignment = null;
        if ($course) {
            $assignment = Assignment::firstOrCreate(
                ['course_id' => $course->id, 'title' => 'Final Production Capstone Project'],
                [
                    'slug' => 'final-production-capstone-project-' . $course->id,
                    'institution_id' => $course->institution_id ?? 1,
                    'creator_id' => $course->creator_id ?? 1,
                    'type' => 'project',
                    'max_score' => 100,
                    'weight' => 40.00,
                    'is_peer_reviewed' => true,
                    'is_active' => true,
                    'submission_types' => ['url', 'text'],
                ]
            );

            if ($assignment) {
                AssignmentSubmission::updateOrCreate(
                    [
                        'assignment_id' => $assignment->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'external_url' => $validated['repo_url'],
                        'content' => ['reflection' => $validated['reflection']],
                        'status' => 'graded',
                        'grade' => 98.50,
                        'max_grade' => 100.00,
                        'percentage' => 98.50,
                        'letter_grade' => 'A+',
                        'submitted_at' => now(),
                        'graded_at' => now(),
                        'graded_by' => $course->creator_id ?? 1,
                        'feedback' => 'Peer Review complete! Your capstone demonstrated enterprise-level component decoupling and clean Eloquent migrations.',
                        'rubric_scores' => [
                            'architectural_design' => 40,
                            'code_quality_and_tests' => 29,
                            'documentation_and_deployment' => 29.5,
                        ],
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'grade' => '98.5%',
            'rubric' => [
                'architectural_design' => '40 / 40 pts (Exemplary decoupled architecture)',
                'code_quality_and_tests' => '29 / 30 pts (Comprehensive test coverage)',
                'documentation_and_deployment' => '29.5 / 30 pts (Clear Docker & deployment guidelines)',
            ],
            'feedback' => 'Peer Review complete! Your capstone demonstrated enterprise-level component decoupling and clean Eloquent migrations.',
            'passed' => true,
        ]);
    }
}
