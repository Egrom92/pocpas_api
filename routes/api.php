<?php

use App\Http\Controllers\SubscriberController;
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

Route::get('/subscriber/{tg_id}/password', [SubscriberController::class, 'getPassword']);
Route::get('/subscriber/{tg_id}/all-passwords', [SubscriberController::class, 'getAllPassword']);
Route::delete('/subscriber/{tg_id}/password', [SubscriberController::class, 'deletePassword']);
Route::post('/subscriber/{tg_id}/password', [SubscriberController::class, 'addPassword']);
Route::patch('/subscriber/{tg_id}/password', [SubscriberController::class, 'editPassword']);

Route::get('/subscriber/{tg_id}/master-password/{master_password}', [SubscriberController::class, 'masterPasswordControl']);
Route::get('/subscriber/{tg_id}', [SubscriberController::class, 'index']);
Route::post('/subscriber', [SubscriberController::class, 'store']);

