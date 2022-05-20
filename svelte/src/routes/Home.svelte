<script>

    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import Note from "../components/Note.svelte";
    import {MasonryGrid} from "@egjs/svelte-grid";
    import InfiniteScroll from "../components/InfiniteScroll.svelte";
    import Modal from "../components/Modal.svelte";

    let masonryGrid;

    let modal;
    let showModal = false;

    let offset = 0;
    const limit = 20;

    let notes = [];
    let newNotesBatch = [];

    $: notes = [
        ...notes,
        ...newNotesBatch
    ];

    async function fetchData() {
        const data = await DataService.getResource(`/api/notes?offset=${offset}&limit=${limit}`);
        newNotesBatch = data['note'];
    }

    onMount(() => {
        fetchData();
    })

    const saveNewNote = async () => {
        await DataService.createNote(new FormData(document.getElementById('newNote')))
    }

    let currentNote;

    const openModal = (e) => {
        currentNote = e.detail.note;
        showModal = true;
    };

    const handleModalClose = () => {
        showModal = false;
    };

    const deleteNote = () => {
        DataService.deleteNote(currentNote.id);
        notes = notes.filter(note => note.id !== currentNote.id);
        showModal = false;
    }


</script>

<main class="container-fluid mt-5">


    <div class="row justify-content-center">
        <div class="col">
            <MasonryGrid class="m-container" align="center" gap={10} bind:this={masonryGrid}>
                {#each notes as note}
                    <Note note={note} on:clickedNote={openModal}/>
                {:else}
                    <p>No notes found.</p>
                {/each}
            </MasonryGrid>
            <InfiniteScroll
                    hasMore={newNotesBatch.length}
                    threshold={100}
                    on:loadMore={() => {offset += limit; fetchData()}}/>
        </div>
    </div>

    <Modal bind:this={modal} open={showModal} on:closed={handleModalClose}>
        <div class="modal-header">
            <h5 class="modal-title">Edit Note</h5>
            <button on:click={handleModalClose} type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="newNote">
                <div class="card-title">
                    <input type="text" class="form-control border-0 fw-bolder" placeholder="Titel" name="title"
                           value={currentNote.title}>
                </div>

                <p class="card-text">
                <textarea rows="8" type="text" class="form-control border-0"
                          placeholder="Inhalt" name="content">{currentNote.content}</textarea>
                </p>
            </form>
        </div>
        <div class="modal-footer">
            <button on:click={deleteNote} type="button" class="btn btn-danger">Delete Note</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            <button on:click={handleModalClose} type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

    </Modal>

</main>


<style>
</style>