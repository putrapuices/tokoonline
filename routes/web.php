<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* default home page
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::group(["prefix" => "latihan"], function () {
    Route::get("/kategori/all", [CategoriController::class, "index"]);
    Route::get("/kategori/search", [CategoriController::class, "search"]);
    Route::get("/kategori/{id}/delete", [CategoriController::class, "delete"]);
    Route::get("/kategori/{id}/restore", [CategoriController::class, "restore"]);
    Route::get("/kategori/{id}/permanent-delete", [CategoriController::class, "permanentDelete"]);
    Route::view("layouts", "child");
});

/*dua cara poenulisan route */
// Route::get('/ucapkan-salam', [App\Http\Controllers\SalamController::class, "beriSalam"]);
/*Or*/
Route::get('/ucapkan-salam', 'App\Http\Controllers\SalamController@beriSalam');


Auth::routes();

Route::match(["GET", "POST"], "/register", function () {
    return redirect("/login");
})->name("register");

Route::resource("users", UserController::class);
Route::resource('categories', CategoryController::class);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
