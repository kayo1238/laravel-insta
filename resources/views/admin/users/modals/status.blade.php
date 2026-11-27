@if ($user->trashed())
{{-- Activate --}}
    <div class="modal fade" id="activate-user-{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content activate">
            <div class="modal-header activate">
                <h3 class="h5 modal-title activate">
                    <i class="fa-solid fa-user-slash"></i> Avtivate User
                </h3>
            </div>
            <div class="modal-body">
                Are you sure you want to activate <span class="fw-bold">{{ $user->name }}</span>?
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.users.activate', $user->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-outline-orange btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-orange btn-sm">Activate</button>
                </form>
            </div>
        </div>
    </div>
</div> 
@else
{{-- Deactivate --}}
<div class="modal fade" id="deactivate-user-{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h5 modal-title text-danger">
                    <i class="fa-solid fa-user-slash"></i> Deavtivate User
                </h3>
            </div>
            <div class="modal-body">
                Are you sure you want to deactivate <span class="fw-bold">{{ $user->name }}</span>?
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.users.deactivate', $user->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Deavtivate</button>
                </form>
            </div>
        </div>
    </div>
</div> 
@endif