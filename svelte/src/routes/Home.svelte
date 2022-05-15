<script>

    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import Note from "../components/Note.svelte";
    import {MasonryGrid} from "@egjs/svelte-grid";
    import {clickOutside} from "./clickOutside";
    import InfiniteScroll from "../components/InfiniteScroll.svelte";

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

    let newNoteOpen = false;
    onMount(() => {
        fetchData();
    })

    const openNewNote = () => {
        newNoteOpen = true;
    };

    const closeNewNote = () => {
        newNoteOpen = false;
    };

    const saveNewNote = async () => {

        const formData = new FormData(document.getElementById('newNote'));
        const user = JSON.parse(localStorage.getItem('user'));

        if (!!formData.get('title') || !!formData.get('content')) {
            formData.append('user_id', user.id);
            formData.append('category_id', '1');
            await DataService.postResource('/api/notes', formData);
        }

        closeNewNote();
    }

</script>

<main class="container-fluid mt-5">

    <div class="row justify-content-center">
        <div class="col-6 mb-3">
            <div use:clickOutside  data-open={newNoteOpen}
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
                <MasonryGrid class="m-container" align="center" gap={10} id="test">
                    {#each notes as note}
                        <Note note={note}/>
                    {:else}
                        <p>No notes found.</p>
                    {/each}
                </MasonryGrid>
                <InfiniteScroll
                        hasMore={newNotesBatch.length}
                        threshold={100}
                        on:loadMore={() => {offset += limit; fetchData()}} />
        </div>
    </div>
</main>


<style>
    .scroll-container {
        overflow-x: scroll;
        height: 90vh;
    }
</style>