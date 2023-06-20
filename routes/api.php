<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\TestEventController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route::middleware(['auth:api'])->group(function () {
//     Route::get('/user/{id}', [UserController::class, 'index'])->name('user.index');
//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//     Route::get('/user/all', [UserController::class, 'user_all'])->name('user_all');
//     Route::post('/user/multi', [UserController::class, 'createMultiUser'])->name('createMultiUser');
//     Route::post('/user', [UserController::class, 'create'])->name('users.create');
//     Route::delete('/user/{id}', [UserController::class, 'delete'])->name('users.delete');
//     Route::post('/user/{id}', [UserController::class, 'update'])->name('users.update');
//     Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
// });

Route::group(['middleware' => ['auth:api',]], function () {
    Route::get('/user/{id}', [UserController::class, 'index'])->name('user.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user/all', [UserController::class, 'user_all'])->name('user_all');
    Route::post('/user/multi', [UserController::class, 'createMultiUser'])->name('createMultiUser');
    Route::post('/user', [UserController::class, 'create'])->name('users.create');
    Route::delete('/user/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::post('/user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
    Route::get('/test-event', [TestEventController::class, 'testEvent'])->name('testEvent');
    Route::post('/test-sendmail', [SendMailController::class, 'SendMail'])->name('SendMail');
});

// Route::middleware('auth:api')->get('/test', function (Request $request) {
//     return $request->user();
// });
