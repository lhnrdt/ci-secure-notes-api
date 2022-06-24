<script>
    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import {
        noteStore,
        categoryStore,
        selectedCategory,
        searchQuery,
        NOTES_PER_REQUEST, timeout
    } from "../stores";
    import Note from "../components/Note.svelte";
    import NoteModal from "../components/NoteModal.svelte";
    import AddButton from "../components/AddButton.svelte";
    import Sidebar from "../components/Sidebar.svelte";
    import CategoryModal from "../components/CategoryModal.svelte";
    import {navigate} from "svelte-navigator";
    import Navbar from "../components/Navbar.svelte";
    import NoteSkeleton from "../components/skeletons/NoteSkeleton.svelte";

    // modals
    let noteModal;
    let categoryModal;
    let hasMore = true;
    let loadingMore = false;


    const emptyNote = {
        category_id: 'NULL',
        title: '',
        content: '',
    }

    const onScroll = async () => {
        const {
            scrollTop,
            scrollHeight,
            clientHeight
        } = document.documentElement;
        const offset = 1;

        if (hasMore && !loadingMore && scrollTop + clientHeight >= scrollHeight - offset) {
            loadingMore = true;
            const res = await noteStore.getMore($searchQuery, $selectedCategory?.id);
            if (!!res.note.length) noteStore.add(res.note);
            hasMore = res['hasMore']
            loadingMore = false;
        }
    }


    const fetchCategories = () => {
        $categoryStore = DataService.getResource(`/api/categories`).then(json => json.categories);
    }

    const fillScreen = async () => {
        const isScrolled = window.innerHeight < document.body.clientHeight;
        if (!isScrolled) {
            const res = await noteStore.getMore($searchQuery, $selectedCategory?.id);
            if (!!res.note.length) noteStore.add(res.note);
            if (res['hasMore']) await fillScreen();
            loadingMore = false;
        }
    }

    const initNotes = async (searchQuery, categoryID) => {
        loadingMore = true;
        await noteStore.init(searchQuery, categoryID)
            .then(() => loadingMore = false)
            .then(fillScreen);
    }

    $:  initNotes($searchQuery, $selectedCategory?.id);


    if (!localStorage.getItem('user')) {
        navigate('/login');
    } else {
        onMount(async () => {
            await fetchCategories();
        });
    }
</script>

<svelte:window on:scroll={onScroll} on:resize={fillScreen}/>

<Navbar/>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-2 col-md-3 col-lg-2 px-sm-2 px-0 bg-light position-fixed">
            <Sidebar modal={categoryModal}/>
        </div>
        <div class="col offset-2 offset-md-3 offset-lg-2">
            <main class="container my-3 px-0 px-md-3">
                <div class="row">
                    {#if ($selectedCategory)}
                        <h3>{$selectedCategory.name}</h3>
                    {:else}
                        <h3>Alle Notizen</h3>
                    {/if}
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-3 position-relative">
                    {#if (!$timeout)}
                        {#each $noteStore as noteBatch}
                            {#each noteBatch as note}
                                <Note note={note} on:noteClicked={(e) => noteModal.show(e.detail.note)}/>
                            {/each}
                        {:else}
                            {#if !loadingMore} <p class="text-muted">Keine Notizen.</p> {/if}
                        {/each}
                    {/if}
                    {#if loadingMore || $timeout}
                        {#each Array(NOTES_PER_REQUEST) as _}
                            <NoteSkeleton/>
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
