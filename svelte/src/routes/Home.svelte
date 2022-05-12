<script>

    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import Note from "../components/Note.svelte";
    import {MasonryGrid} from "@egjs/svelte-grid";
    import {clickOutside} from "./clickOutside";

    let notes = [];
    let newNoteOpen = false;

    function loadNotes() {
        return async () => {
            const notesRes = await DataService.getResource('/api/notes');
            notes = await notesRes['note'];
        };
    }

    onMount(loadNotes())

    const openNewNote = () => {
        newNoteOpen = true;
    };

    const closeNewNote = () => {
        newNoteOpen = false;
    };

    const saveNewNote = () => {

        const formData = new FormData(document.getElementById('newNote'));

        if (!!formData.get('title') || !!formData.get('content')) {
            // @Todo: get actual userId
            formData.append('user_id', '1');
            formData.append('category_id', '1');
            DataService.postResource('/api/notes', formData);
        }

        closeNewNote();
    }

</script>

<main class="container-fluid mt-2">

    <div class="row justify-content-center">
        <div class="col-6 mb-3">
            <div use:clickOutside on:click_outside={saveNewNote} data-open={newNoteOpen}
                 class="card border-1 shadow-sm">
                <div class="card-body">
                    <form id="newNote">
                        {#if (newNoteOpen)}
                            <div class="card-title">
                                <input type="text" class="form-control border-0" placeholder="Titel" name="title">
                            </div>
                        {/if}

                        <p class="card-text">
                            <input on:click={openNewNote} type="text" class="form-control border-0"
                                   placeholder="Neue Notiz..." name="content">
                        </p>
                        {#if (newNoteOpen)}
                            <a on:click|preventDefault={closeNewNote} href={'#'}
                               class="btn btn-light float-end">Schließen</a>
                        {/if}
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col">
            <MasonryGrid class="m-container" align="center" gap={10}>
                {#each notes as note}
                    <Note note={note}/>
                {:else}
                    <p>No notes found.</p>
                {/each}
            </MasonryGrid>
        </div>
    </div>
</main>


<style>

</style>