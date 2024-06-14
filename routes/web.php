<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, "showHomepage"])->name('home')->middleware('auth');
Route::post('/register', [UserController::class, "register"])->name('register')->middleware('guest');
Route::get('/login', [UserController::class, "showLoginForm"])->name('login.post')->middleware('guest');
Route::post('/login', [UserController::class, "login"])->name('login')->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])->name('logout.post')->middleware('auth');
Route::get('/create-post', [PostController::class, "showCreateForm"])->name('create.post')->middleware('auth');
Route::post('/create-post', [PostController::class, "storeNewPost"])->name('store.post')->middleware('auth');
Route::get('/post/{post}', [PostController::class, "viewSinglePost"])->name('show.single.post')->middleware('auth');
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->name('show.profile')->middleware('auth');
Route::delete('/post/{post}', [PostController::class, "delete"])->name('delete.post')->middleware('auth');
