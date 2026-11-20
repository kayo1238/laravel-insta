@extends('layouts.app')

@section('title', 'Admin: Posts')

@section('content')
    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="small table-primary text-secondary">
            <tr>
                <th>ID</th>
                <th></th>
                <th>CATEGORY</th>
                <th>OWNER</th>
                <th>CREATE AT</th>
                <th>STATUS</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($all_posts as $post)
                <tr>
                    <td class="text-end">{{ $all_posts->firstItem() + $loop->index }}</td>
                    <td>
                        <img src="{{ $post->image }}" alt="{{ $post->id }}" class="d-block mx-auto"
                            style="width: 80px; height: 80px; object-fit:cover;">
                    </td>
                    <td>
                        {{-- Categories --}}
                        @if ($post->categories->isNotEmpty())
                            @foreach ($post->categories as $category)
                                <span class="badge bg-secondary bg-opacity-50">{{ $category->name }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary bg-opacity-50">Uncategorized</span>
                        @endif

                    </td>
                    <td>{{ $post->user->name ?? 'Guest' }}</td>
                    <td>{{ $post->created_at }}</td>
                    <td>
                        @if ($post->trashed())
                            {{-- tarashed() - returns TRUE is the user was soft deleted --}}
                            <i class="fa-regular fa-circle text-secondary"></i>&nbsp; Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i>&nbsp; Visible
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            <div class="dropdown-menu">
                                @if ($post->trashed())
                                    <button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#unhide-post-{{ $post->id }}">
                                        <i class="fa-solid fa-eye"></i> Unhide Post {{ $post->id }}
                                    </button>
                                @else
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                        data-bs-target="#hide-post-{{ $post->id }}">
                                        <i class="fa-solid fa-eye-slash"></i> Hide Post {{ $post->id }}
                                    </button>
                                @endif
                            </div>
                        </div>
                        {{-- modal here --}}
                        @include('admin.posts.modals.status')

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $all_posts->links() }}
    </div>
@endsection
