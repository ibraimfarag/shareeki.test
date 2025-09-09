<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;


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


Route::post('/payment', [PageController::class, 'payment'])->name('Payment');
Route::any('/success', [PageController::class, 'success']);
Route::any('/error', [PageController::class, 'paymentError']);
// Route::post('/webhook/payment', [PageController::class, 'paymentWebhook']);
Route::post('/payment-status', [PageController::class, 'paymentWebhook']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


