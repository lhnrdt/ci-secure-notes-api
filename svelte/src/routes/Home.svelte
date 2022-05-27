<script>
    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import {noteStore} from "../stores";
    import Note from "../components/Note.svelte";
    import InfiniteScroll from "../components/InfiniteScroll.svelte";
    import EditModal from "../components/EditModal.svelte";
    import AddButton from "../components/AddButton.svelte";
    import Sidebar from "../components/Sidebar.svelte";


    // modal
    let modal;

    // infinite scroll
    const limit = 20;
    let offset = 0;

    // data
    async function fetchData() {
        const data = await DataService.getResource(`/api/notes?offset=${offset}&limit=${limit}`);
        if (data.note.length) $noteStore = [...$noteStore, ...data.note];
    }

    const saveNewNote = async () => {
        await DataService.createNote(new FormData(document.getElementById('newNote')))
    }

    onMount(() => {
        fetchData();
        console.log($noteStore)
    })

</script>

<main class="container-fluid">
    <div class="row">
        <div class="col-auto">
        </div>
        <div class="col">
            <div class="container">
                <div class="row">
                    <h3>Alle Notizen</h3>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-3 position-relative">
                    {#each $noteStore as note}
                        <div class="col">
                            <Note
                                    note={note}
                                    on:noteClicked={(e) => modal.show(e.detail.note)}
                            />
                        </div>
                    {/each}
                    <InfiniteScroll
                            threshold={100}
                            on:loadMore={() => {offset += limit; fetchData()}}/>
                </div>

                <EditModal bind:this={modal}/>
                <AddButton on:click={() => modal.show({})}/>
            </div>

        </div>
    </div>
</main>


<style>
</style>