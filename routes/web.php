<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
Auth::login(User::all()->first());
Route::get('/', function () {
    return view('welcome');
});


Route::get('/chats', [ChatController::class, 'index'])
     ->middleware(['auth'])
     ->name('chats');

Route::get('/chats/{uuid}', [ChatController::class, 'show'])
     ->middleware(['auth'])
     ->name('chatSingle');


Route::post('/chats/{uuid}', [MessageController::class, 'store'])
     ->middleware(['auth'])
     ->name('chatSingleStore');

require __DIR__ . '/auth.php';
