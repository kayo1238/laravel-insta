<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    private $follow;
    public function __construct(Follow $follow) {
        $this->follow = $follow;
    }

     # store data to database for follow
     public function store($user_id) {
        $this->follow->follower_id = Auth::user()->id;
        $this->follow->following_id  = $user_id;
        $this->follow->save();

        #followされた通知を作成（post_idはnull）
        Notification::create([
            'user_id'   => $user_id,        //通知を受け取る人(相手)
            'sender_id' => Auth::user()->id,    //followした人（自分）
            'post_id'   => null,
            'type'      => 'follow',
        ]);

        return redirect()->back();
     }

     # desrtoy all data
     public function destroy($user_id) {
        $this->follow
            ->where('follower_id', Auth::user()->id)
            ->where('following_id', $user_id)
            ->delete();

            return redirect()->back();
     }
}
