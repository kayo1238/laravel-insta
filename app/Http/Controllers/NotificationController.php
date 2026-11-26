<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){

        // login userの通知を新しい順位に取得（送信者と投稿データも一緒に取得）
        $notifications = Auth::user()->notifications()->with(['sender', 'post'])->get();

        // whereで未読の絞り込み、pluckで未読通知のidを抜き出す、toArrayで配列に
        $unreadIds  = $notifications->where('is_read', false)->pluck('id')->toArray();

        // もし未読があればtrueに書き換える
        if(!empty($unreadIds)){
            Auth::user()->notifications()->whereIn('id', $unreadIds)->update(['is_read' => true]);
        }

        return view('users.notifications.index', compact('notifications', 'unreadIds'));
    }

    public function destroyAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return redirect()>back();
    }
}   
