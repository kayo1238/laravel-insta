@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

    <div class="row justify-content-center" style="font-family: Arial,sans-serif;">
        <div class="col-8">
            <div class="row align-items-center mb-5 bg-white shadow-sm rounded-3 p-3">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="h4 text-muted mb-0">
                        <i class="fa-solid fa-bell me-2"></i>
                        Notifications
                    </h3>

                    @if ($notifications->isNotEmpty())
                        <form action="{{ route('notifications.destroyAll') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class=" fa-trash-can me-1"></i>Clear all    
                            </button>  
                        </form>
                    @endif
                </div>
                

                @if ($notifications->isNotEmpty())
                    <div class="list-group shadow-sm">
                        @foreach ($notifications as $notification)
                        {{--follow通知なら相手のPFへ、投稿の通知なら投稿詳細へ--}}
                        <a href="{{ $notification->type === 'follow' ? route('profile.show', $notification->sender_id) : route('post.show', $notification->post_id) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 {{ in_array($notification->id,$unreadIds) ? 'bg-light' : '' }}">
                            
                            <div class="d-flex align-items-center">
                                {{--通知を送った人のicon--}}
                                @if ($notification->sender->avatar)
                                    <img src="{{ $notification->sender->avatar }}" alt="{{ $notification->sender->name }}" class="rounded-circle avatar-sm me-3">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm me-3"></i>   
                                @endif
                                <div>
                                {{--通知message--}}
                                    <span class="fw-bold text-dark fs-5 me-2" style="font-family: sans-serif">{{ $notification->sender->name }}</span>
                                    @if ($notification->type === 'like')
                                        <span class="text-dark">liked your post.</span>    
                                    @elseif ($notification->type === 'comment')
                                        <span class="text-dark">commented on your post.</span> 
                                        
                                    @elseif ($notification->type === 'follow')
                                        <span class="text-secondary">started following you.</span>    
                                    @endif

                                    {{--未読＆開いたら直ぐにマーク--}}
                                    @if (in_array($notification->id, $unreadIds))
                                    <span class="badge bg-primary ms-2">NEW</span>                                
                                    @endif

                                    <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            {{--サムネイル画像--}}
                            @if ($notification->post)
                                <img src="{{ $notification->post->image}}" alt="post" class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                
                            @endif
                        </a>
                            
                        @endforeach
                    </div>
                @else
                <p class="text-muted text-center mt-5">No notifications yet.</p>          
                @endif
            </div>
        </div>
    </div>

    
@endsection