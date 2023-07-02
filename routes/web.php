<?php

use App\Http\Controllers\LoginController;
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





Route::post('/login', [LoginController::class, 'authenticate']);


Route::middleware(['auth'])->group(function () {
    /** tickets */


    // CREATE
    Route::post('/tickets', [TicketController::class, 'store']);

    // READ
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::get('/tickets/search/{search}', [TicketController::class, 'search']);

    // UPDATE
    Route::put('/tickets/{id}', [TicketController::class, 'update']);

    // DELETE
    Route::delete('/tickets/{id}', [TicketController::class, 'delete']);









    /** users */


    // CREATE
    Route::post('/users', [UserController::class, 'store']);

    // READ
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/search/{search}', [UserController::class, 'search']);

    // UPDATE
    Route::put('/users/{id}', [UserController::class, 'update']);

    // DELETE
    Route::delete('/users/{id}', [UserController::class, 'delete']);
});
