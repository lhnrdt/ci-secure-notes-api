<script>
    import {DataService} from "../services/DataService";
    import {noteStore} from "../stores";
    import {Utils} from "../services/Utils";

    let open = false;
    let showBackdrop = true;
    let noteData;

    export let newNote = false;

    export const show = (note) => {
        open = true;
        showBackdrop = true;

        noteData = note;
    }

    export const hide = () => {
        open = false;
        showBackdrop = false;
    }

    const handleNoteDeleted = () => {
        DataService.deleteNote(noteData.id);
        $noteStore = $noteStore.filter(note => note.id !== noteData.id);
        hide();
    };

    const handleNoteUpdated = async (e) => {
        const formData = new FormData(e.target);
        const user = Utils.getUser();

        formData.append('user_id', user.id);
        formData.append('category_id', noteData['category_id']);
        const res = await DataService.updateNote(noteData.id, formData);
        $noteStore = $noteStore.map(note => res.note.id === note.id ? res.note : note);
        hide();
    };

</script>
{#if open}
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button on:click={hide} type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="newNote" on:submit|preventDefault={handleNoteUpdated}>
                    <div class="modal-body">
                        <div class="card-title">
                            <input type="text" class="form-control fw-bolder" placeholder="Titel" name="title"
                                   value={noteData.title}>
                        </div>

                        <p class="card-text">
                <textarea rows="8" type="text" class="form-control"
                          placeholder="Inhalt" name="content">{noteData.content}</textarea>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button on:click={handleNoteDeleted} type="button" class="btn btn-danger">Delete Note</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button on:click={hide} type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {#if showBackdrop}
        <div class="modal-backdrop show"></div>
    {/if}
{ /if}

<style>
    .modal {
        display: block;
    }
</style>