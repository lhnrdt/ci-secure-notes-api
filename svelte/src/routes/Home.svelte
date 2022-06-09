<script>
    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import {noteStore, categoryStore, selectedCategory} from "../stores";
    import Note from "../components/Note.svelte";
    import InfiniteScroll from "../components/InfiniteScroll.svelte";
    import NoteModal from "../components/NoteModal.svelte";
    import AddButton from "../components/AddButton.svelte";
    import Sidebar from "../components/Sidebar.svelte";


    // modal
    let modal;

    const emptyNote = {
        category_id: 'NULL',
        title: '',
        content: '',
    }

    // infinite scroll
    const limit = 20;
    let offset = 0;

    // data
    async function fetchNoteBatch() {
        const data = await DataService.getResource(`/api/notes?offset=${offset}&limit=${limit}`);
        $noteStore = [...$noteStore, ...data.note];
    }

    async function fetchCategories() {
        const res = await DataService.getResource(`/api/categories`);
        $categoryStore = res.categories;
    }

    $: filteredNotes = $noteStore.filter(note => {
        if (!$selectedCategory) return true;
        else return note['category_id'] === $selectedCategory?.id
    });

    onMount(() => {
        fetchNoteBatch();
        fetchCategories();
    })

</script>

<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-2 px-sm-2 px-0 bg-light position-fixed">
            <Sidebar/>
        </div>
        <div class="col offset-2">
            <main class="container my-3 px-0 px-md-3">
                <div class="row">
                    {#if ($selectedCategory)}
                        <h3>{$selectedCategory.name}</h3>
                    {:else}
                        <h3>Alle Notizen</h3>
                    {/if}
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-3 position-relative">
                    {#each filteredNotes as note}
                        <div class="col">
                            <Note
                                    note={note}
                                    on:noteClicked={(e) => modal.show(e.detail.note)}
                            />
                        </div>
                    {:else}
                        <p class="text-muted">Keine Notizen.</p>
                    {/each}
                    <InfiniteScroll
                            threshold={100}
                            on:loadMore={() => {offset += limit; fetchNoteBatch()}}/>
                </div>

                <NoteModal bind:this={modal} noteData={emptyNote}/>
                <AddButton text={"+ Neue Notiz"} on:click={() => modal.show(emptyNote)}/>
            </main>

        </div>
    </div>
</div>
