<script>
    import {DataService} from "../services/DataService";
    import {noteStore, categoryStore} from "../stores";
    import {Utils} from "../services/Utils";

    let modal;
    export let noteData;
    let color;

    let newNote;
    $: newNote = !noteData?.id;
    $: color = $categoryStore.find(c => c.id === noteData?.category_id)?.color || '#ffffff';

    export const show = (note) => {
        jQuery(modal).modal('show');
        if (!note.category_id) note.category_id = 'NULL';
        noteData = note;
    }

    export const hide = () => {
        jQuery(modal).modal('hide');
    }

    const handleSubmit = async (e) => {

        const formData = new FormData(e.target);
        const user = Utils.getUser();

        if (formData.get('category_id') === 'NULL') formData.delete('category_id');

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
        if (confirm('Notiz wirklich löschen?')) {
            DataService.deleteNote(noteData.id);
            $noteStore = $noteStore.filter(note => note.id !== noteData.id);
            hide();
        }
    };

</script>
<div class="modal fade" tabindex="-1" role="dialog" bind:this={modal}>
    <div class="modal-dialog" role="document">
        <div class="modal-content" style:background-color={color}>
            <div class="modal-header">
                <h5 class="modal-title">Notiz bearbeiten</h5>
                <button on:click={hide} type="button" class="close custom-close" data-dismiss="modal" aria-label="Close"
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
                            <option value="NULL" selected class="default-category">keine Kategorie</option>
                            {#each $categoryStore as category}
                                <option value={category.id} style:background-color={category.color}>
                                    {category.name}
                                </option>
                            {/each}
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

<style>
    .default-category {
        background: #ffffff;
    }

    .custom-close {
        aspect-ratio: 1/1;
        border: none;
    }
</style>