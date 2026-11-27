<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class User extends Authenticatable
{
    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID = 2;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # To get all the posts of a user
    public function posts() {
        return $this->hasMany(Post::class)->latest();
    } 

    # To get all the followers of a user
    public function followers() {
        return $this->hasMany(Follow::class, 'following_id');
    }

    # To get all the users that the user is following
    public function following() {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    # Return TRUE if the AUTH USER already followed the user
    public function isFollowed() {
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        // get all the follower of the user
        // check where in follower_id column AUTH USER exists
    }

    #Get Nottification & 未読の通知一覧を表示
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }
    public function unreadNotificationsCount(){
        return $this->notifications()->where('is_read', false)->count();
    }
}
