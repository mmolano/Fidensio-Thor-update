<?php

use App\Http\Controllers\PressingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

/*TODO: mettre une route de login, un middleware et la route pour l'affichage du tableau */

Route::get('/y',
    function () {
        flash('Welcome to expertphp.in!');
        return view('Pressing/test');
    });

Route::get('/', [PressingController::class, 'index']);

Route::post('/', [PressingController::class, 'commitStatus']);
Route::post('/taken', [PressingController::class, 'commitStatus']);
Route::post('/processing', [PressingController::class, 'commitStatus']);
