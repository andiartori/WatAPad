<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Page\ArticlePageController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Home Page
|--------------------------------------------------------------------------
*/

Route::get('/', [ArticleController::class, 'getAll'])->name('home');

/*
|--------------------------------------------------------------------------
| Article Routes
|--------------------------------------------------------------------------
*/

// Halaman manage articles (harus sebelum {id})
Route::get('/articles/manage', [ArticlePageController::class, 'manage'])->name('articles.manage');


// Halaman create article
Route::get('/articles/create', [ArticlePageController::class, 'create'])->name('articles.create');
Route::post('/articles/create', [ArticlePageController::class, 'store'])->name('articles.store');

// Halaman edit article (Menampilkan form edit artikel berdasarkan ID)
Route::get('/articles/{id}/edit', [ArticlePageController::class, 'edit'])->where('id', '[0-9]+')->name('articles.edit');

// Update artikel (setelah form edit di-submit)
Route::post('/articles/{id}/update', [ArticlePageController::class, 'update'])->where('id', '[0-9]+')->name('articles.update');

// **Rute untuk menghapus artikel**
Route::delete('/articles/{id}/delete', [ArticlePageController::class, 'deleteFromBlade'])->name('articles.delete');

// Ambil semua articles (optional kalau mau ada halaman /articles)
Route::get('/articles', [ArticleController::class, 'getAll'])->name('articles.index');

// Ambil 1 artikel berdasarkan ID
Route::get('/articles/{id}', [ArticleController::class, 'getById'])->where('id', '[0-9]+')->name('articles.show');

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

// Halaman create category
Route::get('/categories/create', function () {
    if (!session()->has('auth_token')) {
        return redirect('/login')->with('error', 'Kamu harus login dulu.');
    }
    return view('categories.create');
})->name('categories.create');

// Simpan category baru
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

// Halaman edit semua category
Route::get('/categories', [CategoryController::class, 'editPage'])->name('categories.edit');

// Halaman form edit per category
Route::get('/categories/{id}/edit', [CategoryController::class, 'editForm'])->where('id', '[0-9]+')->name('categories.edit.form');

// Update category
Route::post('/categories/{id}/update', [CategoryController::class, 'updateFromBlade'])->where('id', '[0-9]+')->name('categories.update');

// Hapus category
Route::delete('/categories/{id}/delete', [CategoryController::class, 'deleteFromBlade'])->where('id', '[0-9]+')->name('categories.delete');

// Untuk cancel update category (kayaknya redundant, sudah ada /categories)
Route::get('/categories/edit', [CategoryController::class, 'editPage'])->name('categories.edit.page');
