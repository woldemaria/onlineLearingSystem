<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseCatalogController;
use App\Http\Controllers\Api\LearningController;
use App\Http\Controllers\Api\GlobalClassroomController;
use App\Http\Controllers\Api\CredentialController;
use App\Http\Controllers\Api\AiTutorController;
use App\Http\Controllers\Api\InstructorController;

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
});

// Online Global Classrooms (Live 25-Min Immersion, Packages, & Placement Test)
Route::get('/classrooms', [GlobalClassroomController::class, 'index']);
Route::get('/classrooms/{slug}', [GlobalClassroomController::class, 'show']);
Route::post('/classrooms/book', [GlobalClassroomController::class, 'book']);
Route::get('/packages', [GlobalClassroomController::class, 'packages']);
Route::post('/placement-test', [GlobalClassroomController::class, 'placementTest']);

// Credentials & Certificates (edX & Coursera)
Route::get('/certificates/{code}', [CredentialController::class, 'verify']);

// Generative AI Study Companion
Route::post('/ai/tutor', [AiTutorController::class, 'ask']);

// Instructor Studio & Uploads (Authorized for instructors & admins)
Route::prefix('instructor')->group(function () {
    Route::get('/stats', [InstructorController::class, 'stats']);
    Route::get('/courses', [InstructorController::class, 'courses']);

    Route::middleware(['api.auth', 'role:instructor,admin'])->group(function () {
        Route::post('/upload', [InstructorController::class, 'uploadMedia']);
        Route::post('/courses', [InstructorController::class, 'storeCourse']);
    });
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
