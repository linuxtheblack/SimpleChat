<?php

use App\Events\UserMessageSent;
use App\Http\Controllers\ChatController;
use App\Models\Chat;
use App\Models\Message;
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


Route::get('/chat', [ChatController::class, 'index'])
    ->middleware(['auth'])
    ->name('chat');

Route::get('/chat/{uuid}', function (String $uuid, Request $request) {
    $chat = Chat::firstWhere('uuid', $uuid);

    //checks if chat exists
    if ($chat === null)
        return redirect('/chat');

    $subs = $chat->users;

    //checks if user have access to the chat
    if (!$subs->find(Auth::id()))
        return redirect('/chat');

    return view('chat', ['messages' => [1,2,3,4], 'user' => Auth::user(), 'id' => $uuid]);
});

Route::post('/chat/{uuid}', function (String $id, Request $request){
    $sender = Auth::user();
    $chat   = Chat::where('uuid', $id)->first();

    if (!$sender->chats->contains($chat->id))
        return ['code' => 401, 'msg' => 'Access denied'];

    $msg = new Message();
    $msg->message = $request->post('msg');
    $msg->user_id = $sender->id;
    $msg->chat_id = $chat->id;
    $msg->save();

    //Send to Pusher!
    UserMessageSent::dispatch(['msg' => $msg->message, 'from' => $sender->name]);

    return ['code' => 200, 'msg' => 'Message sent'];
})->middleware(['auth']);

require __DIR__.'/auth.php';
