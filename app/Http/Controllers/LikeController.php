<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $like;
    public function __construct(Like $like) {
        $this->like = $like;
    }

    # store likes to database
    public function store($post_id) {
        $this->like->user_id = Auth::user()->id;  // liked by logged in user
        $this->like->post_id = $post_id;          // which post
        $this->like->save();                      // save to batabase

        # 自分の投稿以外にlikesされた場合のみ通知を作成
        $post = Post::findOrFail($post_id);
        if ($post->user_id !== Auth::user()->id) {
            Notification::create([
                'user_id'   => $post->user_id,  // 通知を受け取る人
                'sender_id' => Auth::user()->id, // likeした人
                'post_id'   => $post->id,
                'type'      => 'like',
            ]);
            return redirect()->back();
        }
    }

    # unlike a post(destroy)
    public function destroy($post_id) {
        $this->like
            ->where('user_id', Auth::user()->id)
            ->where('post_id', $post_id)
            ->delete();

            return redirect()->back();
    }
}
