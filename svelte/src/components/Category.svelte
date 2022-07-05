<script>
    import {DashCircleFill, PencilFill} from "svelte-bootstrap-icons";
    import {DataService} from "../services/DataService";
    import {selectedCategory, categoryStore, noteStore} from "../stores";
    import {createEventDispatcher} from "svelte";

    export let category;
    export let active = false;
    export let clickFunction = () => {};

    export let showEditControls = false;
    const dispatch = createEventDispatcher();

    // handle deleting a category
    const deleteCategory = (category) => {
        if (confirm('Wirklich löschen?')) {
            // delete from database
            DataService.deleteResource(`/api/categories/${category.id}`);

            // delete from currently shown categories
            $categoryStore = $categoryStore.filter(cat => cat.id !== category.id);

            // remove category from notes
            $noteStore.forEach(n => {
                if (n.category_id === category.id) {
                    n.category_id = null;
                    n.category_name = null;
                    n.color = null;
                }
            })
        }
    }

    const handleCategoryDelete = (e) => {
        e.stopPropagation();
        deleteCategory(category);
        $selectedCategory = null;
    };

    const handleCategoryEdit = (e) => {
        e.stopPropagation();
        // dispatch event to inform sidebar to open modal
        dispatch('editCategory', {category: category});
    }

</script>

<li class="nav-item w-100 clickable"
    on:click={() => clickFunction(category)}
>
    <a class="nav-link align-middle text-md-start px-2 py-3 py-md-2
    d-flex align-items-center"
       class:active aria-current="page"
       title={category.name}
    >
        <div class="d-flex align-items-center w-100 justify-content-center justify-content-md-start">
            <div class="controls d-flex align-items-center"
                 on:click={handleCategoryDelete}
                 class:hidden={!showEditControls}
                 class:me-2={showEditControls}
                 title="Löschen"
            >
                <DashCircleFill fill="red"/>
            </div>
            <div class="edit controls d-flex align-items-center"
                 on:click={handleCategoryEdit}
                 class:hidden={!showEditControls}
                 class:me-2={showEditControls}
                 title="Bearbeiten"
            >
                <PencilFill fill="var(--bs-secondary)"/>
            </div>
            <div class="fs-4 color-circle flex-shrink-0" style:background-color={category.color}></div>
            <span class="ms-1 d-none d-md-inline text-truncate flex-shrink-1">{category.name}</span>
        </div>
    </a>

</li>

<style>
    .nav-item:hover {
        background: lightgray;
        border-radius: 4px;
    }

    .controls {
        width: 14px;
        transition: transform 200ms ease,
        width 200ms ease,
        filter 200ms ease;
    }

    .controls:hover {
        filter: brightness(50%);
    }

    .edit {
        width: 20px;
        height: 20px;
        transition: transform 200ms ease;
    }

    .edit:hover {
        transform: scale(1.3);
    }

    .edit svg {
        width: 14px;
    }

    .clickable {
        cursor: pointer;
    }

    .color-circle {
        border: 1pt solid var(--bs-secondary);
        overflow: hidden;
        line-height: 18px;
        display: inline-block;
        border-radius: 50%;
        width: 20px;
        height: 20px;
    }

    .hidden {
        width: 0;
    }
</style>