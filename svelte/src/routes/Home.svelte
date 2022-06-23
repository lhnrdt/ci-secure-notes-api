<script>
    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import {noteStore, categoryStore, selectedCategory, searchQuery} from "../stores";
    import Note from "../components/Note.svelte";
    import NoteModal from "../components/NoteModal.svelte";
    import AddButton from "../components/AddButton.svelte";
    import Sidebar from "../components/Sidebar.svelte";
    import CategoryModal from "../components/CategoryModal.svelte";
    import {navigate} from "svelte-navigator";
    import Navbar from "../components/Navbar.svelte";

    // modals
    let noteModal;
    let categoryModal;

    const emptyNote = {
        category_id: 'NULL',
        title: '',
        content: '',
    }

    // infinite scroll
    const limit = 5;
    let offset = 0;

    const onScroll = () => {
        const {
            scrollTop,
            scrollHeight,
            clientHeight
        } = document.documentElement;

        if (scrollTop + clientHeight >= scrollHeight) {
            fetchNoteBatch()
        }
    }

    const fetchNoteBatch = async () => {
        console.log("fetching notes", offset, '-', offset + limit);
        const data = await DataService.getResource(`/api/notes?offset=${offset}&limit=${limit}`);
        $noteStore = [...$noteStore, ...data.note];
        offset += limit;
        return !!data.note.length;
    }

    const fetchCategories = async () => {
        const res = await DataService.getResource(`/api/categories`);
        $categoryStore = res.categories;
    }

    const getNotesByCategory = async () => {
        $searchQuery = "";
        return filteredNotes = $noteStore.filter(note => {
            return note['category_id'] === $selectedCategory?.id
        });
    }

    const getNotesByQuery = async () => {
        $selectedCategory = null;
        return filteredNotes = $noteStore.filter(note => {
            let lowercaseSearchQuery = $searchQuery.toLowerCase();
            return note.title?.toLowerCase().includes(lowercaseSearchQuery)
                || note.category_name?.toLowerCase().includes(lowercaseSearchQuery)
                || note.content?.toLowerCase().includes(lowercaseSearchQuery);
        });
    }

    const fillScreen = () => {
        const isScrolled = window.innerHeight < document.body.clientHeight;
        if (!isScrolled) {
            fetchNoteBatch().then((hasResult) => {
                if (hasResult) fillScreen();
            });
        } else {
            fetchNoteBatch()
        }
    }

    let filtered;
    let filteredNotes = [];

    $: filtered = !!$selectedCategory || !!$searchQuery.length;
    $: if ($selectedCategory) getNotesByCategory();
    $: if ($searchQuery.length) getNotesByQuery();

    if (!localStorage.getItem('user')) {
        navigate('/login');
    } else {
        onMount(async () => {
            await fetchNoteBatch();
            await fetchCategories();
            await fillScreen();
        });
    }
</script>

<svelte:window on:scroll={onScroll} on:resize={fillScreen}/>

<Navbar/>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-3 px-sm-2 px-0 bg-light position-fixed">
            <Sidebar modal={categoryModal}/>
        </div>
        <div class="col offset-3">
            <main class="container my-3 px-0 px-md-3">
                <div class="row">
                    {#if ($selectedCategory)}
                        <h3>{$selectedCategory.name}</h3>
                    {:else}
                        <h3>Alle Notizen</h3>
                    {/if}
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-3 position-relative">
                    {#if filtered}
                        {#each filteredNotes as note}
                            <div class="col">
                                <Note note={note}
                                      on:noteClicked={(e) => noteModal.show(e.detail.note)}
                                />
                            </div>
                        {/each}
                    {:else}
                        {#each $noteStore as note}
                            <div class="col">
                                <Note note={note}
                                      on:noteClicked={(e) => noteModal.show(e.detail.note)}
                                />
                            </div>
                        {:else}
                            <p class="text-muted">Keine Notizen.</p>
                        {/each}
                    {/if}
                </div>

                <AddButton text={"+ Neue Notiz"} on:click={() => noteModal.show(emptyNote)}/>
            </main>

        </div>
    </div>
</div>
<NoteModal bind:this={noteModal} noteData={emptyNote}/>
<CategoryModal bind:this={categoryModal}/>
