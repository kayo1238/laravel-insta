<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    private $post;
    public function __construct(Post $post) {
        $this->post = $post;
    }

    # open admin/posts and display all posts
    public function index() {
        $all_posts = $this->post->with('categories')->withTrashed()->latest()->paginate(5);  // get all posts from latest added
        // withTrashed() - include soft deleted data in a query result
        return view('admin.posts.index')->with('all_posts', $all_posts);
    }

    # deactivate a post
    public function hide($id) {
        $this->post->destroy($id);
        return redirect()->back();
    }

    # activate a post
    public function unhide($id) {
        $this->post->onlyTrashed()->findOrFail($id)->restore();
        // onlyTrashed() - retrieves only soft deleted data
        // restore() - this will "un-delete" soft deleted datas. will set "deleted_at" column to null
        return redirect()->back();
    }
}


