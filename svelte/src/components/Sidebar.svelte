<script>
    import {categoryStore, selectedCategory} from "../stores";
    import Category from "./Category.svelte";
    import CategoryModal from "./CategoryModal.svelte";
    import {Pencil, Plus} from 'svelte-bootstrap-icons';

    let categoryModal;
    let showEditControls = false;

    const handleSelectCategory = (category) => {
        const alreadySelected = $selectedCategory === category;
        $selectedCategory = alreadySelected ? null : category;
    };

    const handleAddCategory = async () => {
        categoryModal.show();
    };

    const handleEditOn = () => {
        showEditControls = !showEditControls;
    }

</script>

<div class="d-flex flex-column align-items-center align-items-md-start px-1 pt-1 text-white min-vh-100">
    <div class="d-none d-md-flex align-items-center pb-3 mb-md-0 me-lg-auto text-muted text-decoration-none">
        <span class="fs-5">Kategorien</span>
    </div>
    <ul class="nav nav-pills flex-column mb-0 align-items-center align-items-md-start w-100 overflow-auto">
        {#each $categoryStore as category}
            <Category {category}
                      clickFunction={handleSelectCategory}
                      active={(category === $selectedCategory)}
                      {showEditControls}
            />
        {/each}
    </ul>
    <hr class="w-100 mb-0">
    <div class="d-flex flex-wrap justify-content-evenly w-100">
        <button type="button" class="btn mt-2 hover-light me-1" data-toggle="button" aria-pressed="false"
                on:click={handleEditOn}
        >
            <Pencil/>
        </button>
        <button type="button" class="btn mt-2 hover-light" data-toggle="button" aria-pressed="false"
                on:click={handleAddCategory}
        >
            <Plus/>
        </button>
    </div>
    <CategoryModal bind:this={categoryModal}/>
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
</style>