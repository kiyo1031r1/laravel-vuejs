<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

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
Route::get('/login/{provider}/callback',[LoginController::class, 'redirectToProvider']);
Route::get('/login/{provider}/callback',[LoginController::class, 'handleProviderCallback']);

Route::get('/reset_password/{token}', [ResetPasswordController::class, 'checkToken']);

Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');
