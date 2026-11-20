<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $post;
    private $user;
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts(); // get all posts from latest posted
        $suggested_users = $this->getSuggestedUsers();
        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users);
    }

    # To get the users that the AUTH USER IS NOT following
    public function getSuggestedUsers() {
        $all_users = $this->user->all()->except(Auth::user()->id);
        // get all users except AUTH USER
        $suggested_users = [];  // array for the suggested users

        foreach($all_users as $user){
            //  loop throught all users
            if(!$user->isFollowed()){
                // add a condition if THE AUTH USER IS NOT(!) following the user
                // call isFollowded because that method returns TRUE if the AUTH USER followed the user
                $suggested_users[] = $user;
                // put the data in the array 
            }
        }

        return $suggested_users;
    }

    # Get the post of the users that the AUTH USER is following
    public function getHomePosts() {
        $all_posts = $this->post->latest()->get(); // get all posts from latest posted
        $home_posts = [];                         // attay for the filtered posts

        foreach($all_posts as $post) {
            //  loop through all posts
            //if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
            if (($post->user && $post->user->isFollowed()) || ($post->user && $post->user->id === Auth::user()->id)){
                // add condition to check if the posts are from the AUTH USER'S followed users
                // OR if the owner of the post is the AUTH USER
                $home_posts[] = $post;   // if true, put data in the array
            }
        }

        return $home_posts;
    }

    public function search(Request $request) {
        $users = $this->user->where('name', 'like', '%' .$request->search.'%')->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }
}
