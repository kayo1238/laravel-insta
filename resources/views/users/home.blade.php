@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <div class="row gx-5">
        <div class="col-8">
            @if (!empty($suggested_users))
                <div class="story-strip d-flex align-items-start gap-3 overflow-auto py-2 px-3 mb-4 bg-white rounded-3 border">
                    @foreach ($suggested_users as $storyUser)
                        <a href="{{ route('profile.show', $storyUser->id) }}" class="story-item text-decoration-none text-center flex-shrink-0">
                            <div class="story-ring d-flex align-items-center justify-content-center mx-auto mb-1">
                                <div class="story-inner d-flex align-items-center justify-content-center rounded-circle overflow-hidden">
                                    @if ($storyUser->avatar)
                                        <img src="{{ $storyUser->avatar }}" alt="{{ $storyUser->name }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <i class="fa-solid fa-face-smile smile fs-4"></i>
                                    @endif
                                </div>
                            </div>
                            <span class="story-name d-block text-truncate text-dark small">
                                {{ $storyUser->name }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
            @forelse ($home_posts as $post)
                <div class="card mb-4">
                    {{-- title --}}
                    @include('users.posts.contents.title')
                    {{-- body --}}
                    @include('users.posts.contents.body')
                </div>
            @empty
                {{-- if the site doesn't have any post yet --}}
                <div class="row align-items-center mb-5 bg-white shadow-sm rounded-3 py-3">
                    <div class="text-center">
                        <h2>Share Photos</h2>
                        <p class="text-secondary">When you share photos, they'll appear on your profile.</p>
                        <a href="{{ route('post.create') }}" class="btn btn-color">Share your first photo</a>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="col-4">
            {{-- PROFILE OVERVIEW + SUGGESTION --}}
            <div class="row align-items-center mb-4 bg-white shadow-sm rounded-3 py-4">
                <div class="col-auto">
                    <a href="{{ route('profile.show', Auth::user()->id) }}">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                class="rounded-circle avatar-md">
                        @else
                            <i class="fa-solid fa-face-smile smile icon-md"></i>
                        @endif
                    </a>
                </div>
                <div class="col ps-0">
                    <a href="{{ route('profile.show', Auth::user()->id) }}"
                        class="text-decoration-none text-dark fw-bold">{{ Auth::user()->name }}</a>
                    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                </div>
            </div>


            {{-- Suggestions --}}
            @if ($suggested_users)
                <div class="row align-items-center mb-5 bg-white shadow-sm rounded-3 p-3">
                    <div class="row">
                        <div class="col-auto">
                            <p class="fw-bold text-secondary">Suggestions For You</p>
                        </div>
                        <div class="col text-end">
                            <a href="" class="fw-bold text-dark text-decoration-none">See all</a>
                        </div>
                    </div>

                    @foreach ($suggested_users as $user)
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <a href="{{ route('profile.show', $user->id) }}">
                                    @if ($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                            class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-face-smile smile icon-sm"></i>
                                    @endif
                                </a>
                            </div>
                            <div class="col ps-0 text-truncate">
                                <a href="{{ route('profile.show', $user->id) }}"
                                    class="text-decoration-none text-dark fw-bold">{{ $user->name }}</a>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('follow.store', $user->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-mini">
                                        Follow</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
