<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoCommentController;
use App\Http\Controllers\ReVideoCommentController;

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
Route::put('/users/update_from_user/{user}',[UserController::class, 'updateFromUser']);
Route::post('/users/register_premium/{user}',[UserController::class, 'registerPremium']);
Route::post('/users/cancel_premium/{user}',[UserController::class, 'cancelPremium']);
Route::post('/users/search',[UserController::class, 'search']);

//video
Route::apiResource('video_categories', VideoCategoryController::class);
Route::post('video_categories/get_category', [VideoCategoryController::class, 'getCategory']);
Route::apiResource('videos', VideoController::class);
Route::post('/videos/search', [VideoController::class, 'search']);
Route::post('/videos/watch/{video}', [VideoController::class, 'watch']);
Route::post('/videos/download', [VideoController::class, 'download']);
Route::apiResource('video_comments', VideoCommentController::class);
Route::post('video_comments/get_comment', [VideoCommentController::class, 'getComment']);
Route::apiResource('re_video_comments', ReVideoCommentController::class);