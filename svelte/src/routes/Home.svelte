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
    const LIMIT_NOTES_PER_REQUEST = 5;
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

    const clearNotes = () => {
        offset = 0;
        $noteStore = [];
    }

    const fetchNoteBatch = async () => {
        console.log("fetching notes", offset, '-', offset + LIMIT_NOTES_PER_REQUEST);
        let url = `/api/notes?offset=${offset}&limit=${LIMIT_NOTES_PER_REQUEST}`;

        if ($searchQuery.length) url += `&q=${$searchQuery}`;
        if ($selectedCategory) url += `&category=${$selectedCategory.id}`;

        const data = await DataService.getResource(url);
        $noteStore = [...$noteStore, ...data.note];
        offset += LIMIT_NOTES_PER_REQUEST;
        return !!data.note.length;
    }

    const fetchCategories = async () => {
        const res = await DataService.getResource(`/api/categories`);
        $categoryStore = res.categories;
    }

    const fillScreen = () => {
        const isScrolled = window.innerHeight < document.body.clientHeight;
        console.log('isScrolled', isScrolled)
        if (!isScrolled) {
            fetchNoteBatch().then((hasResult) => {
                if (hasResult) fillScreen();
            });
        } else {
            fetchNoteBatch();
        }
    }

    $:  {
        $selectedCategory;
        $searchQuery;
        clearNotes();
        fetchNoteBatch().then(fillScreen);
    }

    if (!localStorage.getItem('user')) {
        navigate('/login');
    } else {
        onMount(() => {
            fetchCategories();
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
                    {#each $noteStore as note}
                        <Note note={note}
                              on:noteClicked={(e) => noteModal.show(e.detail.note)}
                        />
                    {:else}
                        <p class="text-muted">Keine Notizen.</p>
                    {/each}
                </div>

                <AddButton text={"+ Neue Notiz"} on:click={() => noteModal.show(emptyNote)}/>
            </main>

        </div>
    </div>
</div>
<NoteModal bind:this={noteModal} noteData={emptyNote}/>
<CategoryModal bind:this={categoryModal}/>
