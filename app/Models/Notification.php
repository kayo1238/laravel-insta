<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //　一括保存を許可する
    protected $fillable = [
        'user_id',
        'sender_id',
        'post_id',
        'type',
        'is_read',
    ];

    //　通知を送ってきたユーザー（いいね・コメントした人）を取得
    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    //　対象の投稿を取得
    public function post(){
        return $this->belongsTo(Post::class);
    }
}



