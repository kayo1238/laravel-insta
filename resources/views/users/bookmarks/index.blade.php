@extends('layouts.app')

@section('title', 'Saved Posts')

@section('content')

    <div class="row justify-content-center">
        <div class="col-8">
            <div class="row align-items-center mb-5 bg-white shadow-sm rounded-3 p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 text-muted mb-0"><i class="fa-solid fa-bookmark text-warning me-2"></i>Saved Posts</h2>
                    <a href="{{ route('index') }}" class="btn btn-mini">back to Home</a>
                </div>
                
                <div class="row g-3">
                    @forelse ($posts as $post)
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('post.show', $post->id) }}" class="d-block">
                                <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100 rounded shadow-sm" style="aspect-ratio:1 / 1; object-fit: cover;">
                            </a>
                        </div>

                    @empty
                        <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                            <h3 class="h5">No saved posts yet</h3>
                            <p class="text-secondary mb-3">Bookmark posts from the feed to find them here later.</p>
                            <a href="{{ route('index') }}" class="btn btn-color btn-sm">Browse Posts</a>
                        </div>  
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    
@endsection