<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\VideoController;

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

//auth
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//auth_user
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/forgot', [ForgotPasswordController::class, 'forgot']);
Route::post('/reset', [ResetPasswordController::class, 'reset']);

//admin_auth
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout']);

//user
Route::apiResource('/users',UserController::class);
Route::post('/users/search',[UserController::class, 'search']);

//video
Route::apiResource('video_category', VideoCategoryController::class);
Route::apiResource('videos', VideoController::class);


Route::apiResource('/tasks',TaskController::class);