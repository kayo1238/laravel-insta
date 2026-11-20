<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    private $comment;
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    # to add comments to database
    public function store(Request $request, $post_id)
    {
        $request->validate(
            [
                'comment_body' . $post_id => 'required|max:150'
            ],
            [
                'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
                'comment_body' . $post_id . '.max'      => 'The comment must not have more than 150 characters.'
            ]
        );

        $this->comment->body = $request->input('comment_body' . $post_id); // input is the data from name attribute
        $this->comment->user_id = Auth::user()->id;
        $this->comment->post_id = $post_id;
        $this->comment->save();

        return redirect()->route('post.show', $post_id);
    }

    # create destroy method for comment
    # create destroy method (delete)
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);   
        $comment->delete();       
        // $this->comment->destroy($id);             
    # use redirect()->back
        return redirect()->back();
    }
   
}
