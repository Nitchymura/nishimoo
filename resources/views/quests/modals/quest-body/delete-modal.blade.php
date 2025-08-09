<!-- resources/views/quests/modals/delete-modal.blade.php -->
<div class="modal fade" id="delete-questbody-{{ $questbody->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-red">
            <form action="{{ route('questbody.delete', $questbody->id) }}" method="POST">
                
                @csrf
                @method('DELETE')
                <div class="modal-header border-0">
                    <h3 class="modal-title poppins-bold">Delete this place?</h3>
                </div>
                <div class="modal-body text-start border-0">
                    <p>Are you sure you want to delete this place?</p>
                    <p class="text-center raleway-semibold fs-4">
                        @if ($questbody->spot)
                            {{ $questbody->spot->title }}
                        @elseif ($questbody->business)
                            {{ $questbody->business->name }}
                        @else
                            Undefined
                        @endif
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-red">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-red">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

