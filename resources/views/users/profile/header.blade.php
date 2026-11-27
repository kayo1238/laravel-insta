<div class="row align-items-center mb-5 bg-white shadow-sm rounded-3 p-3">
    <div class="row mt-5">
        <div class="col-4">
            @if ($user->avatar)
                <img src="{{ $user->avatar }}" alt="" class="img-thumbnail rounded-circle d-block mx-auto avatar-lg">
            @else
                <i class="fa-solid fa-face-smile smile d-block text-center icon-lg"></i>
            @endif
        </div>
        <div class="col-8">
            <div class="row mb-3">
                <div class="col-auto">
                    <h2 class="display-6 mb-0">{{ $user->name }}</h2>
                </div>
                <div class="col-auto p-2">
                    @if (Auth::user()->id === $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn btn-mini fw-bold">Edit
                            Profile</a>
                    @else
                        @if ($user->isFollowed())
                            <form action="{{ route('follow.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-following fw-bold">Following</button>
                            </form>
                        @else
                            <form action="{{ route('follow.store', $user->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-mini fw-bold">Follow</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-auto text-center">
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark">
                        <strong class="h2">{{ $user->posts->count() }}</strong><br>
                        {{ $user->posts->count() == 1 ? 'post' : 'posts'}}
                    </a>
                </div>
                <div class="col-auto text-center">
                    <a href="{{ route('profile.followers', $user->id) }}" class="text-decoration-none text-dark">
                        <strong class="h2">{{ $user->followers->count() }}</strong><br>
                        {{ $user->followers->count() == 1 ? 'follower' : 'followers'}}
                    </a>
                </div>
                <div class="col-auto text-center">
                    <a href="{{ route('profile.following', $user->id) }}" class="text-decoration-none text-dark">
                        <strong class="h2">{{ $user->following->count() }}</strong><br> following
                    </a>
                </div>
            </div>
            <p class="fw-bold h4">{{ $user->introduction }}</p>
        </div>
    </div>


