<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    //bookmarkを追加する
    public function store(Post $post){
        Bookmark::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' =>$post->id,
        ]);

        return back();
    }
    //bookmarkを解除する
    public function destroy(Post $post){
        Bookmark::where('user_id', Auth::id())
        ->where('post_id', $post->id)
        ->delete();

        return back();
    }
    //自分がbookmarkした投稿idを取り出す
    public function index(){
        $bookmarkedPostIds = Bookmark::where('user_id', Auth::id())
            ->latest()
            ->pluck('post_id');
    //該当する投稿データをまとめて取得する    
        $posts = Post::whereIn('id', $bookmarkedPostIds)
            ->latest()
            ->get();

        return view('users.bookmarks.index', compact('posts'));
    }
}




