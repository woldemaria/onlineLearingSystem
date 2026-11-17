<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseCatalogController;
use App\Http\Controllers\Api\LearningController;
use App\Http\Controllers\Api\GlobalClassroomController;
use App\Http\Controllers\Api\CredentialController;
use App\Http\Controllers\Api\AiTutorController;
use App\Http\Controllers\Api\InstructorController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\MediaStreamController;
use App\Http\Controllers\Api\NotificationController;

// Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('api.auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Courses & Discovery (Udemy & Coursera)
Route::get('/courses', [CourseCatalogController::class, 'index']);
Route::get('/courses/{slug}', [CourseCatalogController::class, 'show']);
Route::get('/categories', [CourseCatalogController::class, 'categories']);
Route::get('/institutions', [CourseCatalogController::class, 'institutions']);

// Learning, Progress & Coursera Financial Aid
Route::middleware('api.auth')->group(function () {
    Route::post('/courses/{id}/enroll', [LearningController::class, 'enroll']);
    Route::get('/my-learning', [LearningController::class, 'myCourses']);
    Route::post('/lessons/{id}/complete', [LearningController::class, 'completeLesson']);
    Route::post('/financial-aid', [LearningController::class, 'financialAid']);
    Route::post('/assignments/submit', [LearningController::class, 'submitAssignment']);
    Route::post('/courses/{slug}/reviews', [CourseCatalogController::class, 'addReview']);
});

// Online Global Classrooms (Live 25-Min Immersion, Packages, & Placement Test)
Route::get('/classrooms', [GlobalClassroomController::class, 'index']);
Route::get('/classrooms/{slug}', [GlobalClassroomController::class, 'show']);
Route::post('/classrooms/book', [GlobalClassroomController::class, 'book']);
Route::get('/packages', [GlobalClassroomController::class, 'packages']);
Route::post('/placement-test', [GlobalClassroomController::class, 'placementTest']);

// Credentials & Certificates (edX & Coursera)
Route::get('/certificates/{code}', [CredentialController::class, 'verify']);

// Generative AI Study Companion & Quiz Generation
Route::post('/ai/tutor', [AiTutorController::class, 'ask']);
Route::post('/ai/quiz-generate', [AiTutorController::class, 'generateQuiz']);
Route::post('/ai/summarize', [AiTutorController::class, 'summarize']);

// Notifications Hub
Route::get('/notifications', [NotificationController::class, 'index']);
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

// Media Streaming (RFC 7233 partial content chunked streaming for video timeline scrubbing)
Route::get('/media/stream/{path}', [MediaStreamController::class, 'stream'])->where('path', '.*');

// Instructor Studio & Uploads (Authorized for instructors & admins)
Route::prefix('instructor')->group(function () {
    Route::get('/stats', [InstructorController::class, 'stats']);
    Route::get('/courses', [InstructorController::class, 'courses']);

    Route::middleware(['api.auth', 'role:instructor,admin'])->group(function () {
        Route::post('/upload', [InstructorController::class, 'uploadMedia']);
        Route::post('/courses', [InstructorController::class, 'storeCourse']);
        Route::post('/courses/{id}/curriculum', [InstructorController::class, 'saveCurriculum']);
    });
});

// Admin User & Registration Management (Authorized for admins only)
Route::prefix('admin')->middleware(['api.auth', 'role:admin'])->group(function () {
    Route::get('/stats', [AdminUserController::class, 'stats']);
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/export', [AdminUserController::class, 'exportCsv']);
    Route::patch('/users/{id}/role', [AdminUserController::class, 'updateRole']);
    Route::patch('/users/{id}/status', [AdminUserController::class, 'updateStatus']);
    Route::patch('/users/{id}/verify', [AdminUserController::class, 'verifyInstructor']);
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
});

// System Health & Telemetry
Route::get('/health', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Throwable $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'healthy',
        'service' => 'GELME Super Platform API',
        'timestamp' => now()->toIso8601String(),
        'database' => $dbStatus,
        'version' => '1.0.0',
    ]);
});
