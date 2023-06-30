<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/** tickets */


/** create */
Route::post('/tickets', [TicketController::class, 'store']);

/** read */
Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);
Route::get('/tickets/search/{search}', [TicketController::class, 'search']);

/** update */
Route::put('/tickets/{id}', [TicketController::class, 'update']);

/** delete */
Route::delete('/tickets/{id}', [TicketController::class, 'delete']);









/** users */


/** create */
Route::post('/users', [UserController::class, 'store']);

/** read */
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/search/{search}', [UserController::class, 'search']);

/** update */
Route::put('/users/{id}', [UserController::class, 'update']);

/** delete */
Route::delete('/users/{id}', [UserController::class, 'delete']);
