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

Route::post('/subscriber/{tg_id}/add', [SubscriberController::class, 'addPassword']);
Route::get('/subscriber/{tg_id}/{master_password}', [SubscriberController::class, 'masterPasswordControl']);
Route::get('/subscriber/{tg_id}', [SubscriberController::class, 'index']);
Route::post('/subscriber', [SubscriberController::class, 'store']);

