<!--suppress JSUnresolvedFunction -->
<script>
    import {DataService} from "../services/DataService";
    import {noteStore, categoryStore, EMPTY_NOTE} from "../stores";
    import {Utils} from "../services/Utils";
    import CategorySkeleton from "./skeletons/CategorySkeleton.svelte";

    let noteData = EMPTY_NOTE;
    let modal;
    let color;
    let newNote;

    $: newNote = !noteData?.id;

    const updateColor = async () => {
        // find color in loaded category by category_id
        color = await $categoryStore.then(cs => cs.find(c => c.id === noteData?.category_id)?.color ?? '#ffffff');
    }

    // open the modal bootstrap style
    export const show = (note) => {
        if (!note.category_id) note.category_id = 'NULL';
        noteData = note;
        console.log(noteData)
        updateColor();
        jQuery(modal).modal('show');
    }

    // hide the modal bootstrap style
    export const hide = () => {
        jQuery(modal).modal('hide');
        noteData = EMPTY_NOTE;
    }

    // submit changes to db
    const handleSubmit = async (e) => {

        const formData = new FormData(e.target);
        const user = Utils.getUser();

        // remove category id if marked as NULL
        if (formData.get('category_id') === 'NULL') formData.delete('category_id');
        formData.append('user_id', user.id);

        if (newNote) {
            // update db
            const res = await DataService.createNote(formData);
            // update local
            $noteStore = [res.note, ...$noteStore];
        } else {
            // update db
            const res = await DataService.updateNote(noteData.id, formData);
            // update local
            $noteStore = $noteStore.map(note => res.note.id === note.id ? res.note : note);
        }
        hide();
    }


    const handleNoteDeleted = () => {
        if (confirm('Notiz wirklich löschen?')) {
            // update db
            DataService.deleteNote(noteData.id);
            // update local
            $noteStore = $noteStore.filter(note => note.id !== noteData.id);
            hide();
        }
    };

</script>
<div class="modal fade" tabindex="-1" role="dialog" bind:this={modal}>
    <div class="modal-dialog" role="document">
        <div class="modal-content" style:background-color={color}>
            <div class="modal-header">
                <h5 class="modal-title">
                    {#if newNote}
                        Notiz erstellen
                    {:else }
                        Notiz bearbeiten
                    {/if}
                </h5>
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
                                on:change={updateColor}
                        >
                            <option value="NULL" selected class="default-category">keine Kategorie</option>
                            {#await $categoryStore}
                                {#each Array(5) as _}
                                    <CategorySkeleton/>
                                {/each}
                            {:then categories}
                                {#each categories as category}
                                    <option value={category.id} style:background-color={category.color}>
                                        {category.name}
                                    </option>
                                {:else}
                                    <p>keine Kategorien</p>
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

<style>
    .default-category {
        background: #ffffff;
    }

    .custom-close {
        aspect-ratio: 1/1;
        border: none;
    }
</style>