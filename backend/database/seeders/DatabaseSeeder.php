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
        $countryGB = Country::firstOrCreate(['code' => 'GBR'], ['name' => 'United Kingdom', 'iso2' => 'GB', 'phone_code' => '+44', 'status' => true]);
        $countryCO = Country::firstOrCreate(['code' => 'COL'], ['name' => 'Colombia', 'iso2' => 'CO', 'phone_code' => '+57', 'status' => true]);
        $countryCN = Country::firstOrCreate(['code' => 'CHN'], ['name' => 'China', 'iso2' => 'CN', 'phone_code' => '+86', 'status' => true]);

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

        // 2. Institutions (Coursera, Google, IBM, Intuit, Stanford, MITx, OGC)
        $google = Institution::firstOrCreate(
            ['slug' => 'google-career-certificates'],
            [
                'uuid' => (string) Str::uuid(),
                'organization_id' => $org->id,
                'country_id' => $countryUS->id,
                'name' => 'Google Career Certificates',
                'type' => 'industry_leader',
                'contact_email' => 'grow-with-google@google.com',
                'website' => 'https://grow.google/certificates',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

        $ibm = Institution::firstOrCreate(
            ['slug' => 'ibm-skills-network'],
            [
                'uuid' => (string) Str::uuid(),
                'organization_id' => $org->id,
                'country_id' => $countryUS->id,
                'name' => 'IBM Skills Network',
                'type' => 'industry_leader',
                'contact_email' => 'skills@ibm.com',
                'website' => 'https://www.ibm.com/training',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

        $intuit = Institution::firstOrCreate(
            ['slug' => 'intuit-academy'],
            [
                'uuid' => (string) Str::uuid(),
                'organization_id' => $org->id,
                'country_id' => $countryUS->id,
                'name' => 'Intuit Academy',
                'type' => 'industry_leader',
                'contact_email' => 'academy@intuit.com',
                'website' => 'https://academy.intuit.com',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

        $deeplearningAi = Institution::firstOrCreate(
            ['slug' => 'deeplearning-ai'],
            [
                'uuid' => (string) Str::uuid(),
                'organization_id' => $org->id,
                'country_id' => $countryUS->id,
                'name' => 'DeepLearning.AI',
                'type' => 'research_institute',
                'contact_email' => 'support@deeplearning.ai',
                'website' => 'https://www.deeplearning.ai',
                'status' => true,
                'verification_status' => 'verified'
            ]
        );

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

        // 3. Instructors & Users
        $andrewNg = User::firstOrCreate(
            ['email' => 'andrew.ng@gelme.global'],
            ['name' => 'Andrew Ng', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $andrewNg->id],
            ['legal_name' => 'Dr. Andrew Ng, Ph.D.', 'preferred_name' => 'Andrew', 'bio' => 'Founder of DeepLearning.AI, Stanford Adjunct Professor, Co-founder of Coursera.', 'website' => 'https://www.andrewng.org']
        );

        $googleTeam = User::firstOrCreate(
            ['email' => 'google.career.team@gelme.global'],
            ['name' => 'Google Career Team', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $ibmTeam = User::firstOrCreate(
            ['email' => 'ibm.team@gelme.global'],
            ['name' => 'IBM Skills Instructors', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $sarahChen = User::firstOrCreate(
            ['email' => 'sarah.chen@gelme.global'],
            ['name' => 'Prof. Sarah Chen', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $carlos = User::firstOrCreate(
            ['email' => 'carlos.mendez@gelme.global'],
            ['name' => 'Carlos Méndez', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $selamawit = User::firstOrCreate(
            ['email' => 'selamawit.tadesse@gelme.global'],
            ['name' => 'Selamawit Tadesse', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );

        $student = User::firstOrCreate(
            ['email' => 'student@gelme.global'],
            ['name' => 'Abebe Bekele', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        UserProfile::firstOrCreate(
            ['profileable_type' => User::class, 'profileable_id' => $student->id],
            ['legal_name' => 'Abebe Bekele', 'preferred_name' => 'Abebe', 'bio' => 'Student Scholar & Certified Professional Candidate.', 'website' => 'https://github.com/abebe-b']
        );

        // 4. Categories (Aligned with Skills)
        $catAI = CourseCategory::firstOrCreate(['slug' => 'artificial-intelligence'], ['name' => 'Artificial Intelligence (AI)', 'description' => 'Machine learning, generative AI, LLMs, prompt engineering, and deep neural networks.']);
        $catData = CourseCategory::firstOrCreate(['slug' => 'data-analytics'], ['name' => 'Data Analytics & SQL', 'description' => 'Data analysis, visualization, SQL database querying, spreadsheet analytics, and business intelligence.']);
        $catSec = CourseCategory::firstOrCreate(['slug' => 'cybersecurity'], ['name' => 'Cybersecurity', 'description' => 'Threat detection, network security, penetration testing, SIEM tools, and defensive operations.']);
        $catDev = CourseCategory::firstOrCreate(['slug' => 'python-programming'], ['name' => 'Python Programming', 'description' => 'Python for everybody, scripting, automation, algorithms, and application architectures.']);
        $catPM = CourseCategory::firstOrCreate(['slug' => 'project-management'], ['name' => 'Project Management', 'description' => 'Agile, Scrum, project planning, documentation, risk mitigation, and PMP/CAPM alignment.']);
        $catExcel = CourseCategory::firstOrCreate(['slug' => 'microsoft-excel'], ['name' => 'Microsoft Excel', 'description' => 'Spreadsheet modeling, pivot tables, VLOOKUP, macros, and financial data analysis.']);
        $catAcct = CourseCategory::firstOrCreate(['slug' => 'accounting'], ['name' => 'Accounting & Bookkeeping', 'description' => 'Financial statements, payroll, bookkeeping, ledgers, and QuickBooks Intuit workflows.']);
        $catMkt = CourseCategory::firstOrCreate(['slug' => 'digital-marketing'], ['name' => 'Digital Marketing', 'description' => 'SEO, social media advertising, email campaigns, conversion rate optimization, and brand analytics.']);
        $catHR = CourseCategory::firstOrCreate(['slug' => 'human-resources'], ['name' => 'Human Resources (HR)', 'description' => 'Talent acquisition, employee relations, workplace culture, compensation, and organizational leadership.']);
        $catLang = CourseCategory::firstOrCreate(['slug' => 'global-languages'], ['name' => 'Global Languages & Immersion', 'description' => 'Live 25-minute conversational immersion with certified native teachers across 14+ languages.']);

        // 5. Full Catalog of Professional Certificates, Courses & Specializations
        $allPrograms = [
            // --- PROFESSIONAL CERTIFICATES ---
            [
                'slug' => 'google-ai-certificate',
                'title' => 'Google AI Certificate',
                'subtitle' => 'Learn how to use generative AI tools and prompt techniques to boost productivity and build modern AI-powered solutions.',
                'description' => 'Taught by Google AI experts. Gain practical hands-on experience using generative AI, understanding machine learning workflows, and applying AI responsibly in workplace scenarios. Includes official Google Verifiable Professional Certificate.',
                'category_id' => $catAI->id,
                'institution_id' => $google->id,
                'creator_id' => $googleTeam->id,
                'level' => 'all_levels',
                'type' => 'cohort_based',
                'price' => 49.00,
                'duration_hours' => 32,
                'current_enrollments' => 142000,
                'skills' => ['Generative AI', 'Prompt Engineering', 'AI Productivity', 'Google Gemini'],
                'thumb' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'google-cybersecurity-certificate',
                'title' => 'Google Cybersecurity Certificate',
                'subtitle' => 'Prepare for an entry-level cybersecurity analyst job in under 6 months. Master Python, Linux, SQL, and SIEM tools.',
                'description' => 'Designed by Google security professionals. Practice identifying cybersecurity risks, using packet sniffing tools, triaging security events with Chronicle and Splunk, and automating tasks with Python. Prepares you for the CompTIA Security+ examination.',
                'category_id' => $catSec->id,
                'institution_id' => $google->id,
                'creator_id' => $googleTeam->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 49.00,
                'duration_hours' => 120,
                'current_enrollments' => 285000,
                'skills' => ['Cybersecurity', 'Python', 'Linux', 'SQL', 'SIEM Tools', 'Incident Response'],
                'thumb' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'google-data-analytics-certificate',
                'title' => 'Google Data Analytics Certificate',
                'subtitle' => 'Gain in-demand skills in data cleaning, analysis, SQL, Tableau, R programming, and spreadsheet modeling.',
                'description' => 'Over 85% of graduates report a positive career outcome within 6 months. Learn step-by-step how to clean, organize, analyze, and visualize data to solve real business problems. Endorsed by top employers globally.',
                'category_id' => $catData->id,
                'institution_id' => $google->id,
                'creator_id' => $googleTeam->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 49.00,
                'duration_hours' => 180,
                'current_enrollments' => 520000,
                'skills' => ['Data Analytics', 'SQL', 'Spreadsheets', 'Tableau', 'R Programming'],
                'thumb' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'google-it-support-certificate',
                'title' => 'Google IT Support Certificate',
                'subtitle' => 'Start your career in tech with fundamental skills in computer networking, systems administration, and security.',
                'description' => 'No experience needed. Master day-to-day IT support tasks including computer assembly, wireless networking, customer service, and installing software across Windows and Linux environments. Prepares you for the CompTIA A+ certification.',
                'category_id' => $catSec->id,
                'institution_id' => $google->id,
                'creator_id' => $googleTeam->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 49.00,
                'duration_hours' => 100,
                'current_enrollments' => 410000,
                'skills' => ['IT Support', 'Computer Networking', 'Linux', 'Troubleshooting', 'System Administration'],
                'thumb' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'google-project-management-certificate',
                'title' => 'Google Project Management Certificate',
                'subtitle' => 'Master traditional project management and Agile/Scrum methodologies. Qualify for 100+ hours of CAPM/PMP project training.',
                'description' => 'Learn how to create project documentation, manage timelines, run sprint retrospectives in Asana and Jira, and communicate effectively with cross-functional stakeholders. Taught by senior Google project managers.',
                'category_id' => $catPM->id,
                'institution_id' => $google->id,
                'creator_id' => $googleTeam->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 49.00,
                'duration_hours' => 140,
                'current_enrollments' => 310000,
                'skills' => ['Project Management', 'Agile & Scrum', 'Risk Management', 'Jira & Asana', 'CAPM / PMP'],
                'thumb' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'google-ux-design-certificate',
                'title' => 'Google UX Design Certificate',
                'subtitle' => 'Learn design foundations, wireframing, user research, and build a professional UX portfolio in Figma and Adobe XD.',
                'description' => 'Follow the design sprint process from user empathetic research to high-fidelity clickable prototypes. Complete 3 full portfolio capstone projects ready for design job interviews.',
                'category_id' => $catMkt->id,
                'institution_id' => $google->id,
                'creator_id' => $googleTeam->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 49.00,
                'duration_hours' => 160,
                'current_enrollments' => 290000,
                'skills' => ['UX Design', 'Figma', 'User Research', 'Wireframing', 'Prototyping'],
                'thumb' => 'https://images.unsplash.com/photo-1581291518655-9523c932edcf?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'ibm-ai-engineering-certificate',
                'title' => 'IBM AI Engineering Certificate',
                'subtitle' => 'Master Machine Learning, Deep Learning, PyTorch, Keras, and Apache Spark to build production-scale neural networks.',
                'description' => 'Delivered by IBM engineers. Build scalable machine learning models and deep neural networks on enterprise GPU clusters. Earn industry-recognized credentials and digital badges from IBM.',
                'category_id' => $catAI->id,
                'institution_id' => $ibm->id,
                'creator_id' => $ibmTeam->id,
                'level' => 'intermediate',
                'type' => 'cohort_based',
                'price' => 59.00,
                'duration_hours' => 120,
                'current_enrollments' => 180000,
                'skills' => ['Artificial Intelligence (AI)', 'Machine Learning', 'PyTorch', 'Deep Learning', 'Apache Spark'],
                'thumb' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'ibm-ai-product-manager-certificate',
                'title' => 'IBM AI Product Manager Certificate',
                'subtitle' => 'Lead AI product teams from inception to deployment. Formulate AI strategy, data governance, and product roadmaps.',
                'description' => 'Learn how to evaluate market opportunities for machine learning, translate business objectives into algorithmic requirements, and manage AI product lifecycle metrics.',
                'category_id' => $catPM->id,
                'institution_id' => $ibm->id,
                'creator_id' => $ibmTeam->id,
                'level' => 'intermediate',
                'type' => 'cohort_based',
                'price' => 59.00,
                'duration_hours' => 60,
                'current_enrollments' => 75000,
                'skills' => ['Product Management', 'AI Strategy', 'Machine Learning', 'Data Governance'],
                'thumb' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'ibm-data-science-certificate',
                'title' => 'IBM Data Science Certificate',
                'subtitle' => 'Develop job-ready skills in Python, SQL, data visualization, predictive modeling, and machine learning with IBM Cloud.',
                'description' => 'Comprehensive 10-course program covering Python data science libraries (Pandas, NumPy, Matplotlib, Scikit-learn), relational SQL databases, and real-world project portfolios.',
                'category_id' => $catData->id,
                'institution_id' => $ibm->id,
                'creator_id' => $ibmTeam->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 59.00,
                'duration_hours' => 150,
                'current_enrollments' => 380000,
                'skills' => ['Data Science', 'Python', 'SQL', 'Data Analytics', 'Machine Learning'],
                'thumb' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'intuit-academy-bookkeeping-certificate',
                'title' => 'Intuit Academy Bookkeeping Certificate',
                'subtitle' => 'Become a certified bookkeeper. Master double-entry accounting, reconciling statements, and QuickBooks workflows.',
                'description' => 'Created in partnership with Intuit Academy. Learn foundational accounting concepts, general ledger posting, payroll, and prepare to earn the official Intuit Certified Bookkeeping professional badge.',
                'category_id' => $catAcct->id,
                'institution_id' => $intuit->id,
                'creator_id' => $sarahChen->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 45.00,
                'duration_hours' => 70,
                'current_enrollments' => 95000,
                'skills' => ['Accounting', 'Bookkeeping', 'Financial Statements', 'QuickBooks', 'Double-Entry'],
                'thumb' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&auto=format&fit=crop&q=80',
            ],

            // --- COURSES & SPECIALIZATIONS ---
            [
                'slug' => 'ai-essentials-specialization',
                'title' => 'AI Essentials Specialization',
                'subtitle' => 'Develop fundamental understanding of artificial intelligence, neural networks, ethics, and practical workplace adoption.',
                'description' => 'A curated foundational specialization covering AI terminologies, generative prompts, business transformation use cases, and responsible AI governance.',
                'category_id' => $catAI->id,
                'institution_id' => $deeplearningAi->id,
                'creator_id' => $andrewNg->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 39.00,
                'duration_hours' => 30,
                'current_enrollments' => 165000,
                'skills' => ['Artificial Intelligence (AI)', 'AI Strategy', 'Prompt Engineering'],
                'thumb' => 'https://images.unsplash.com/photo-1535378917042-10a22c95931a?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'ai-for-business-specialization',
                'title' => 'AI For Business Specialization',
                'subtitle' => 'Learn how to leverage AI, machine learning, and big data to transform enterprise decision-making and marketing.',
                'description' => 'Taught by leading business school faculty. Understand the financial and operational impact of AI, evaluate vendor solutions, and manage algorithmic risk in corporate organizations.',
                'category_id' => $catPM->id,
                'institution_id' => $stanford->id,
                'creator_id' => $sarahChen->id,
                'level' => 'all_levels',
                'type' => 'cohort_based',
                'price' => 79.00,
                'duration_hours' => 45,
                'current_enrollments' => 88000,
                'skills' => ['AI Strategy', 'Digital Marketing', 'Data Analytics', 'Project Management'],
                'thumb' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'ai-for-everyone-course',
                'title' => 'AI For Everyone Course',
                'subtitle' => 'Andrew Ng explains AI, machine learning, deep learning, and how non-technical leaders can harness their potential.',
                'description' => 'One of the most enrolled AI courses in global history. Taught by AI pioneer Andrew Ng. Understand what AI realistically can and cannot do, and how to spot opportunities to apply AI to problems in your own organization.',
                'category_id' => $catAI->id,
                'institution_id' => $deeplearningAi->id,
                'creator_id' => $andrewNg->id,
                'level' => 'beginner',
                'type' => 'self_paced',
                'price' => 19.99,
                'duration_hours' => 12,
                'current_enrollments' => 1250000,
                'skills' => ['Artificial Intelligence (AI)', 'Machine Learning', 'Data Strategy'],
                'thumb' => 'https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'ai-in-healthcare-specialization',
                'title' => 'AI in Healthcare Specialization',
                'subtitle' => 'Explore medical imaging, patient data analytics, diagnostic machine learning, and clinical genomics.',
                'description' => 'Learn how artificial intelligence is transforming clinical diagnostics, drug discovery, and healthcare operations. Designed for medical professionals and software engineers alike.',
                'category_id' => $catAI->id,
                'institution_id' => $stanford->id,
                'creator_id' => $sarahChen->id,
                'level' => 'intermediate',
                'type' => 'cohort_based',
                'price' => 79.00,
                'duration_hours' => 50,
                'current_enrollments' => 62000,
                'skills' => ['Artificial Intelligence (AI)', 'Healthcare Analytics', 'Deep Learning'],
                'thumb' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'deep-learning-specialization',
                'title' => 'Deep Learning Specialization',
                'subtitle' => 'Master Deep Learning with Andrew Ng: Neural Networks, CNNs, Sequence Models, and Transformers.',
                'description' => 'The world-famous 5-course sequence created by Andrew Ng and DeepLearning.AI. Implement neural networks from scratch in Python, build computer vision models with ConvNets, and NLP models with LSTMs and Transformers.',
                'category_id' => $catAI->id,
                'institution_id' => $deeplearningAi->id,
                'creator_id' => $andrewNg->id,
                'level' => 'intermediate',
                'type' => 'cohort_based',
                'price' => 69.00,
                'duration_hours' => 90,
                'current_enrollments' => 840000,
                'skills' => ['Deep Learning', 'Artificial Intelligence (AI)', 'Python', 'PyTorch', 'Neural Networks'],
                'thumb' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'excel-skills-for-business-specialization',
                'title' => 'Excel Skills for Business Specialization',
                'subtitle' => 'Master Microsoft Excel from essential formulas and charts to advanced pivot tables, macros, and financial modeling.',
                'description' => 'Gain confidence in managing large datasets, automated lookup functions, what-if scenarios, and professional dashboard presentations. Highly valued across finance, marketing, and HR.',
                'category_id' => $catExcel->id,
                'institution_id' => $stanford->id,
                'creator_id' => $sarahChen->id,
                'level' => 'all_levels',
                'type' => 'self_paced',
                'price' => 24.99,
                'duration_hours' => 40,
                'current_enrollments' => 450000,
                'skills' => ['Microsoft Excel', 'Data Analytics', 'Accounting', 'Financial Modeling'],
                'thumb' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'financial-markets-course',
                'title' => 'Financial Markets Course',
                'subtitle' => 'An overview of the ideas, methods, and institutions that permit human society to manage risks and foster enterprise.',
                'description' => 'Examine securities, stocks, bonds, insurance, derivatives, and banking systems. Learn how financial theory applies to modern financial management and wealth creation.',
                'category_id' => $catAcct->id,
                'institution_id' => $stanford->id,
                'creator_id' => $sarahChen->id,
                'level' => 'beginner',
                'type' => 'self_paced',
                'price' => 29.99,
                'duration_hours' => 34,
                'current_enrollments' => 670000,
                'skills' => ['Accounting', 'Financial Markets', 'Risk Management', 'Economics'],
                'thumb' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'machine-learning-specialization',
                'title' => 'Machine Learning Specialization',
                'subtitle' => 'Built by Andrew Ng and Stanford Online. Master fundamental ML algorithms, supervised, unsupervised, and recommender systems.',
                'description' => 'The definitive modern foundational ML course. Learn regression, classification, decision trees, neural networks, anomaly detection, and reinforcement learning in Python.',
                'category_id' => $catAI->id,
                'institution_id' => $stanford->id,
                'creator_id' => $andrewNg->id,
                'level' => 'beginner',
                'type' => 'cohort_based',
                'price' => 49.00,
                'duration_hours' => 65,
                'current_enrollments' => 990000,
                'skills' => ['Machine Learning', 'Artificial Intelligence (AI)', 'Python', 'Algorithms'],
                'thumb' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'prompt-engineering-for-chatgpt-course',
                'title' => 'Prompt Engineering for ChatGPT Course',
                'subtitle' => 'Learn how to be an expert prompt engineer. Tap into the full cognitive potential of Large Language Models.',
                'description' => 'Master few-shot prompting, persona patterns, template expansion, reasoning chains, and integration of ChatGPT APIs into automated business workflows.',
                'category_id' => $catAI->id,
                'institution_id' => $deeplearningAi->id,
                'creator_id' => $andrewNg->id,
                'level' => 'all_levels',
                'type' => 'self_paced',
                'price' => 14.99,
                'duration_hours' => 18,
                'current_enrollments' => 380000,
                'skills' => ['Artificial Intelligence (AI)', 'Prompt Engineering', 'Python'],
                'thumb' => 'https://images.unsplash.com/photo-1677442136019-21780efad99a?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'python-for-everybody-specialization',
                'title' => 'Python for Everybody Specialization',
                'subtitle' => 'Learn to program and analyze data with Python. Develop programs to gather, clean, analyze, and visualize data.',
                'description' => 'The world-renowned University program designed for absolute beginners. Master fundamental programming constructs, data structures, networked web APIs, and SQL databases in Python.',
                'category_id' => $catDev->id,
                'institution_id' => $mitx->id,
                'creator_id' => $sarahChen->id,
                'level' => 'beginner',
                'type' => 'self_paced',
                'price' => 29.99,
                'duration_hours' => 55,
                'current_enrollments' => 1100000,
                'skills' => ['Python', 'SQL', 'Data Analytics', 'Software Development'],
                'thumb' => 'https://images.unsplash.com/photo-1526379095098-d400fd0bf935?w=800&auto=format&fit=crop&q=80',
            ],

            // --- ONLINE GLOBAL CLASSROOM LIVE IMMERSION ---
            [
                'slug' => 'conversational-spanish-immersion-25min',
                'title' => 'Conversational Spanish: 25-Minute Live Native Immersion',
                'subtitle' => 'Break down language barriers with live, personalized 1-on-1 and small-group 25-minute sessions conducted by certified native teachers.',
                'description' => 'Online Global Classroom pedagogy focuses on natural conversational fluency, colloquial nuances, and cultural education rather than rote memorization.',
                'category_id' => $catLang->id,
                'institution_id' => $ogcInst->id,
                'creator_id' => $carlos->id,
                'level' => 'all_levels',
                'type' => 'live_virtual',
                'price' => 19.99,
                'duration_hours' => 12,
                'current_enrollments' => 18200,
                'skills' => ['Conversational Spanish', 'Oral Fluency', 'CEFR B2'],
                'thumb' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'slug' => 'amharic-language-heritage-immersion-25min',
                'title' => 'Amharic Language & Ethiopian Heritage: 25-Min Live Immersion',
                'subtitle' => 'Explore the richness of Amharic (አማርኛ) and Ge\'ez heritage with certified native Ethiopian instructors.',
                'description' => 'Practice conversational Amharic with native speakers from Addis Ababa. Includes the Ge\'ez Fidel alphabet, hospitality etiquette, and practical conversational fluency.',
                'category_id' => $catLang->id,
                'institution_id' => $ogcInst->id,
                'creator_id' => $selamawit->id,
                'level' => 'beginner',
                'type' => 'live_virtual',
                'price' => 18.50,
                'duration_hours' => 14,
                'current_enrollments' => 9420,
                'skills' => ['Amharic', 'Ge\'ez Script', 'Cultural Fluency'],
                'thumb' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800&auto=format&fit=crop&q=80',
            ]
        ];

        foreach ($allPrograms as $p) {
            $course = Course::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'subtitle' => $p['subtitle'],
                    'description' => $p['description'],
                    'category_id' => $p['category_id'],
                    'institution_id' => $p['institution_id'],
                    'creator_id' => $p['creator_id'],
                    'level' => $p['level'],
                    'type' => $p['type'],
                    'language' => 'en',
                    'price' => $p['price'],
                    'currency' => 'USD',
                    'is_free' => false,
                    'status' => 'active',
                    'state' => 'published',
                    'visibility' => 'public',
                    'duration_hours' => $p['duration_hours'],
                    'current_enrollments' => $p['current_enrollments'],
                    'objectives' => [
                        'Master job-ready skills certified by industry leaders and accredited institutions',
                        'Hands-on applied capstone projects, coding labs, or conversational immersion',
                        'Earn cryptographic verifiable credentials with SHA-256 tamper-proof ledger proof'
                    ]
                ]
            );

            // Create sample module & lessons
            $mod = CourseModule::firstOrCreate(
                ['course_id' => $course->id, 'slug' => 'foundations-and-core-skills'],
                [
                    'title' => 'Module 1: Professional Foundations & Core Concepts',
                    'description' => 'Comprehensive syllabus covering industry best practices, theory, and real-world tools.',
                    'order' => 1,
                    'is_published' => true
                ]
            );

            Lesson::firstOrCreate(
                ['module_id' => $mod->id, 'slug' => 'introduction-and-orientation'],
                [
                    'course_id' => $course->id,
                    'title' => 'Course Orientation & Essential Principles',
                    'description' => 'High-definition video lecture detailing foundational principles.',
                    'type' => 'video',
                    'external_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                    'duration_minutes' => 15,
                    'order' => 1,
                    'is_published' => true,
                    'is_free_preview' => true,
                    'content' => ['summary' => 'Introduction and key takeaways.']
                ]
            );

            Lesson::firstOrCreate(
                ['module_id' => $mod->id, 'slug' => 'applied-practical-lab'],
                [
                    'course_id' => $course->id,
                    'title' => 'Applied Practical Lab & Hands-On Scenarios',
                    'description' => 'Real-world problem solving and capstone exercise.',
                    'type' => 'video',
                    'external_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                    'duration_minutes' => 25,
                    'order' => 2,
                    'is_published' => true,
                    'is_free_preview' => false,
                    'content' => ['summary' => 'Deep dive into practical implementation.']
                ]
            );
        }

        // 6. Online Global Classrooms (Live 25-Min Immersion Sessions)
        $courseSpanish = Course::where('slug', 'conversational-spanish-immersion-25min')->first();
        $courseAmharic = Course::where('slug', 'amharic-language-heritage-immersion-25min')->first();

        $roomsData = [
            [
                'slug' => 'spanish-travel-daily-immersion',
                'course_id' => $courseSpanish?->id,
                'creator_id' => $carlos->id,
                'title' => 'Live 25-Min Conversational Spanish: Travel & Daily Situations',
                'description' => 'Small group immersion class with native instructor Carlos Méndez (Bogotá). Practice ordering in restaurants, asking directions, and social conversations.',
                'language' => 'Spanish (Español)',
                'duration' => 25,
                'room_id' => 'OGC-SPANISH-LIVE-101',
                'url' => 'https://meet.jit.si/gelme-spanish-travel-immersion-2026',
            ],
            [
                'slug' => 'amharic-culture-heritage-exchange',
                'course_id' => $courseAmharic?->id,
                'creator_id' => $selamawit->id,
                'title' => 'Global Classroom: Amharic & Ethiopian Heritage Immersion',
                'description' => 'Interactive cultural language exchange session with native speaker Selamawit Tadesse focusing on Ge\'ez alphabet, greetings, and hospitality phrases.',
                'language' => 'Amharic (አማርኛ)',
                'duration' => 25,
                'room_id' => 'OGC-AMHARIC-CULTURE-201',
                'url' => 'https://meet.jit.si/gelme-amharic-heritage-session-2026',
            ],
            [
                'slug' => 'english-executive-business-fluency',
                'course_id' => null,
                'creator_id' => $carlos->id,
                'title' => 'Executive British English: High-Stakes Presentations & Accent Coaching',
                'description' => 'Targeted 25-minute accent refinement and pitch delivery with certified London coach. Build instant confidence.',
                'language' => 'English (ESL / British Accent)',
                'duration' => 25,
                'room_id' => 'OGC-ENGLISH-EXEC-301',
                'url' => 'https://meet.jit.si/gelme-english-executive-2026',
            ],
            [
                'slug' => 'mandarin-beijing-daily-conversations',
                'course_id' => null,
                'creator_id' => $carlos->id,
                'title' => 'Mandarin Chinese: Daily Life & Beijing Street Conversations (普通话)',
                'description' => 'Practice the 4 tones naturally. Learn practical expressions for shopping, ordering authentic food, and chatting with locals.',
                'language' => 'Mandarin (中文)',
                'duration' => 25,
                'room_id' => 'OGC-MANDARIN-LIVE-401',
                'url' => 'https://meet.jit.si/gelme-mandarin-fluency-2026',
            ],
        ];

        foreach ($roomsData as $r) {
            VirtualClassroom::firstOrCreate(
                ['slug' => $r['slug']],
                [
                    'course_id' => $r['course_id'],
                    'creator_id' => $r['creator_id'],
                    'title' => $r['title'],
                    'description' => $r['description'],
                    'type' => 'live',
                    'status' => 'scheduled',
                    'scheduled_at' => now()->addMinutes(15),
                    'duration_minutes' => $r['duration'],
                    'provider' => 'jitsi',
                    'provider_room_id' => $r['room_id'],
                    'provider_room_url' => $r['url'],
                    'is_recorded' => true,
                    'is_chat_enabled' => true,
                    'is_whiteboard_enabled' => true,
                    'settings' => [
                        'immersion_language' => $r['language'],
                        'format' => '25-Min Native Immersion',
                        'max_capacity' => 12,
                        'current_participants' => rand(4, 9),
                    ]
                ]
            );
        }

        // 7. Verifiable Credentials & Student Enrollments
        $courseGoogleAI = Course::where('slug', 'google-ai-certificate')->first();
        $courseGoogleCyber = Course::where('slug', 'google-cybersecurity-certificate')->first();

        $enr1 = Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $courseGoogleAI->id],
            [
                'status' => 'completed',
                'enrollment_type' => 'self',
                'enrollment_date' => now()->subDays(15)->toDateString(),
                'completion_date' => now()->subDays(2)->toDateString(),
                'progress_percent' => 100.00,
                'grade' => 99.00,
                'completed_lessons_count' => 2,
                'total_lessons_count' => 2,
                'last_accessed_at' => now()
            ]
        );

        Credential::firstOrCreate(
            ['verification_code' => 'GELME-CERT-2026-X892K'],
            [
                'uuid' => (string) Str::uuid(),
                'issuer_id' => $google->id,
                'issuer_type' => Institution::class,
                'recipient_id' => $student->id,
                'recipient_type' => User::class,
                'course_id' => $courseGoogleAI->id,
                'enrollment_id' => $enr1->id,
                'type' => 'certificate',
                'category' => 'professional',
                'name' => 'Google AI Professional Certificate',
                'description' => 'Awarded for demonstrating verified mastery of generative AI, prompt engineering, and machine learning productivity tools.',
                'verification_url' => 'http://localhost:3000/verify/GELME-CERT-2026-X892K',
                'issued_at' => now()->subDays(2),
                'is_public' => true,
                'is_verifiable' => true,
                'criteria' => [
                    'skills' => ['Generative AI', 'Prompt Engineering', 'AI Productivity', 'Google Gemini'],
                    'grade' => '99.0%',
                    'credits' => '4.5 CEU',
                    'blockchain_hash' => hash('sha256', 'GELME-CERT-2026-X892K' . 'GoogleAI2026')
                ]
            ]
        );

        Credential::firstOrCreate(
            ['verification_code' => 'STANFORD-AI-2026-9901'],
            [
                'uuid' => (string) Str::uuid(),
                'issuer_id' => $deeplearningAi->id,
                'issuer_type' => Institution::class,
                'recipient_id' => $student->id,
                'recipient_type' => User::class,
                'course_id' => Course::where('slug', 'deep-learning-specialization')->first()?->id ?? $courseGoogleAI->id,
                'type' => 'specialization',
                'category' => 'academic',
                'name' => 'Deep Learning Specialization Master Credential',
                'description' => 'Honors credential awarded for proficiency in Neural Networks, CNNs, Sequence Models, and Transformers with Andrew Ng.',
                'verification_url' => 'http://localhost:3000/verify/STANFORD-AI-2026-9901',
                'issued_at' => now()->subDays(5),
                'is_public' => true,
                'is_verifiable' => true,
                'criteria' => [
                    'skills' => ['Deep Learning', 'PyTorch', 'Neural Networks', 'Andrew Ng Curriculum'],
                    'grade' => '99.2%',
                    'credits' => '6.0 CEU',
                    'blockchain_hash' => hash('sha256', 'STANFORD-AI-2026-9901' . 'DeepLearningAI2026')
                ]
            ]
        );

        Credential::firstOrCreate(
            ['verification_code' => 'OGC-SPANISH-2026-7741'],
            [
                'uuid' => (string) Str::uuid(),
                'issuer_id' => $ogcInst->id,
                'issuer_type' => Institution::class,
                'recipient_id' => $student->id,
                'recipient_type' => User::class,
                'course_id' => $courseSpanish?->id ?? $courseGoogleAI->id,
                'type' => 'language_proficiency',
                'category' => 'immersion',
                'name' => 'Online Global Classroom CEFR B2 Conversational Spanish Certificate',
                'description' => 'Verified certificate of oral fluency achieved through 25-minute live native speaker immersion sessions.',
                'verification_url' => 'http://localhost:3000/verify/OGC-SPANISH-2026-7741',
                'issued_at' => now()->subDays(10),
                'is_public' => true,
                'is_verifiable' => true,
                'criteria' => [
                    'skills' => ['Conversational Fluency', 'CEFR B2 Spoken Interaction', 'Cultural Idioms'],
                    'grade' => '96.0%',
                    'credits' => '2.5 CEU',
                    'blockchain_hash' => hash('sha256', 'OGC-SPANISH-2026-7741' . 'OGCSpanish2026')
                ]
            ]
        );
    }
}
