<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::redirect("/", "/articles");

Route::get("/login", [UserController::class,"loginView"])->name('login-view');
Route::get("/register", [UserController::class,"registerView"])->name('register-view');
Route::post("/login", [UserController::class,"login"])->name('login');
Route::post("/register", [UserController::class,"register"])->name('register');
Route::post("/logout", [UserController::class,"logout"])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::resources([
        "users"=>UserController::class,
        "articles"=>ArticleController::class,
    ]);
});
