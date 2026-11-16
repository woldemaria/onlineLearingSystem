# Software Requirements Specification (SRS) & Architecture Blueprint
## GELME — Global Education & Learning Management Ecosystem

**System Name:** GELME Super Platform  
**Target Reference Platforms:** [Udemy](https://www.udemy.com/), [Coursera](https://www.coursera.org/), [edX](https://www.edx.org/), [Online Global Classroom](https://www.onlineglobalclassroom.com/)  
**Version:** 2.0 (Enterprise Production Baseline)  
**Architecture:** Decoupled Multi-Tenant SPA (Vue 3 + Vite + Tailwind CSS + Pinia) & Scalable REST Backend (Laravel 11 + SQLite/PostgreSQL)  

---

## 1. Executive Summary & Vision

The **Global Education & Learning Management Ecosystem (GELME)** combines the strengths of the world's leading online education paradigms into a unified, high-performance ecosystem:

1. **Udemy's On-Demand Marketplace Model**: Self-paced video courses, practical developer bootcamps, lifetime access, ratings & reviews, student-instructor discussion boards, and instant certificates of completion.
2. **Coursera's Academic & Institutional Rigor**: University partnerships (Stanford Online, MITx), multi-course accredited specializations, structured rubrics, and formal grade transcripts.
3. **edX's Micro-Credentials & Cryptographic Integrity**: MicroMasters, verifiable certificates backed by SHA-256 tamper-proof cryptographic hashes, and public instant verification endpoints.
4. **Online Global Classroom's Native Live Immersion**: High-intensity 25-minute interactive 1-on-1 and small-group video classrooms conducted by certified native instructors across 14+ world languages (Spanish, English/ESL, Amharic, Mandarin, French, Japanese, German), powered by embedded WebRTC / Jitsi Meet.
5. **Generative AI Study Companion**: Floating, lesson-aware AI tutor providing instant conceptual explanations, code debugging, foreign language conversational practice, and dynamic quiz generation.

---

## 2. Functional Requirements Breakdown

### 2.1 Course Catalog, Discovery & Marketplace (Udemy & edX)
- **Multi-Faceted Search & Filtering**: Filter by category, difficulty level (Beginner, Intermediate, Advanced, All Levels), delivery format (Self-Paced, Cohort Degree, Live 25-Min Immersion), language, duration, and price.
- **Syllabus & Curriculum Accordion**: Dynamic module and lesson hierarchy with video duration, preview clips, and downloadable coding assets.
- **Student Reviews & Social Proof**: 5-star rating breakdown, verified student reviews, and total enrollment counts.

### 2.2 Immersive Learning Portal & Video Player (Udemy & Coursera)
- **HD Video Streaming**: Native video player with playback speeds, progress tracking, and auto-next lesson transitions.
- **Interactive Drawer**: Real-time playlist navigation showing completed checks (`✓`) and remaining lessons.
- **In-Lesson Study Tabs**: Overview & objectives, personal note-taking textarea, and downloadable lecture slides/source code.
- **Automated Completion Tracking**: Real-time progress percentage recalculation upon completing lessons.

### 2.3 Online Global Classroom (Live 25-Minute Native Immersion)
- **Native-Speaker Tutoring**: 25-minute targeted conversational lessons focused on spoken fluency and cultural nuances rather than rote grammar.
- **14+ Language Coverage**: Spanish, Amharic, English ESL, Mandarin, Japanese, German, French, Portuguese, Arabic, and more.
- **Embedded WebRTC / Jitsi Meet**: Zero-plugin video conferencing with screen sharing, participant rosters, in-session chat, and shared vocabulary notes.
- **Booking & Capacity Management**: 1-on-1 and small group caps (8-12 students) with automated calendar slot reservation.

### 2.4 Cryptographic Credential & Verification Ledger (edX & Coursera)
- **Unique Verification Code**: Public code lookup (e.g. `GELME-CERT-2026-X892K`).
- **Cryptographic Proof**: SHA-256 blockchain-style hash verifying recipient name, issuing university/partner, grade percentage, CEU credits, and issuance timestamp.
- **Print & Share**: Official seal of accreditation, printable certificate layout, and social share links.

### 2.5 Generative AI Study Buddy
- **Floating Interactive Assistant**: Available across all course views and dashboard pages.
- **Contextual Understanding**: Pre-configured quick prompts for Vue 3 reactivity, Laravel 11 REST APIs, foreign language practice, and credential verification.

---

## 3. System Architecture & Database Design

### 3.1 Relational Database Foundation (75 Tables)
- **Identity & Tenancy**: `users`, `user_profiles`, `organizations`, `institutions`, `campuses`, `faculties`, `departments`.
- **Curriculum & Pedagogy**: `courses`, `course_categories`, `course_modules`, `lessons`, `learning_activities`, `academic_programs`, `academic_years`, `semesters`.
- **Assessments & Evaluation**: `exams`, `question_banks`, `questions`, `exam_questions`, `exam_attempts`, `assignments`, `assignment_submissions`, `grades`, `grading_rubrics`.
- **Synchronous Virtual Classrooms**: `virtual_classrooms`, `classroom_participants`, `attendance_sessions`, `attendance_records`.
- **Credentials & Badges**: `credentials`, `credential_verifications`, `achievements`, `user_achievements`.
- **Commerce & Licensing**: `payments`, `orders`, `order_items`, `products`, `subscriptions`, `institution_licenses`, `instructor_earnings`, `payouts`, `refunds`.
- **Auditing & Governance**: `audit_logs`, `security_events`, `activity_events`, `user_sessions`, `user_devices`, `consent_records`, `guardian_relationships`.

### 3.2 RESTful API Contracts
| Endpoint | Method | Description |
|---|---|---|
| `/api/courses` | GET | Filterable catalog with search, category, level, and pagination |
| `/api/courses/{slug}` | GET | Course details, syllabus modules, lessons, reviews, and instructor |
| `/api/courses/{id}/enroll` | POST | Enroll authenticated student and initialize progress ledger |
| `/api/my-learning` | GET | Student dashboard courses, completion percentages, current lesson |
| `/api/lessons/{id}/complete` | POST | Mark lesson complete, update progress, auto-generate certificate at 100% |
| `/api/classrooms` | GET | List scheduled Online Global Classroom live sessions and language slots |
| `/api/classrooms/{slug}` | GET | Single classroom room config, Jitsi WebRTC domain and room token |
| `/api/classrooms/book` | POST | Reserve a seat in a 25-minute live immersion class |
| `/api/certificates/{code}` | GET | Public cryptographic verification of credentials |
| `/api/ai/tutor` | POST | Generative AI study companion question answering |
| `/api/instructor/stats` | GET | Instructor studio metrics, active courses, and earnings |

---

## 4. Frontend Technology Stack & UI Architecture

- **Framework**: Vue 3 (Composition API `<script setup>`)
- **State Management**: Pinia Store modules (`useAuthStore`, `useCourseStore`, `useClassroomStore`)
- **Styling**: Tailwind CSS with custom palette matching Udemy purple (`#a435f0`), Coursera blue (`#0056d2`), and Online Global Classroom emerald (`#059669`)
- **Routing**: Vue Router 4 (HTML5 history mode)
- **Live Classroom Engine**: Jitsi Meet WebRTC API integration with pre-join automation
- **Build System**: Vite 6.4 with optimized code-splitting and asset bundling
