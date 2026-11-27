{{-- clickable image --}}
<div class="container p-0">
    <a href="{{ route('post.show', $post->id) }}">
        <img src="{{ $post->image }}" alt="post id{{ $post->id }}" class="w-100">
    </a>
</div>
<div class="card-body">
    {{-- heart button + no. of likes + categories --}}
    <div class="row align-items-center">
        <div class="col-auto">
            @if ($post->isLiked())
                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-solid fa-heart heart"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('like.store', $post->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-regular fa-heart no-heart"></i>
                    </button>
                </form>
            @endif
        </div>
        
        <div class="col-auto px-0">
            <span>{{ $post->likes->count() }}</span>
        </div>

        <div class="col-auto">
            <a href="{{ route('post.show', $post->id) }}" class="text-dark fa-xl">
                <i class="fa-regular fa-comment"></i>
            </a>
        </div>
        
        <div class="col-auto px-0">
            <span>{{ $post->comments->count() }}</span>
        </div>

        <div class="col text-end">
            @if ($post->categories->isNotEmpty())
                @foreach ($post->categories as $category)
                    <span class="badge bg-secondary bg-opacity-50">{{ $category->name }}</span>
                @endforeach
            @else
                <span class="badge bg-secondary bg-opacity-50">Uncategorized</span>
            @endif
        </div>
        {{--bookmark--}}
        <div class="col-auto ps-1">
            @php
                $isBookmarked = \App\Models\Bookmark::where('user_id', auth()->id())
                    ->where('post_id', $post->id)
                    ->exists();
            @endphp

            @if ($isBookmarked)
            <form action="{{ route('bookmarks.destroy', $post->id) }}" method="post" class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm shadow-none p-0" title="Remove bookmark">
                    <i class="fa-solid fa-bookmark text-warning fs-5"></i>
                </button>
            </form>
                
            @else
                <form action="{{ route('bookmarks.store', $post->id) }}" method="post" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0" title="Bookmark">
                        <i class="fa-regular fa-bookmark text-dark fs-5"></i>
                    </button>
                </form>
            @endif
        </div>
        <div class="col-auto ps-1">
            <span>{{ $post->bookmarks->count() }}</span>
        </div>
    </div>

    {{-- owner + description --}}
    <a href="{{ route('profile.show', $post->user->id) }}"
        class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
    &nbsp; {{--画面を大きくしても小さくしても名前と内容が同じ列になる--}} 
    <p class="d-inline fw-light">{{ $post->description }}</p>
    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

    {{-- include comments here --}}
    @include('users.posts.contents.comments')
</div>
