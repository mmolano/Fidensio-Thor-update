<?php

use App\Http\Controllers\PressingController;
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

/*TODO: mettre une route de login, un middleware et la route pour l'affichage du tableau */

Route::get('/', [PressingController::class, 'index']);
