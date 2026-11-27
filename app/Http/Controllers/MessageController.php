<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\Post;

class MessageController extends Controller
{
    private $message;
    private $user;

    public function __construct(Message $message, User $user)
    {
        $this->message = $message;
        $this->user    = $user;
    }

    // DM . chat list
    public function show($user_id)
    {
        $authUser = Auth::user();

        // follow中のuser listを取得
        $followingIds = $authUser->following()->pluck('following_id');
        $followingUsers = $this->user->whereIn('id', $followingIds)->get();

        // 未読件数取得
        foreach ($followingUsers as $followUser) {
            $followUser->unread_count = $this->message
                ->where('sender_id', $followUser->id)
                ->where('receiver_id', $authUser->id)
                ->whereNull('read_at')
                ->count();
        }

        // chat相手のuser info
        $receiver = $this->user->findOrFail($user_id);

        // 相手から自分宛の未読message(read_atがnull)を既読にする
        $this->message
            ->where('sender_id', $user_id)
            ->where('receiver_id', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // 自分→相手　or　相手→自分 messageを古い順に
        $messages = $this->message
            ->where(function ($query) use ($authUser, $user_id) {
                $query->where('sender_id', $authUser->id)
                    ->where('receiver_id', $user_id);
            })
            ->orWhere(function ($query) use ($authUser, $user_id) {
                $query->where('sender_id', $user_id)
                    ->where('receiver_id', $authUser->id);
            })
            ->orderBy('created_at', 'asc')->get();

        // HomeControllerと同様に$home_postsを用意
        $all_posts = Post::latest()->get();
        $home_posts = [];
        foreach ($all_posts as $post) {
            if (($post->user && $post->user->isFollowed()) || ($post->user && $post->user->id === $authUser->id)) {
                $home_posts[] = $post;
            }
        }

        $suggested_users = [];

        return view('users.home', compact('followingUsers', 'messages', 'receiver', 'home_posts', 'suggested_users'));
    }

    // send a message
    public function store(Request $request, $user_id)
    {
        $request->validate([                                // varidation
            'body' => 'required|string|max:1000',
        ]);

        // 相手userが存在するか確認(存在しなければ404)
        $receiver = $this->user->findOrFail($user_id);

        //自分自身への送信を防ぐ
        if ((int) $receiver->id === (int) Auth::id()) {
            return back()->withErrors(['body' => "You can not send it to yourself."]);
        }

        $this->message->create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user_id,
            'body'        => $request->body,
        ]);

        return redirect()->route('messages.show', $user_id);
    }

        // public function index() {
        //     $authUser = Auth::user();

        //     $followingIds = $authUser->following()->pluck('following_id');
        //     $followingUsers = $this->user->whereIn('id', $followingIds)->get();

        //     foreach($followingUsers as $followUser) {
        //         $followUser->unread_count = $this->message
        //         ->where('sender_id', $followUser->id)
        //         ->where('receiver_id', $followUser->id)
        //         ->whereNull('read_at')
        //         ->count();
        //     }

        //     // modalを開いた状態でlist表示
        //     $openMessageModal = true;

        //     // HomeController同様$home_posts用意
        //     $all_posts = Post::latest()->get();
        //     $home_posts = [];
        //     foreach($all_posts as $post) {
        //         if(($post->user && $post->user->isfollowed()) || ($post->user && $post->user->id === $authUser->id)) {
        //             $home_posts[] = $post;
        //         }
        //     }

        //     $suggested_users = [];

        //     return view('users.home', compact(
        //         'followingUsers',
        //         'openMessageModal',
        //         'home_posts',
        //         'suggested_users'
        //     ));
        // }    

}
