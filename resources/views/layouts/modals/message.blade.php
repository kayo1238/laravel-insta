@php
    $isModalOpen = isset($receiver);
@endphp

<!-- チャット用モーダル -->
<div class="modal fade {{ $isModalOpen ? 'show' : '' }}" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="{{ $isModalOpen ? 'false' : 'true' }}" style="{{ $isModalOpen ? 'display:block;' : '' }}">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatModalLabel">
                    @isset($receiver)
                        <a href="{{ route('message.show', $followingUsers->first()->id ?? $receiver->id) }}" class="text-decoration-none text-dark me-2">
                            &lt;
                        </a>
                        {{ receiver->name }}
                        @else
                            Messages
                    @endisset
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @isset($receiver)
                {{-- 選択中の相手とのmessage list --}}
                @forelse ($messages ?? [] as $msg)
                    @if ($msg->sender_id === Auth::id())
                        <div class="d-flex justify-content-end mb-2">
                            <div class="bg-primary.text-white.rounded.px-3.py-2" style="max-width: 75%;">
                              <div>{{ msg->body }}</div>
                              <small class="opacity-75" style="font-size: 0.7rem;">
                                {{ $msg->created_at->format('H:i') }}
                              </small>
                              </div>
                        </div>
                    @else
                        <div class="d-flex jutify-content-start mb-2">
                            <div class="bg-light border rounded px-3 py-2" style="max-width: 75%;">
                                <div>{{ $msg->body }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">
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
            @forelse ($followingUsers ?? [] as $followUser)
                <a href="{{ route('message.show', $followUser->id) }}" class="d-flex align-items-center gap-2 p-2 text-decoration-none text-dark border-bottom">
                    @if ($followUser->avatar)
                        <img src="{{ $followUser->avatar }}" alt="{{ $followUser->name }}" class="rounded-circle avatar-sm">
                    @else
                        <i class="fa-solid fa-circle-user fs-4 text-secondary"></i>
                    @endif
                    <span>{{ $followUser->name }}</span>
                </a>
            @empty
                <p class="text-muted">I don't have any users following me yet.</p>
            @endforelse
            @endisset
            </div>

           @isset($receiver)
               
           @endisset
        </div>
    </div>
</div>