<script>
    import {categoryStore, selectedCategory} from "../stores";
    import Category from "./Category.svelte";
    import {Pencil, BookmarkPlus} from 'svelte-bootstrap-icons';

    export let modal;
    let showEditControls = false;

    const handleSelectCategory = (category) => {
        const alreadySelected = $selectedCategory === category;
        $selectedCategory = alreadySelected ? null : category;
    };

    const handleAddCategory = async () => {
        modal.show();
    };

    const toggleEditControls = () => {
        showEditControls = !showEditControls;
    }
    const handleEditCategory = (e) => {
        modal.show(e.detail.category);
    };

</script>

<div class="d-flex flex-column align-items-center align-items-md-start pt-3 px-1 min-vh-100">
    <div class="d-none d-md-flex align-items-center pb-3 mb-md-0 me-lg-auto text-muted text-decoration-none">
        <span class="fs-5">Kategorien</span>
    </div>
    <ul class="nav nav-pills flex-column mb-0 align-items-center align-items-md-start w-100 overflow-auto">
        {#each $categoryStore as category}
            <Category {category}
                      on:editCategory={handleEditCategory}
                      clickFunction={handleSelectCategory}
                      active={(category === $selectedCategory)}
                      {showEditControls}
            />
        {:else}
            <p>keine Kategorien</p>
        {/each}
    </ul>
    <hr class="w-100 mb-0">
    <div class="d-flex flex-wrap justify-content-evenly w-100">
        <button type="button" class="btn mt-2 hover-light" data-toggle="tooltip" data-placement="top"
                aria-pressed="false"
                title="Kategorien bearbeiten"
                on:click={toggleEditControls}
        >
            <Pencil/>
        </button>
        <button type="button" class="btn btn-sq mt-2 hover-light" data-toggle="tooltip" data-placement="top"
                aria-pressed="false"
                title="Kategorie hinzufügen"
                on:click={handleAddCategory}
        >
            <BookmarkPlus/>
        </button>
    </div>
</div>

<style>
    .hover-light:hover {
        background: lightgray;
    }

    hr {
        color: var(--bs-secondary);
        margin-inline-start: 0;
        margin-inline-end: 0;
    }

    .skeleton {
        background: lightgray;
        width: 5em;
    }
</style>