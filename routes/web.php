<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
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
Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
Route::get('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
Route::delete('/categories/{category}/delete-permanent', [CategoryController::class, 'deletePermanent'])->name('categories.delete-permanent');
Route::resource('categories', CategoryController::class);
Route::get('/ajax/categories/search', [CategoryController::class, 'ajaxSearch']);
Route::get('/books/trash', [BookController::class, 'trash'])->name('books.trash');
Route::post('/books/{book}/restore', [BookController::class, 'restore'])->name('books.restore');
Route::delete('/books/{id}/delete-permanent', [BookController::class, 'deletePermanent'])->name('books.delete-permanent');
Route::resource('books', BookController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
