<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Country;
use App\Models\Organization;
use App\Models\Institution;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Models\VirtualClassroom;
use App\Models\Enrollment;
use App\Models\Credential;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Countries
        $countryUS = Country::firstOrCreate(['code' => 'USA'], ['name' => 'United States', 'iso2' => 'US', 'phone_code' => '+1', 'status' => true]);
        $countryET = Country::firstOrCreate(['code' => 'ETH'], ['name' => 'Ethiopia', 'iso2' => 'ET', 'phone_code' => '+251', 'status' => true]);
        $countryCO = Country::firstOrCreate(['code' => 'COL'], ['name' => 'Colombia', 'iso2' => 'CO', 'phone_code' => '+57', 'status' => true]);
        $countryGB = Country::firstOrCreate(['code' => 'GBR'], ['name' => 'United Kingdom', 'iso2' => 'GB', 'phone_code' => '+44', 'status' => true]);
        $countryCN = Country::firstOrCreate(['code' => 'CHN'], ['name' => 'China', 'iso2' => 'CN', 'phone_code' => '+86', 'status' => true]);
        $countryJP = Country::firstOrCreate(['code' => 'JPN'], ['name' => 'Japan', 'iso2' => 'JP', 'phone_code' => '+81', 'status' => true]);
        $countryFR = Country::firstOrCreate(['code' => 'FRA'], ['name' => 'France', 'iso2' => 'FR', 'phone_code' => '+33', 'status' => true]);
        $countryDE = Country::firstOrCreate(['code' => 'DEU'], ['name' => 'Germany', 'iso2' => 'DE', 'phone_code' => '+49', 'status' => true]);

        // 1. Organization
        $org = Organization::firstOrCreate(
            ['slug' => 'gelme-global-consortium'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'GELME Global Education Consortium',
                'type' => 'education_consortium',
                'country_id' => $countryUS->id,
                'contact_email' => 'partnerships@gelme.global',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

        // 2. Instructors & Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@gelme.global'],
            ['name' => 'Dr. Elena Rostova', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $admin->id],
            [
                'legal_name' => 'Dr. Elena Rostova, Ph.D.',
                'preferred_name' => 'Elena',
                'bio' => 'Chief Academic Officer & Global Platform Administrator. Former Dean of Computing & Distributed Systems.',
                'website' => 'https://gelme.global'
            ]
        );

        $sarahChen = User::firstOrCreate(
            ['email' => 'sarah.chen@gelme.global'],
            ['name' => 'Prof. Sarah Chen', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $sarahChen->id],
            [
                'legal_name' => 'Prof. Sarah Chen',
                'preferred_name' => 'Sarah',
                'bio' => 'Senior AI Researcher & Full-Stack Architect. Stanford Online Lead Instructor. 320,000+ students globally.',
                'website' => 'https://stanford.edu/~schen'
            ]
        );

        $carlos = User::firstOrCreate(
            ['email' => 'carlos.mendez@gelme.global'],
            ['name' => 'Carlos Méndez', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $carlos->id],
            [
                'legal_name' => 'Carlos Méndez',
                'preferred_name' => 'Carlos',
                'bio' => 'Certified Native Spanish Polyglot (Bogotá, Colombia). Online Global Classroom Master Coach with 12,000+ completed 25-min sessions.',
                'website' => 'https://www.onlineglobalclassroom.com/teachers/carlos'
            ]
        );

        $selamawit = User::firstOrCreate(
            ['email' => 'selamawit.tadesse@gelme.global'],
            ['name' => 'Selamawit Tadesse', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $selamawit->id],
            [
                'legal_name' => 'Selamawit Tadesse',
                'preferred_name' => 'Selam',
                'bio' => 'Native Amharic & Ge\'ez Cultural Linguist from Addis Ababa. Online Global Classroom Lead Immersion Specialist.',
                'website' => 'https://www.onlineglobalclassroom.com/teachers/selamawit'
            ]
        );

        $davidMiller = User::firstOrCreate(
            ['email' => 'david.miller@gelme.global'],
            ['name' => 'David Miller, M.A.', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $davidMiller->id],
            [
                'legal_name' => 'David Miller',
                'preferred_name' => 'David',
                'bio' => 'British Accent & Executive ESL Specialist (London, UK). CELTA certified coach with 10+ years in international business English.',
                'website' => 'https://www.onlineglobalclassroom.com/teachers/david'
            ]
        );

        $meiLing = User::firstOrCreate(
            ['email' => 'meiling.zhou@gelme.global'],
            ['name' => 'Mei-Ling Zhou (周美玲)', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $meiLing->id],
            [
                'legal_name' => 'Mei-Ling Zhou',
                'preferred_name' => 'Mei-Ling',
                'bio' => 'Native Mandarin Chinese (Beijing Accent). HSK & Business Mandarin certified tutor with 8,000+ interactive sessions.',
                'website' => 'https://www.onlineglobalclassroom.com/teachers/meiling'
            ]
        );

        $kenjiSato = User::firstOrCreate(
            ['email' => 'kenji.sato@gelme.global'],
            ['name' => 'Kenji Sato (佐藤健二)', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $claireDubois = User::firstOrCreate(
            ['email' => 'claire.dubois@gelme.global'],
            ['name' => 'Claire Dubois', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $klaus = User::firstOrCreate(
            ['email' => 'klaus.schneider@gelme.global'],
            ['name' => 'Klaus Schneider', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $alexVance = User::firstOrCreate(
            ['email' => 'alex.vance@gelme.global'],
            ['name' => 'Alex Vance, CISSP', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@gelme.global'],
            ['name' => 'Abebe Bekele', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $student->id],
            [
                'legal_name' => 'Abebe Bekele',
                'preferred_name' => 'Abebe',
                'bio' => 'Active Student Scholar pursuing Full-Stack Cloud Engineering and Global Polyglot Immersion.',
                'website' => 'https://github.com/abebe-b'
            ]
        );

        // 3. Institutions
        $stanford = Institution::firstOrCreate(
            ['slug' => 'stanford-online'],
            [
                'uuid' => (string) Str::uuid(),
                'organization_id' => $org->id,
                'country_id' => $countryUS->id,
                'name' => 'Stanford Online & Innovation Lab',
                'type' => 'university',
                'contact_email' => 'online@stanford.edu',
                'website' => 'https://online.stanford.edu',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

        $mitx = Institution::firstOrCreate(
            ['slug' => 'mitx-institute'],
            [
                'uuid' => (string) Str::uuid(),
                'organization_id' => $org->id,
                'country_id' => $countryUS->id,
                'name' => 'MITx MicroMasters Consortium',
                'type' => 'university',
                'contact_email' => 'micromasters@mit.edu',
                'website' => 'https://mitxonline.mit.edu',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

        $ogcInst = Institution::firstOrCreate(
            ['slug' => 'online-global-classroom-hq'],
            [
                'uuid' => (string) Str::uuid(),
                'organization_id' => $org->id,
                'country_id' => $countryUS->id,
                'name' => 'Online Global Classroom Network',
                'type' => 'language_institute',
                'contact_email' => 'support@onlineglobalclassroom.com',
                'website' => 'https://www.onlineglobalclassroom.com',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

        // 4. Categories
        $catDev = CourseCategory::firstOrCreate(['slug' => 'software-development'], ['name' => 'Software & Web Development', 'description' => 'Full-stack engineering, Vue 3, Laravel 11, REST APIs, and microservices.']);
        $catAI = CourseCategory::firstOrCreate(['slug' => 'artificial-intelligence'], ['name' => 'AI & Data Science', 'description' => 'Deep learning, LLMs, neural networks, and prompt engineering.']);
        $catLang = CourseCategory::firstOrCreate(['slug' => 'global-languages'], ['name' => 'Global Languages & Immersion', 'description' => '25-minute live virtual conversational lessons with native tutors across 14 languages.']);
        $catCloud = CourseCategory::firstOrCreate(['slug' => 'cloud-devops'], ['name' => 'Cloud, DevOps & Security', 'description' => 'Kubernetes, AWS, Docker, CI/CD, and ethical hacking.']);
        $catDesign = CourseCategory::firstOrCreate(['slug' => 'design-ui-ux'], ['name' => 'Design & Creative Systems', 'description' => 'Figma design systems, UI/UX architecture, and responsive product design.']);

        // 5. Courses Setup Helper
        $coursesData = [
            [
                'slug' => 'complete-full-stack-web-development-bootcamp',
                'title' => 'Complete Full-Stack Web Development Bootcamp 2026',
                'subtitle' => 'Master Vue 3, Laravel 11, Tailwind CSS, REST APIs, Docker, and Cloud Deployment from scratch to production.',
                'description' => 'The ultimate Udemy-style engineering masterclass. Built for developers wanting hands-on real-world experience building scalable web applications. Includes lifetime access, code repositories, downloadable assets, and direct Q&A support.',
                'category_id' => $catDev->id,
                'institution_id' => $stanford->id,
                'creator_id' => $sarahChen->id,
                'level' => 'all_levels',
                'type' => 'self_paced',
                'language' => 'en',
                'price' => 14.99,
                'duration_hours' => 48,
                'current_enrollments' => 84210,
                'objectives' => [
                    'Build production-grade SPAs with Vue 3 Composition API, Pinia, and Tailwind CSS',
                    'Architect secure, scalable RESTful APIs with Laravel 11 and SQLite/PostgreSQL',
                    'Deploy applications with Docker, CI/CD pipelines, and cloud hosting',
                    'Implement JWT authentication, authorization policies, and automated test suites'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Architecture & Modern Frontend (Vue 3 + Vite)',
                        'lessons' => [
                            ['title' => 'Platform Introduction & Course Roadmap', 'duration' => 12, 'preview' => true],
                            ['title' => 'Vue 3 Composition API: Reactive State & Computed Properties', 'duration' => 18, 'preview' => true],
                            ['title' => 'State Management with Pinia Modular Stores', 'duration' => 20, 'preview' => false],
                            ['title' => 'Styling with Tailwind CSS & Responsive Layouts', 'duration' => 15, 'preview' => false]
                        ]
                    ],
                    [
                        'title' => 'Module 2: Scalable RESTful Backend with Laravel 11',
                        'lessons' => [
                            ['title' => 'Laravel 11 Architecture & Routing Pipeline', 'duration' => 22, 'preview' => false],
                            ['title' => 'Eloquent Relationships: One-to-Many & Polymorphic Associations', 'duration' => 25, 'preview' => false],
                            ['title' => 'Database Migrations & 75-Table Relational Integrity', 'duration' => 30, 'preview' => false],
                            ['title' => 'Building Fast REST Controllers & Resource Responses', 'duration' => 20, 'preview' => false]
                        ]
                    ],
                    [
                        'title' => 'Module 3: Production Deployment & Docker Containerization',
                        'lessons' => [
                            ['title' => 'Containerizing Vue 3 and Laravel with Docker Compose', 'duration' => 25, 'preview' => false],
                            ['title' => 'Nginx Reverse Proxy & SSL Configuration', 'duration' => 18, 'preview' => false],
                            ['title' => 'Final Capstone Project: Launching the Super Platform', 'duration' => 35, 'preview' => false]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'advanced-ai-deep-learning-specialization',
                'title' => 'Advanced AI & Deep Learning Specialization',
                'subtitle' => 'Curated university specialization on Transformers, Large Language Models, PyTorch, and Cognitive Agents.',
                'description' => 'Coursera-accredited university program offered in consortium with MITx and Stanford Online. Rigorous academic grading, proctored quizzes, and a verifiable digital credential upon completion.',
                'category_id' => $catAI->id,
                'institution_id' => $mitx->id,
                'creator_id' => $sarahChen->id,
                'level' => 'advanced',
                'type' => 'cohort_based',
                'language' => 'en',
                'price' => 79.00,
                'duration_hours' => 64,
                'current_enrollments' => 38900,
                'objectives' => [
                    'Master Transformer architectures, Self-Attention mechanisms, and Multi-Head Attention',
                    'Fine-tune open-weight LLMs using LoRA, QLoRA, and RLHF',
                    'Build autonomous agent loops with LangChain, LlamaIndex, and vector databases',
                    'Deploy low-latency inference pipelines with ONNX and TensorRT'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Attention Mechanisms & Transformer Foundations',
                        'lessons' => [
                            ['title' => 'From RNNs to Transformers: Self-Attention Mathematics', 'duration' => 24, 'preview' => true],
                            ['title' => 'Implementing Scaled Dot-Product Attention in PyTorch', 'duration' => 32, 'preview' => false],
                            ['title' => 'Positional Encodings and Layer Normalization', 'duration' => 19, 'preview' => false]
                        ]
                    ],
                    [
                        'title' => 'Module 2: Generative Pre-training & Fine-Tuning Strategies',
                        'lessons' => [
                            ['title' => 'Pre-training at Scale: Data tokenization and loss curves', 'duration' => 28, 'preview' => false],
                            ['title' => 'Parameter-Efficient Fine-Tuning (PEFT): LoRA & QLoRA', 'duration' => 35, 'preview' => false],
                            ['title' => 'RLHF: Reward modeling and PPO alignment', 'duration' => 40, 'preview' => false]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'conversational-spanish-immersion-25min',
                'title' => 'Conversational Spanish: 25-Minute Live Native Immersion',
                'subtitle' => 'Break down language barriers with live, personalized 1-on-1 and small-group 25-minute sessions conducted by certified native teachers.',
                'description' => 'Online Global Classroom signature methodology: 25-minute targeted conversational lessons focused on natural fluency, cultural nuance, and everyday spoken situations. Certified native speakers guide every interaction.',
                'category_id' => $catLang->id,
                'institution_id' => $ogcInst->id,
                'creator_id' => $carlos->id,
                'level' => 'beginner',
                'type' => 'live_virtual',
                'language' => 'es',
                'price' => 19.99,
                'duration_hours' => 12,
                'current_enrollments' => 18200,
                'objectives' => [
                    'Speak confident conversational Spanish from session 1 with native speakers',
                    'Master real-world travel, dining, business, and social situations',
                    'Live WebRTC virtual classrooms with interactive whiteboard and shared vocabulary notes',
                    'Attain certified CEFR B1/B2 spoken conversational fluency'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Everyday Social Interaction & Travel',
                        'lessons' => [
                            ['title' => 'Saludos y Presentaciones Naturales (Native Greetings)', 'duration' => 25, 'preview' => true],
                            ['title' => 'En el Restaurante: Ordering food & expressing preferences', 'duration' => 25, 'preview' => false],
                            ['title' => 'Navegando la Ciudad: Asking directions & transport', 'duration' => 25, 'preview' => false]
                        ]
                    ],
                    [
                        'title' => 'Module 2: Real-World Conversation & Storytelling',
                        'lessons' => [
                            ['title' => 'Talking about past experiences & weekend plans', 'duration' => 25, 'preview' => false],
                            ['title' => 'Expressing opinions, agreement, and cultural views', 'duration' => 25, 'preview' => false]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'amharic-language-heritage-immersion-25min',
                'title' => 'Amharic Language & Ethiopian Heritage: 25-Min Live Immersion',
                'subtitle' => 'Explore the richness of Amharic (አማርኛ) and Ge\'ez heritage with certified native Ethiopian instructors.',
                'description' => 'Online Global Classroom cultural immersion track: Practice conversational Amharic with native speakers from Addis Ababa. Includes the Ge\'ez Fidel alphabet, hospitality etiquette, and practical conversational fluency.',
                'category_id' => $catLang->id,
                'institution_id' => $ogcInst->id,
                'creator_id' => $selamawit->id,
                'level' => 'beginner',
                'type' => 'live_virtual',
                'language' => 'am',
                'price' => 18.50,
                'duration_hours' => 14,
                'current_enrollments' => 9420,
                'objectives' => [
                    'Master essential Amharic greetings and conversational etiquette',
                    'Understand the phonetic foundation of the Ge\'ez script (Fidel)',
                    'Practice live 25-minute interactive dialogues with native tutors',
                    'Explore Ethiopian culture, coffee ceremony traditions, and history'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Conversational Foundations (እንኳን ደህና መጣችሁ)',
                        'lessons' => [
                            ['title' => 'Greetings, Introductions & Politeness (ሰላምታ እና መተዋወቅ)', 'duration' => 25, 'preview' => true],
                            ['title' => 'Everyday Phrases & Numbers (የዕለት ተዕለት ቃላት)', 'duration' => 25, 'preview' => false],
                            ['title' => 'The Ethiopian Coffee Ceremony & Hospitality Customs', 'duration' => 25, 'preview' => false]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'english-esl-business-accent-reduction-25min',
                'title' => 'Executive English (ESL) & Accent Reduction: 25-Min Native Immersion',
                'subtitle' => 'Speak fluent, confident business English with certified British and American native voice specialists.',
                'description' => 'Online Global Classroom professional track: Targeted 25-minute 1-on-1 sessions designed for international founders, engineers, and executives to eliminate hesitation and communicate with authority.',
                'category_id' => $catLang->id,
                'institution_id' => $ogcInst->id,
                'creator_id' => $davidMiller->id,
                'level' => 'intermediate',
                'type' => 'live_virtual',
                'language' => 'en',
                'price' => 22.00,
                'duration_hours' => 20,
                'current_enrollments' => 24100,
                'objectives' => [
                    'Eliminate hesitation during international pitch presentations and team standups',
                    'Master clear vowel resonance and connected speech rhythm',
                    'Participate in high-intensity mock board meetings and executive negotiations',
                    'Receive real-time phonetic feedback and custom pronunciation drills'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Corporate Communication & Presentation Mastery',
                        'lessons' => [
                            ['title' => 'Pitching Ideas with Executive Gravitas', 'duration' => 25, 'preview' => true],
                            ['title' => 'Handling Tough Questions & Negotiating in English', 'duration' => 25, 'preview' => false],
                            ['title' => 'Intonation, Pitch Variation, and Stress Timing', 'duration' => 25, 'preview' => false]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'mandarin-chinese-fluency-25min',
                'title' => 'Mandarin Chinese for Global Business: 25-Min Live Native Immersion',
                'subtitle' => 'Master tones, Pinyin, and everyday conversational Chinese (普通话) with certified Beijing native tutors.',
                'description' => 'Online Global Classroom immersion format: Learn spoken Chinese rapidly through practical dialogues, business etiquette, and interactive whiteboard character practice.',
                'category_id' => $catLang->id,
                'institution_id' => $ogcInst->id,
                'creator_id' => $meiLing->id,
                'level' => 'all_levels',
                'type' => 'live_virtual',
                'language' => 'zh',
                'price' => 21.50,
                'duration_hours' => 25,
                'current_enrollments' => 15300,
                'objectives' => [
                    'Demystify the 4 tones and pronounce Pinyin with native accuracy',
                    'Master 300 high-frequency words for everyday living and business meetings',
                    'Understand Chinese business culture and networking etiquette (Guanxi)',
                    'Live character drawing and shared notes in 25-minute live rooms'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Tonal Foundations & Daily Survival',
                        'lessons' => [
                            ['title' => 'Tones and Pinyin Pronunciation Workout', 'duration' => 25, 'preview' => true],
                            ['title' => 'Self-Introduction & Business Card Exchange (名片)', 'duration' => 25, 'preview' => false]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'cybersecurity-ethical-hacking-masterclass',
                'title' => 'Cybersecurity & Ethical Hacking Masterclass 2026',
                'subtitle' => 'Comprehensive penetration testing, network security, web app exploitation, and incident response.',
                'description' => 'Udemy-style hands-on cybersecurity bootcamp. Practice penetration testing in simulated cloud labs. Covers Kali Linux, OWASP Top 10, Metasploit, Wireshark, and defensive cryptography.',
                'category_id' => $catCloud->id,
                'institution_id' => $stanford->id,
                'creator_id' => $alexVance->id,
                'level' => 'all_levels',
                'type' => 'self_paced',
                'language' => 'en',
                'price' => 16.99,
                'duration_hours' => 38,
                'current_enrollments' => 52300,
                'objectives' => [
                    'Perform vulnerability assessments and authorized penetration tests',
                    'Identify and exploit OWASP Top 10 vulnerabilities (SQLi, XSS, SSRF)',
                    'Analyze network traffic packets with Wireshark and tcpdump',
                    'Implement defensive hardening and multi-factor Zero Trust security'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Reconnaissance & Network Scanning',
                        'lessons' => [
                            ['title' => 'Passive vs Active Reconnaissance Techniques', 'duration' => 20, 'preview' => true],
                            ['title' => 'Advanced Nmap Port Scanning & Service Enumeration', 'duration' => 25, 'preview' => false],
                            ['title' => 'Vulnerability Scanning with OpenVAS', 'duration' => 22, 'preview' => false]
                        ]
                    ]
                ]
            ],
            [
                'slug' => 'cloud-devops-kubernetes-docker-aws',
                'title' => 'Cloud DevOps Architecture with Docker, Kubernetes & AWS',
                'subtitle' => 'Architect automated CI/CD pipelines, container orchestration, and multi-region cloud infrastructures.',
                'description' => 'Coursera / edX accredited university specialization: Learn production DevOps workflows used by Fortune 500 tech companies. Verifiable certificate backed by SHA-256 blockchain proof.',
                'category_id' => $catCloud->id,
                'institution_id' => $mitx->id,
                'creator_id' => $sarahChen->id,
                'level' => 'intermediate',
                'type' => 'cohort_based',
                'language' => 'en',
                'price' => 69.00,
                'duration_hours' => 50,
                'current_enrollments' => 41200,
                'objectives' => [
                    'Build microservices architectures with Docker & Kubernetes',
                    'Automate deployments using GitHub Actions and ArgoCD',
                    'Manage Infrastructure as Code with Terraform and AWS CloudFormation',
                    'Monitor production health with Prometheus, Grafana, and OpenTelemetry'
                ],
                'modules' => [
                    [
                        'title' => 'Module 1: Container Orchestration Foundations',
                        'lessons' => [
                            ['title' => 'Containers vs Virtual Machines Architecture', 'duration' => 18, 'preview' => true],
                            ['title' => 'Kubernetes Pods, Services, and Ingress Controllers', 'duration' => 30, 'preview' => false]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($coursesData as $cData) {
            $course = Course::firstOrCreate(
                ['slug' => $cData['slug']],
                [
                    'title' => $cData['title'],
                    'subtitle' => $cData['subtitle'],
                    'description' => $cData['description'],
                    'category_id' => $cData['category_id'],
                    'institution_id' => $cData['institution_id'],
                    'creator_id' => $cData['creator_id'],
                    'level' => $cData['level'],
                    'type' => $cData['type'],
                    'language' => $cData['language'],
                    'price' => $cData['price'],
                    'currency' => 'USD',
                    'is_free' => false,
                    'status' => 'active',
                    'state' => 'published',
                    'visibility' => 'public',
                    'duration_hours' => $cData['duration_hours'],
                    'current_enrollments' => $cData['current_enrollments'],
                    'objectives' => $cData['objectives']
                ]
            );

            // Create modules & lessons
            foreach ($cData['modules'] as $mIdx => $modData) {
                $mod = CourseModule::firstOrCreate(
                    ['course_id' => $course->id, 'slug' => Str::slug($modData['title'])],
                    [
                        'title' => $modData['title'],
                        'description' => 'Comprehensive syllabus module covering core concepts and applied projects.',
                        'order' => $mIdx + 1,
                        'is_published' => true
                    ]
                );

                foreach ($modData['lessons'] as $lIdx => $lesData) {
                    Lesson::firstOrCreate(
                        ['module_id' => $mod->id, 'slug' => Str::slug($lesData['title'])],
                        [
                            'course_id' => $course->id,
                            'title' => $lesData['title'],
                            'description' => 'In-depth lecture with code examples, architectural diagrams, and interactive exercises.',
                            'type' => 'video',
                            'external_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                            'duration_minutes' => $lesData['duration'],
                            'order' => $lIdx + 1,
                            'is_published' => true,
                            'is_free_preview' => $lesData['preview'],
                            'content' => ['summary' => 'Comprehensive walkthrough with real-world examples.']
                        ]
                    );
                }
            }
        }

        // 6. Online Global Classrooms (Live 25-Min Immersion Sessions)
        $courseSpanish = Course::where('slug', 'conversational-spanish-immersion-25min')->first();
        $courseAmharic = Course::where('slug', 'amharic-language-heritage-immersion-25min')->first();
        $courseEnglish = Course::where('slug', 'english-esl-business-accent-reduction-25min')->first();
        $courseMandarin = Course::where('slug', 'mandarin-chinese-fluency-25min')->first();
        $courseAI = Course::where('slug', 'advanced-ai-deep-learning-specialization')->first();

        $roomsData = [
            [
                'slug' => 'spanish-travel-daily-immersion',
                'course_id' => $courseSpanish?->id,
                'creator_id' => $carlos->id,
                'title' => 'Live 25-Min Conversational Spanish: Travel & Daily Situations',
                'description' => 'Small group immersion class with native instructor Carlos Méndez (Bogotá). Practice ordering in restaurants, asking directions, and social conversations.',
                'language' => 'Spanish (Español)',
                'scheduled_offset' => 15, // in minutes
                'duration' => 25,
                'room_id' => 'OGC-SPANISH-LIVE-101',
                'url' => 'https://meet.jit.si/gelme-spanish-travel-immersion-2026',
                'format' => '25-Min Native Immersion'
            ],
            [
                'slug' => 'amharic-culture-heritage-exchange',
                'course_id' => $courseAmharic?->id,
                'creator_id' => $selamawit->id,
                'title' => 'Global Classroom: Amharic & Ethiopian Heritage Immersion',
                'description' => 'Interactive cultural language exchange session with native speaker Selamawit Tadesse focusing on Ge\'ez alphabet, greetings, and hospitality phrases.',
                'language' => 'Amharic (አማርኛ)',
                'scheduled_offset' => 45,
                'duration' => 25,
                'room_id' => 'OGC-AMHARIC-CULTURE-201',
                'url' => 'https://meet.jit.si/gelme-amharic-heritage-session-2026',
                'format' => '25-Min Native Immersion'
            ],
            [
                'slug' => 'english-executive-business-fluency',
                'course_id' => $courseEnglish?->id,
                'creator_id' => $davidMiller->id,
                'title' => 'Executive British English: High-Stakes Presentations & Accent Coaching',
                'description' => 'Targeted 25-minute accent refinement and pitch delivery with certified London coach David Miller. Build instant confidence.',
                'language' => 'English (ESL / British Accent)',
                'scheduled_offset' => 75,
                'duration' => 25,
                'room_id' => 'OGC-ENGLISH-EXEC-301',
                'url' => 'https://meet.jit.si/gelme-english-executive-2026',
                'format' => '25-Min Native Immersion'
            ],
            [
                'slug' => 'mandarin-beijing-daily-conversations',
                'course_id' => $courseMandarin?->id,
                'creator_id' => $meiLing->id,
                'title' => 'Mandarin Chinese: Daily Life & Beijing Street Conversations (普通话)',
                'description' => 'Practice the 4 tones naturally with Mei-Ling Zhou. Learn practical expressions for shopping, ordering authentic food, and chatting with locals.',
                'language' => 'Mandarin (中文)',
                'scheduled_offset' => 110,
                'duration' => 25,
                'room_id' => 'OGC-MANDARIN-LIVE-401',
                'url' => 'https://meet.jit.si/gelme-mandarin-fluency-2026',
                'format' => '25-Min Native Immersion'
            ],
            [
                'slug' => 'japanese-tokyo-jlpt-conversational',
                'course_id' => null,
                'creator_id' => $kenjiSato->id,
                'title' => 'Japanese Fluency: Everyday Tokyo Situations & JLPT Practice (日本語)',
                'description' => 'Interactive 25-minute conversational practice with native Tokyo teacher Kenji Sato. Focus on natural spoken rhythm, keigo politeness, and anime idioms.',
                'language' => 'Japanese (日本語)',
                'scheduled_offset' => 140,
                'duration' => 25,
                'room_id' => 'OGC-JAPANESE-LIVE-501',
                'url' => 'https://meet.jit.si/gelme-japanese-fluency-2026',
                'format' => '25-Min Native Immersion'
            ],
            [
                'slug' => 'french-paris-cafe-conversations',
                'course_id' => null,
                'creator_id' => $claireDubois->id,
                'title' => 'French Immersion: Parisian Café Culture & Natural Slang (Français)',
                'description' => 'Immerse in conversational French with Paris native Claire Dubois. Move beyond textbook phrases into authentic everyday French dialogue.',
                'language' => 'French (Français)',
                'scheduled_offset' => 180,
                'duration' => 25,
                'room_id' => 'OGC-FRENCH-LIVE-601',
                'url' => 'https://meet.jit.si/gelme-french-fluency-2026',
                'format' => '25-Min Native Immersion'
            ],
            [
                'slug' => 'german-berlin-tech-business',
                'course_id' => null,
                'creator_id' => $klaus->id,
                'title' => 'German for Professionals: Tech & Engineering Workplaces (Deutsch)',
                'description' => 'Speak accurate, structured German in meetings and technical interviews with native tutor Klaus Schneider from Berlin.',
                'language' => 'German (Deutsch)',
                'scheduled_offset' => 210,
                'duration' => 25,
                'room_id' => 'OGC-GERMAN-LIVE-701',
                'url' => 'https://meet.jit.si/gelme-german-fluency-2026',
                'format' => '25-Min Native Immersion'
            ],
            [
                'slug' => 'stanford-ai-office-hours',
                'course_id' => $courseAI?->id,
                'creator_id' => $sarahChen->id,
                'title' => 'Stanford / DeepMind Office Hours: Transformer Debugging & PyTorch Labs',
                'description' => 'Live interactive coding lab. Bring your attention matrix bugs, GPU memory bottlenecks, and PyTorch optimization queries.',
                'language' => 'English & Python Code',
                'scheduled_offset' => 240,
                'duration' => 60,
                'room_id' => 'OGC-AI-WORKSHOP-801',
                'url' => 'https://meet.jit.si/gelme-ai-office-hours-stanford',
                'format' => '60-Min Interactive Lab'
            ]
        ];

        foreach ($roomsData as $rData) {
            VirtualClassroom::firstOrCreate(
                ['slug' => $rData['slug']],
                [
                    'course_id' => $rData['course_id'],
                    'creator_id' => $rData['creator_id'],
                    'title' => $rData['title'],
                    'description' => $rData['description'],
                    'type' => 'live',
                    'status' => 'scheduled',
                    'scheduled_at' => now()->addMinutes($rData['scheduled_offset']),
                    'duration_minutes' => $rData['duration'],
                    'provider' => 'jitsi',
                    'provider_room_id' => $rData['room_id'],
                    'provider_room_url' => $rData['url'],
                    'is_recorded' => true,
                    'is_chat_enabled' => true,
                    'is_whiteboard_enabled' => true,
                    'settings' => [
                        'immersion_language' => $rData['language'],
                        'format' => $rData['format'],
                        'max_capacity' => 12,
                        'current_participants' => rand(4, 9),
                    ]
                ]
            );
        }

        // 7. Student Enrollments & Verifiable Credentials
        $courseWeb = Course::where('slug', 'complete-full-stack-web-development-bootcamp')->first();
        $enr1 = Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $courseWeb->id],
            [
                'status' => 'completed',
                'enrollment_type' => 'self',
                'enrollment_date' => now()->subDays(20)->toDateString(),
                'completion_date' => now()->subDays(1)->toDateString(),
                'progress_percent' => 100.00,
                'grade' => 98.50,
                'completed_lessons_count' => 11,
                'total_lessons_count' => 11,
                'last_accessed_at' => now()
            ]
        );

        $certCode1 = 'GELME-CERT-2026-X892K';
        Credential::firstOrCreate(
            ['verification_code' => $certCode1],
            [
                'uuid' => (string) Str::uuid(),
                'issuer_id' => $stanford->id,
                'issuer_type' => Institution::class,
                'recipient_id' => $student->id,
                'recipient_type' => User::class,
                'course_id' => $courseWeb->id,
                'enrollment_id' => $enr1->id,
                'type' => 'certificate',
                'category' => 'professional',
                'name' => 'Full-Stack Web Development & Cloud Architecture Professional Certificate',
                'description' => 'Awarded for demonstrating verified mastery in Vue 3, Laravel 11 enterprise REST APIs, Docker microservices, and cloud deployments.',
                'verification_url' => 'http://localhost:3000/verify/' . $certCode1,
                'issued_at' => now()->subDays(1),
                'is_public' => true,
                'is_verifiable' => true,
                'criteria' => [
                    'skills' => ['Vue 3 Composition API', 'Laravel 11 Enterprise REST', 'Cloud & Docker Architecture', 'Tailwind CSS'],
                    'grade' => '98.5%',
                    'credits' => '4.5 CEU',
                    'blockchain_hash' => hash('sha256', $certCode1 . 'StanfordOnlineGELME2026')
                ]
            ]
        );

        $certCode2 = 'STANFORD-AI-2026-9901';
        Credential::firstOrCreate(
            ['verification_code' => $certCode2],
            [
                'uuid' => (string) Str::uuid(),
                'issuer_id' => $mitx->id,
                'issuer_type' => Institution::class,
                'recipient_id' => $student->id,
                'recipient_type' => User::class,
                'course_id' => $courseAI->id,
                'type' => 'specialization',
                'category' => 'academic',
                'name' => 'Advanced AI & Deep Learning Master Specialization Credential',
                'description' => 'Honors credential awarded for proficiency in Transformers, PyTorch, Large Language Models, and Cognitive AI Systems.',
                'verification_url' => 'http://localhost:3000/verify/' . $certCode2,
                'issued_at' => now()->subDays(5),
                'is_public' => true,
                'is_verifiable' => true,
                'criteria' => [
                    'skills' => ['Transformers', 'PyTorch LLM Fine-Tuning', 'Cognitive Agents', 'RLHF Alignment'],
                    'grade' => '99.2%',
                    'credits' => '6.0 CEU',
                    'blockchain_hash' => hash('sha256', $certCode2 . 'MITxGELME2026')
                ]
            ]
        );

        $certCode3 = 'OGC-SPANISH-2026-7741';
        Credential::firstOrCreate(
            ['verification_code' => $certCode3],
            [
                'uuid' => (string) Str::uuid(),
                'issuer_id' => $ogcInst->id,
                'issuer_type' => Institution::class,
                'recipient_id' => $student->id,
                'recipient_type' => User::class,
                'course_id' => $courseSpanish->id,
                'type' => 'language_proficiency',
                'category' => 'immersion',
                'name' => 'Online Global Classroom CEFR B2 Conversational Spanish Certificate',
                'description' => 'Verified certificate of oral fluency achieved through 25-minute live native speaker immersion sessions.',
                'verification_url' => 'http://localhost:3000/verify/' . $certCode3,
                'issued_at' => now()->subDays(10),
                'is_public' => true,
                'is_verifiable' => true,
                'criteria' => [
                    'skills' => ['Conversational Fluency', 'CEFR B2 Spoken Interaction', 'Cultural Idioms', 'Native Pronunciation'],
                    'grade' => '96.0%',
                    'credits' => '2.5 CEU',
                    'blockchain_hash' => hash('sha256', $certCode3 . 'OGCSpanish2026')
                ]
            ]
        );
    }
}
