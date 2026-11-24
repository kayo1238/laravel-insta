<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    private $message;
    private $user;

    public function __construct(Message $message, User $user) {
        $this->message = $message;
        $this->user    = $user; 
    }

    // DM . chat list
    public function show($user_id) {
        $authUser = Auth::user();

        // follow中のuser listを取得
        $followingUsers = $authUser->following()->get();
        // chat相手のuser info
        $receiver = $this->user->findOrFail($user_id);

        // 相手から自分宛の未読message(read_atがnull)を既読にする
        $this->message
            ->where('sender_id', $user_id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

            // 自分→相手　or　相手→自分 messageを古い順に
            $message = $this->message
                ->where(function($query) use ($user_id) {
                    $query->where('sender_id', Auth::id())
                          ->where('receiver_id', $user_id);
                })
                ->orWhere(function($query) use ($user_id) {
                    $query->where('sender_id', $user_id)
                          ->where('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')->get();

                return view('index', compact('followingUsers', 'messages', 'receiver'));
    }

    // send a message
    public function store(Request $request, $user_id) {
        $request->validate([                                // varidation
            'body' => 'required|string|max:1000',
        ]);

       $this->message->create([
        'sender_id'   => Auth::id(),
        'receiver_id' => $user_id,
        'body'        => $request->body,
       ]);

        return redirect()->route('message.show', $user_id);

    }
   
}
