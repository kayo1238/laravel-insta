<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    # A post belong to a user
    # To  get the owner of the post(1-many invers)

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    # To get the categories under a post
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }

    # To get all the comments of a post
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    # To get the likes of a post
    public function likes(){
        return $this->hasMany(Like::class);
    }

    # Returns TRUE if the AUTH USER (LOGGED IN ISER) already liked the post
    public function isLiked(){
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
        // first, return ALL LIKES of the post by calling the likes() relationship
        // second check if AUTH USER already EXISITS is the user_id column
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }
}
