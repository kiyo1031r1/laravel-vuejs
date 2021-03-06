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
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VideoEvaluationController;

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
Route::post('users/exist', [UserController::class, 'exist']);
Route::put('/users/update_from_user/{user}',[UserController::class, 'updateFromUser']);
Route::post('/users/search',[UserController::class, 'search']);

//video
Route::apiResource('video_categories', VideoCategoryController::class);
Route::post('video_categories/exist', [VideoCategoryController::class, 'exist']);
Route::apiResource('videos', VideoController::class);
Route::post('/videos/search', [VideoController::class, 'search']);
Route::post('/videos/exist', [VideoController::class, 'exist']);
Route::post('/videos/watch/{video}', [VideoController::class, 'watch']);
Route::post('/videos/download', [VideoController::class, 'download']);
Route::get('video_evaluations/get_evaluation/{video}', [VideoEvaluationController::class, 'getEvaluation']);
Route::post('video_evaluations/evaluate/{video}', [VideoEvaluationController::class, 'evaluate']);
Route::get('video_evaluations/is_evaluate/{video}', [VideoEvaluationController::class, 'isEvaluate']);
Route::put('video_evaluations/{video}', [VideoEvaluationController::class, 'update']);
Route::apiResource('video_comments', VideoCommentController::class);
Route::post('video_comments/get_comment', [VideoCommentController::class, 'getComment']);
Route::apiResource('re_video_comments', ReVideoCommentController::class);

//subscription
Route::get('subscription', [SubscriptionController::class, 'index']);
Route::get('subscription/get_status', [SubscriptionController::class, 'getStatus']);
Route::post('subscription/subscribe', [SubscriptionController::class, 'subscribe']);
Route::post('subscription/edit_card', [SubscriptionController::class, 'editCard']);
Route::post('subscription/cancel', [SubscriptionController::class, 'cancel']);
Route::post('subscription/cancel_now', [SubscriptionController::class, 'cancelNow']);
Route::get('subscription/get_next_update', [SubscriptionController::class, 'getNextUpdate']);
Route::get('subscription/get_card_information', [SubscriptionController::class, 'getCardInformation']);