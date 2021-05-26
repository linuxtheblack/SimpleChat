<?php

namespace App\Http\Controllers;

use App\Events\UserMessageSent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * TODO: Check if this works
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(string $id, Request $request)
    {
        $sender = Auth::user();
        $chat   = Chat::where('uuid', $id)->first();

        if (!$sender->chats->contains($chat->id)) {
            return ['code' => 401, 'msg' => 'Access denied'];
        }

        $msg          = new Message();
        $msg->message = $request->post('msg');
        $msg->user_id = $sender->id;
        $msg->chat_id = $chat->id;
        $msg->save();

        //Send to Pusher!
        UserMessageSent::dispatch(['msg' => $msg->message, 'from' => $sender->name, 'channel' => $id]);

        return ['code' => 200, 'msg' => 'Message sent'];

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message      $message
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
