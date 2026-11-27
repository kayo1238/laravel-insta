@if ($post->trashed())
    {{-- Unhide --}}
    <div class="modal fade" id="unhide-post-{{ $post->id }}">
        <div class="modal-dialog">
            <div class="modal-content unhide">
                <div class="modal-header unhide">
                    <h3 class="h5 modal-title unhide">
                        <i class="fa-solid fa-user-slash"></i> Unhide Post
                    </h3>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to Unhide this post?</p>
                    <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="image-lg"
                        style="width: 120px; height: 120px; object-fit:cover">
                    <p class="mt-3 text-muted">{{ $post->description }}</p>
                </div>
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.posts.unhide', $post->id) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <button type="button" class="btn btn-outline-pink btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-pink btn-sm">Unhide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Hide --}}
    <div class="modal fade" id="hide-post-{{ $post->id }}">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                <div class="modal-header border-danger">
                    <h3 class="h5 modal-title text-danger">
                        <i class="fa-solid fa-eye-slash"></i> Hide Post
                    </h3>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to hide the post?</p>
                    <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="image-lg"
                        style="width: 120px; height: 120px; object-fit:cover">
                    <p class="mt-3 text-muted">{{ $post->description }}</p>
                </div>
                <div class="modal-footer border-0">
                    <form action="{{ route('admin.posts.hide', $post->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Hide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
