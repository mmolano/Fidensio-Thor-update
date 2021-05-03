<?php

use App\Facades\Mailjet;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PressingController;
use App\Http\Middleware\SessionAuth;
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

//TODO: remove ça

Route::get('email-test', function(){

    $details = [
        'email' => 'miguel@fidensio.com',
        'name' => 'Miguel'
    ];

    dd(Mailjet::sendWithTemplate($details, 'payment_refused', 'hi'));

    dd('done');

});

Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::group([
    'middleware' => SessionAuth::class
], function () {
    Route::get('/', [PressingController::class, 'index']);
    Route::post('/', [PressingController::class, 'commitStatus']);
    Route::post('/update', [PressingController::class, 'commitStatus']);
    Route::post('/processing', [PressingController::class, 'commitStatus']);

    Route::post('/pay/order', [PressingController::class, 'processPayment']);
    Route::post('/rePay/order', [PressingController::class, 'reHandlePayment']);

    Route::get('/getData', [PressingController::class, 'getOrders']);
    Route::get('/getProduct', [PressingController::class, 'getProducts']);
});
