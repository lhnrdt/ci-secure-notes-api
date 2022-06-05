<script>
    import {Trash} from "svelte-bootstrap-icons";
    import {DataService} from "../services/DataService";
    import {selectedCategory, categoryStore, noteStore} from "../stores";

    export let category;
    export let active = false;
    export let clickFunction = () => {
    };

    let displayDelete = false;

    const handleDisplayDelete = () => {
        displayDelete = true;
    }

    const handleHideDelete = () => {
        displayDelete = false;
    }

    const deleteCategory = async (category) => {
        if (confirm('Wirklich löschen?')) {
            await DataService.deleteResource(`/api/categories/${category.id}`);
            $categoryStore = $categoryStore.filter(cat => cat.id !== category.id);
            $noteStore.forEach(n => {
                if (n.category_id === category.id) {
                    n.category_id = null;
                    n.category_name = null;
                    n.color = null;
                }
            })
        }
    }

    const handleNoteDelete = (e) => {
        e.stopPropagation();
        deleteCategory(category);
        $selectedCategory = null;
    };

</script>

<li class="nav-item"
    on:click={() => clickFunction(category)}
    on:mouseenter={handleDisplayDelete}
    on:mouseleave={handleHideDelete}
>
    <a href="#" class="nav-link" class:active aria-current="page">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center me-2">
                <span class="color-circle" style:background-color={category.color}></span>
                {category.name}
            </div>
            <div class="controls" on:click={handleNoteDelete}>
                {#if (displayDelete)}
                    <Trash fill="red"/>
                {/if}
            </div>
        </div>
    </a>
</li>

<style>
    .nav-item:hover {
        background: lightgray;
    }

    .controls {
        width: 20px;
        transition: transform 200ms ease;
    }

    .controls:hover {
        transform: scale(1.5);
    }

    .color-circle {
        display: inline-block;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        margin-inline: 1em;
    }
</style>