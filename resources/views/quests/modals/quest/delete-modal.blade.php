{{-- Quests/modals/quest delete-modal.blade.php --}}
<!-- resources/views/quests/modals/delete-modal.blade.php -->
<div class="modal fade" id="delete-quest-{{ $quest->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-red">
            <form action="{{ route('quest.delete', $quest->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header border-0">
                    <h3 class="modal-title poppins-bold">Delete this Quest?</h3>
                </div>
                <div class="modal-body text-start border-0 raleway-regular">
                    <p class="pb-0 fs-4">Are you sure you want to delete this Quest?</p>
                    <p class="x-small color-red ps-3 pt-0">*All contents within this Quest will be deleted.</p>
                    <p class="text-center raleway-semibold fs-4">
                        {{ $quest->title }}
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



