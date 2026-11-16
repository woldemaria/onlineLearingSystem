<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Institution;

class CourseCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()->where('status', 'active');

        if ($request->filled('category')) {
            $category = CourseCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('level', $request->level);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('subtitle', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'newest' => $query->orderBy('id', 'desc'),
                'duration' => $query->orderBy('duration_hours', 'desc'),
                default => $query->orderBy('current_enrollments', 'desc'),
            };
        } else {
            $query->orderBy('current_enrollments', 'desc');
        }

        $courses = $query->with(['category', 'institution', 'creator'])->get();

        return response()->json([
            'data' => $courses->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'slug' => $c->slug,
                    'subtitle' => $c->subtitle,
                    'description' => $c->description,
                    'category' => $c->category?->name ?? 'General',
                    'category_slug' => $c->category?->slug ?? '',
                    'institution' => $c->institution?->name ?? 'GELME Global',
                    'institution_slug' => $c->institution?->slug ?? '',
                    'instructor_name' => $c->creator?->name ?? 'Master Instructor',
                    'instructor_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($c->creator?->name ?? 'Instructor') . '&background=4f46e5&color=fff&bold=true',
                    'price' => (float) $c->price,
                    'currency' => $c->currency,
                    'is_free' => (bool) $c->is_free,
                    'level' => $c->level,
                    'type' => $c->type,
                    'language' => $c->language,
                    'duration_hours' => $c->duration_hours,
                    'enrollments_count' => $c->current_enrollments,
                    'rating' => 4.9,
                    'reviews_count' => rand(350, 4200),
                    'thumbnail_url' => match ($c->slug) {
                        'complete-full-stack-web-development-bootcamp' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80',
                        'advanced-ai-deep-learning-specialization' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&auto=format&fit=crop&q=80',
                        'conversational-spanish-immersion-25min' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=800&auto=format&fit=crop&q=80',
                        'amharic-language-heritage-immersion-25min' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&auto=format&fit=crop&q=80',
                        'english-esl-business-accent-reduction-25min' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80',
                        'mandarin-chinese-fluency-25min' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=800&auto=format&fit=crop&q=80',
                        'cybersecurity-ethical-hacking-masterclass' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&auto=format&fit=crop&q=80',
                        'cloud-devops-kubernetes-docker-aws' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=80',
                        default => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80',
                    },
                    'features' => [
                        'Verifiable Digital Certificate',
                        'On-demand video lectures',
                        'Interactive quizzes & coding labs',
                        'Access on mobile, tablet & desktop'
                    ]
                ];
            }),
            'total' => $courses->count(),
        ]);
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['category', 'institution', 'creator.profile', 'modules.lessons'])
            ->firstOrFail();

        $ratingBreakdown = [
            '5_star' => 84,
            '4_star' => 12,
            '3_star' => 3,
            '2_star' => 1,
            '1_star' => 0,
        ];

        return response()->json([
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'subtitle' => $course->subtitle,
                'description' => $course->description,
                'category' => $course->category?->name ?? 'General',
                'category_slug' => $course->category?->slug ?? '',
                'institution' => [
                    'name' => $course->institution?->name ?? 'GELME Global Consortium',
                    'website' => $course->institution?->website ?? 'https://gelme.global',
                    'type' => $course->institution?->type ?? 'university',
                ],
                'instructor' => [
                    'name' => $course->creator?->name ?? 'Master Instructor',
                    'email' => $course->creator?->email,
                    'bio' => $course->creator?->profile?->bio ?? 'Senior Faculty & Accredited Global Instructor.',
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($course->creator?->name ?? 'Instructor') . '&background=4f46e5&color=fff&bold=true',
                    'students_count' => $course->creator ? Course::where('creator_id', $course->creator_id)->sum('current_enrollments') : 120000,
                    'courses_count' => $course->creator ? Course::where('creator_id', $course->creator_id)->count() : 3,
                    'rating' => 4.9,
                ],
                'price' => (float) $course->price,
                'original_price' => (float) round($course->price * 2.8, 2),
                'discount_percent' => 65,
                'currency' => $course->currency,
                'is_free' => (bool) $course->is_free,
                'level' => $course->level,
                'type' => $course->type,
                'language' => $course->language,
                'duration_hours' => $course->duration_hours,
                'enrollments_count' => $course->current_enrollments,
                'rating' => 4.9,
                'rating_count' => rand(1800, 14200),
                'rating_breakdown' => $ratingBreakdown,
                'objectives' => $course->objectives ?? [
                    'Gain complete practical mastery of concepts through real-world scenarios',
                    'Build enterprise-ready capstones verified by industry standards',
                    'Earn verifiable academic or professional credentials with SHA-256 proof'
                ],
                'requirements' => [
                    'Basic computer literacy and a modern web browser',
                    'No prior specialized prerequisites needed for beginner modules',
                    'High motivation to achieve fluency and master enterprise skills'
                ],
                'target_audience' => [
                    'Software engineers looking to master production full-stack architectures',
                    'Polyglots seeking live conversational immersion with certified native tutors',
                    'University students aiming for accredited MicroMasters & credentials'
                ],
                'thumbnail_url' => match ($course->slug) {
                    'complete-full-stack-web-development-bootcamp' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80',
                    'advanced-ai-deep-learning-specialization' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&auto=format&fit=crop&q=80',
                    'conversational-spanish-immersion-25min' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=800&auto=format&fit=crop&q=80',
                    'amharic-language-heritage-immersion-25min' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&auto=format&fit=crop&q=80',
                    'english-esl-business-accent-reduction-25min' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80',
                    'mandarin-chinese-fluency-25min' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?w=800&auto=format&fit=crop&q=80',
                    'cybersecurity-ethical-hacking-masterclass' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&auto=format&fit=crop&q=80',
                    'cloud-devops-kubernetes-docker-aws' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?w=800&auto=format&fit=crop&q=80',
                    default => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop&q=80',
                },
                'modules' => $course->modules->map(function ($mod) {
                    return [
                        'id' => $mod->id,
                        'title' => $mod->title,
                        'slug' => $mod->slug,
                        'description' => $mod->description,
                        'lessons' => $mod->lessons->map(function ($les) {
                            return [
                                'id' => $les->id,
                                'title' => $les->title,
                                'slug' => $les->slug,
                                'duration_minutes' => $les->duration_minutes,
                                'type' => $les->type,
                                'is_free_preview' => (bool) $les->is_free_preview,
                                'video_url' => $les->external_url,
                            ];
                        })
                    ];
                }),
                'quiz' => [
                    'title' => 'Module Graded Assessment: ' . $course->title,
                    'passing_score' => 80,
                    'questions' => [
                        [
                            'id' => 1,
                            'question' => 'In enterprise production applications, what is the primary benefit of decoupled architecture?',
                            'options' => [
                                'Independent scalability of frontend and backend services',
                                'Elimination of all network latency',
                                'Removing the need for database migrations',
                                'Automatic generation of CSS stylesheets'
                            ],
                            'correct_index' => 0,
                            'explanation' => 'Decoupling client SPAs and REST/GraphQL backends allows independent horizontal scaling, technology flexibility, and separated deployment lifecycles.'
                        ],
                        [
                            'id' => 2,
                            'question' => 'What is the cryptographic mechanism used to ensure tamper-proof credential verification on edX and Coursera?',
                            'options' => [
                                'SHA-256 digital fingerprinting and blockchain-style ledger auditing',
                                'Base64 plain text encoding',
                                'Rot13 ciphering',
                                'Simple HTTP cookie caching'
                            ],
                            'correct_index' => 0,
                            'explanation' => 'Cryptographic SHA-256 hashes bind the recipient identity, issuing university, and completion timestamp into an immutable tamper-evident proof.'
                        ],
                        [
                            'id' => 3,
                            'question' => 'Why does Online Global Classroom use 25-minute live immersion sessions instead of passive 2-hour lectures?',
                            'options' => [
                                'Maximizes focused oral production and active retrieval with native speakers without cognitive fatigue',
                                'Because video servers cannot run longer than 25 minutes',
                                'Students are prohibited from speaking for more than 20 minutes',
                                'It is only designed for listening to recordings'
                            ],
                            'correct_index' => 0,
                            'explanation' => 'High-intensity 25-minute conversational blocks optimize active spoken recall, deliberate vocabulary practice, and rapid conversational fluency.'
                        ]
                    ]
                ],
                'reviews' => [
                    [
                        'author' => 'Michael Chang',
                        'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&auto=format&fit=crop&q=80',
                        'rating' => 5,
                        'date' => '3 days ago',
                        'comment' => 'One of the best structured courses I have ever taken. The combination of self-paced on-demand videos and live classroom immersion helped me land a senior position!'
                    ],
                    [
                        'author' => 'Fatima Al-Mansoor',
                        'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80',
                        'rating' => 5,
                        'date' => '1 week ago',
                        'comment' => 'The verifiable certificate from Stanford Online and GELME was directly verified by my employer during hiring. Outstanding curriculum and interactive labs.'
                    ],
                    [
                        'author' => 'Lucas Bernard',
                        'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80',
                        'rating' => 5,
                        'date' => '2 weeks ago',
                        'comment' => 'The 25-minute native immersion lessons are revolutionary. Speaking directly with certified native teachers accelerated my conversational fluency faster than any app.'
                    ]
                ]
            ]
        ]);
    }

    public function categories()
    {
        $categories = CourseCategory::withCount('courses')->get();
        return response()->json([
            'categories' => $categories->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'description' => $c->description,
                    'courses_count' => $c->courses_count,
                ];
            })
        ]);
    }

    public function institutions()
    {
        $institutions = Institution::withCount('courses')->get();
        return response()->json([
            'institutions' => $institutions->map(function ($inst) {
                return [
                    'id' => $inst->id,
                    'name' => $inst->name,
                    'slug' => $inst->slug,
                    'website' => $inst->website,
                    'type' => $inst->type,
                    'courses_count' => $inst->courses_count,
                ];
            })
        ]);
    }
}
