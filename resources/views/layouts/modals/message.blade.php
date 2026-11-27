@php
    $isModalOpen = isset($receiver);
@endphp

<!-- チャット用モーダル -->
<div class="modal fade {{ $isModalOpen ? 'show' : '' }}" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel"
    aria-hidden="{{ $isModalOpen ? 'false' : 'true' }}" style="{{ $isModalOpen ? 'display:block;' : '' }}">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="chatModalLabel">
                    @isset($receiver)
                        {{ $receiver->name }}
                    @else
                        Messages
                    @endisset
                </h5>
                @isset($receiver)
                    <a href="{{ url('/') }}" class="text-decoration-none ms-auto fs-4 lh-1" aria-label="Close"><i class="fa-solid fa-square-xmark text-white"></i></a>
                @else
                  <a href="{{ url('/') }}" class="text-decoration-none ms-auto fs-4 lh-1" aria-label="Close"><i class="fa-solid fa-square-xmark text-white"></i></a>
                @endisset
            </div>

            <div class="modal-body">
                @isset($receiver)
                    {{-- 選択中の相手とのmessage list --}}
                    @forelse ($messages ?? [] as $msg)
                        @if ($msg->sender_id === Auth::id())
                            <div class="d-flex justify-content-end mb-2">
                                <div class="bg-primary text-white rounded px-3 py-2" style="max-width: 75%;">
                                    <div>{{ $msg->body }}</div>
                                    <small class="opacity-75" style="font-size: 0.7rem;">
                                        {{ $msg->created_at->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-start align-items-start gap-2 mb-2">
                                @if ($receiver->avatar)
                                    <img src="{{ $receiver->avatar }}" alt="{{ $receiver->name }}"
                                        class="rounded-circle avatar-sm"
                                        style="width: 32px; height 32px; object-fit: cover">
                                @else
                                    <i class="fa-solid fa-circle-user fs-4 text-secondary"></i>
                                @endif

                                <div class="bg-secondary bg-opacity-25 text-dark border rounded px-3 py-2"
                                    style="max-width: 75%;">
                                    <div>{{ $msg->body }}</div>
                                    <small class="text-dark" style="font-size: 0.7rem;">
                                        {{ $msg->created_at->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-muted text-center my-4">No message yet.</p>
                    @endforelse
                @else
                    {{-- follow user list --}}
                    <p class="text-muted small mb-2">Users following</p>

                    @php
                        $followList = $followingUsers ?? null;
                        $authId = \Illuminate\Support\Facades\Auth::id();

                        if (is_null($followList) && $authId) {
                            /** @var \App\Models\User $authUser */
                            $authUser = \Illuminate\Support\Facades\Auth::user();
                            $followingIds = $authUser->following()->pluck('following_id');
                            $followList = \App\Models\User::whereIn('id', $followingIds)->get();

                            foreach ($followList as $followUser) {
                                $followUser->unread_count = \App\Models\Message::where('sender_id', $followUser->id)
                                    ->where('receiver_id', $authId)
                                    ->whereNull('read_at')
                                    ->count();
                            }
                        }
                    @endphp

                    @forelse ($followList ?? [] as $followUser)
                        @php
                            $unreadCount = $followUser->unread_count ?? 0;
                        @endphp
                        <a href="{{ route('messages.show', $followUser->id) }}"
                            class="d-flex align-items-center justify-content-between p-2 text-decoration-none text-dark border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                @if ($followUser->avatar)
                                    <img src="{{ $followUser->avatar }}" alt="{{ $followUser->name }}"
                                        class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user fs-4 text-secondary"></i>
                                @endif
                                <span>{{ $followUser->name }}</span>
                            </div>

                            @if ($unreadCount > 0)
                                <span class="badge rounded-pill bg-danger">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    @empty
                        <p class="text-muted">I don't have any users following me yet.</p>
                    @endforelse
                @endisset
            </div>

            @isset($receiver)
                <div class="modal-footer">
                    <form action="{{ route('messages.store', $receiver->id) }}" method="POST" class="w-100 d-flex gap-2">
                        @csrf
                        <input type="text" name="body" class="form-control" placeholder="Message..." required>
                        <button type="submit" class="btn btn-danger  bg-danger bg-opacity-75">Send</button>
                    </form>
                </div>
            @endisset
        </div>
    </div>
</div>

@if ($isModalOpen)
    <div class="modal-backdrop fade show"></div>
@endif
