<?php

use App\Models\Chat;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/404', function () {
    return "Chat not found";
});

Route::get('/dashboard', function () {
    return view('dashboard', ['user' => Auth::user()]);
})->middleware(['auth'])->name('dashboard');

Route::get('/{uuid}', function (String $uuid, Request $request) {
    $chat = Chat::firstWhere('uuid', $uuid);
    if ($chat === null)
        return redirect('/dashboard');
    $subs = $chat->users();

    if (!$subs->find(Auth::id()))
        $subs->attach(Auth::id());

    $chat->users()->each(function ($u){
        dump($u->name);
    });

    dd("STOP");
    return $chat->users();
});

require __DIR__.'/auth.php';
