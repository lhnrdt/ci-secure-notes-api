<script>
    import {DataService} from "../services/DataService";
    import {noteStore, categoryStore} from "../stores";
    import {Utils} from "../services/Utils";

    let open = false;
    let showBackdrop = true;
    let noteData;
    let color;

    let newNote;
    $: newNote = !noteData?.id;
    $: color = $categoryStore.find(c => c.id === noteData?.category_id)?.color || 'white';

    export const show = (note) => {
        open = true;
        showBackdrop = true;
        noteData = note;
    }

    export const hide = () => {
        open = false;
        showBackdrop = false;
    }

    const handleSubmit = async (e) => {

        const formData = new FormData(e.target);
        const user = Utils.getUser();

        formData.append('user_id', user.id);

        if (newNote) {
            const res = await DataService.createNote(formData);
            $noteStore = [res.note, ...$noteStore];
        } else {
            const res = await DataService.updateNote(noteData.id, formData);
            $noteStore = $noteStore.map(note => res.note.id === note.id ? res.note : note);
        }
        hide();
    }


    const handleNoteDeleted = () => {
        DataService.deleteNote(noteData.id);
        $noteStore = $noteStore.filter(note => note.id !== noteData.id);
        hide();
    };

</script>
{#if open}
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style:background-color={color}>
                <div class="modal-header">
                    <h5 class="modal-title">Notiz bearbeiten</h5>
                    <button on:click={hide} type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style:background-color={color}
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="newNote" on:submit|preventDefault={handleSubmit}>
                    <div class="modal-body">
                        <div class="card-title">
                            <input type="text" class="form-control fw-bolder" placeholder="Titel" name="title"
                                   value={noteData.title}
                                   style:background-color={color}
                            >
                        </div>

                        <p class="card-text">
                            <textarea rows="8" type="text" class="form-control" placeholder="Inhalt" name="content"
                                      style:background-color={color}
                            >{noteData.content}</textarea>
                        </p>

                        <div class="input-group mb-3">
                            <select name="category_id" class="form-select" aria-label="Color"
                                    style:background-color={color}
                                    bind:value={noteData.category_id}
                            >
                                {#await $categoryStore}
                                    <option value="">loading...</option>
                                {:then categories}
                                    {#each categories as category}
                                        <option value={category.id} style:background-color={category.color}>
                                            {category.name}
                                        </option>
                                    {/each}
                                {/await}
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {#if !newNote}
                            <button on:click={handleNoteDeleted} type="button" class="btn btn-danger">
                                Löschen
                            </button>
                        {/if}
                        <button type="submit" class="btn btn-primary">
                            Speichern
                        </button>
                        <button on:click={hide} type="button" class="btn btn-secondary" data-dismiss="modal">
                            Abbrechen
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