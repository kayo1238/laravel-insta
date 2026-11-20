<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    # show a user
    public function show($id) {
        $user = $this->user->findOrFail($id);
        return view('users.profile.show')->with('user', $user);
    }

    # Open edit page
    public function edit() {
        $user = $this->user->findOrFail(Auth::user()->id);  // only find LOGGED IN USER
        return view('users.profile.edit')->with('user', $user);
    }

    # update profile
    public function update(Request $request) {
        $request->validate([
            'name'         => 'required|min:1|max:50',
            'email'        => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            // unique:table,column,exceptID (we tell laravel all emails are unique except login user's)
            'avatar'       => 'mimes:jpeg,jpg,gif,png|max:1048',
            'introduction' => 'max:100'
        ]);

        #update database
        $user               = $this->user->findOrFail(Auth::user()->id);
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->introduction = $request->introduction;

        // if theres avatar
        if($request->avatar){
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }
        $user->save();  // save to detabase

        return redirect()->route('profile.show', Auth::user()->id);
    }

    # open followers page
    public function followers($id) {
        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);
    }

    # open following page
    public function following($id) {
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }

}
