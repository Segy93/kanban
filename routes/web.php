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
Route::post('/tickets', [TicketController::class, 'store'])->middleware('auth');

/** read */
Route::get('/tickets', [TicketController::class, 'index'])->middleware('auth');
Route::get('/tickets/{id}', [TicketController::class, 'show'])->middleware('auth');

/** update */
Route::put('/tickets/{id}', [TicketController::class, 'update'])->middleware('auth');

/** delete */
Route::delete('/tickets/{id}', [TicketController::class, 'delete'])->middleware('auth');









/** users */


/** create */
Route::post('/users', [UserController::class, 'store'])->middleware('auth');

/** read */
Route::get('/users', [UserController::class, 'index'])->middleware('auth');
Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth');

/** update */
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth');

/** delete */
Route::delete('/users/{id}', [UserController::class, 'delete'])->middleware('auth');
