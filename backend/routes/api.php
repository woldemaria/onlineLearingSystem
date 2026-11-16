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
    Route::get('/me', [AuthController::class, 'me']);
});

// Courses & Discovery (Udemy & Coursera)
Route::get('/courses', [CourseCatalogController::class, 'index']);
Route::get('/courses/{slug}', [CourseCatalogController::class, 'show']);
Route::get('/categories', [CourseCatalogController::class, 'categories']);
Route::get('/institutions', [CourseCatalogController::class, 'institutions']);

// Learning, Progress & Coursera Financial Aid
Route::post('/courses/{id}/enroll', [LearningController::class, 'enroll']);
Route::get('/my-learning', [LearningController::class, 'myCourses']);
Route::post('/lessons/{id}/complete', [LearningController::class, 'completeLesson']);
Route::post('/financial-aid', [LearningController::class, 'financialAid']);
Route::post('/assignments/submit', [LearningController::class, 'submitAssignment']);

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

// Instructor Studio
Route::get('/instructor/stats', [InstructorController::class, 'stats']);
