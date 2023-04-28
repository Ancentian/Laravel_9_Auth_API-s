<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Route::post('/logout', function () {
//     return view('logout');
// })->name('logout')->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    //Route::get('/blogDetails', 'blogDetails')->name('blogDetails');
});

