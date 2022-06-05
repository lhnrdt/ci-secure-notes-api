<script>
    import {categoryStore, selectedCategory} from "../stores";
    import Category from "./Category.svelte";
    import CategoryModal from "./CategoryModal.svelte";

    let categoryModal;

    const handleSelectCategory = (category) => {
        const alreadySelected = $selectedCategory === category;
        $selectedCategory = alreadySelected ? null : category;
    };

    const handleAddCategory = async () => {
        categoryModal.show();
    };

</script>

<div class="d-flex flex-column flex-shrink-0 p-3">
    <h4>Kategorien</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        {#each $categoryStore as category}
            <Category {category}
                      clickFunction={handleSelectCategory}
                      active={(category === $selectedCategory)}
            />
        {/each}
    </ul>
    <button type="button" class="btn btn-secondary mt-2" data-toggle="button" aria-pressed="false"
        on:click={handleAddCategory}
    >
        + Neue Kategorie
    </button>
    <CategoryModal bind:this={categoryModal}/>
</div>
