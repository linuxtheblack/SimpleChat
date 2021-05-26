<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chats = Auth::user()->chats()->with('users')->get();

        return view('chats', ['chats' => $chats]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     *
     * @return \Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */
    public function show(string $uuid)
    {
        $chat = Chat::firstWhere('uuid', $uuid);

        //checks if chat exists
        if ($chat === null) {
            return redirect('/chats');
        }

        $subs = $chat->users;

        //checks if user have access to the chat
        if (!$subs->find(Auth::id())) {
            return redirect('/chat');
        }

        return view('chat', ['messages' => [1, 2, 3, 4], 'user' => Auth::user(), 'id' => $uuid]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Chat         $chat
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Chat $chat
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
