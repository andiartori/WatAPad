<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;

// Routes untuk login, register, dan logout
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



Route::get('/categories', [CategoryController::class, 'getAll']);
Route::get('/categories/{id}', [CategoryController::class, 'getById']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
});
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'delete']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/articles', [ArticleController::class, 'store']); // Membuat artikel baru
    Route::put('/articles/{id}', [ArticleController::class, 'update']); // Memperbarui artikel
    Route::delete('/articles/{id}', [ArticleController::class, 'delete']); // Menghapus artikel
});

Route::get('/articles', [ArticleController::class, 'getAll']); // Mendapatkan semua artikel
Route::get('/articles/{id}', [ArticleController::class, 'getById']); // Mendapatkan artikel berdasarkan ID
Route::get('/articles/category/{categoryId}', [ArticleController::class, 'getByCategoryId']);
