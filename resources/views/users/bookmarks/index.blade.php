@extends('layouts.app')

@section('title', 'Saved Posts')

@section('content')
    
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0"><i class="fa-solid fa-bookmarktext-warning me-2"></i>Saved Posts</h2>
                <a href="{{ route('index') }}" class="text-decoration-none">back to Home</a>
            </div>

            @forelse ($posts as $post)
                <div id="post-{{ $post->id }}" class="card mb-4">
                    {{--title--}}
                    @include('users.posts.contents.title')
                    {{---body--}}
                    @include('users.posts.contents.body')
                </div>    
            @empty
                <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                    <h3 class="h5">No saved posts yet</h3>
                    <p class="text-secondary mb-3">Bookmark posts from the feed to find them here later.</p>
                    <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-sm">Browse Posts</a>
                </div>
                
            @endforelse
        </div>
    </div>
    
@endsection