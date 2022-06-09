<script>
    import {X} from "svelte-bootstrap-icons";
    import {DataService} from "../services/DataService";
    import {selectedCategory, categoryStore, noteStore} from "../stores";
    import {createEventDispatcher} from "svelte";

    export let category;
    export let active = false;
    export let clickFunction = () => {
    };

    export let showEditControls = false;
    const dispatch = createEventDispatcher();

    const deleteCategory = (category) => {
        if (confirm('Wirklich löschen?')) {
            DataService.deleteResource(`/api/categories/${category.id}`);
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
        dispatch('noteDeleted');
        deleteCategory(category);
        $selectedCategory = null;
    };

</script>

<li class="nav-item w-100 clickable"
    on:click={() => clickFunction(category)}
>
    <a class="nav-link align-middle text-md-start px-2 py-3 py-md-2 text-nowrap
    d-flex align-items-center justify-content-center justify-content-md-between"
       class:active aria-current="page"
    >
        <div class:shaking={showEditControls} class="d-flex align-items-center">
            <div class="fs-4 color-circle" style:background-color={category.color}></div>
            <span class="ms-1 d-none d-md-inline">{category.name}</span>
        </div>
        <div class="controls d-flex align-items-center"
             on:click={handleNoteDelete}
             class:d-none={!showEditControls}
        >
            {#if (showEditControls)}
                <X fill="red"/>
            {/if}
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
        transform: scale(1.4);
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
        width: 18px;
        height: 18px;
    }

    .shaking {
        --rotate-amount: 2deg;
        animation: shaking infinite 180ms;
    }

    @keyframes shaking {

        25% {
            transform: rotate(calc(var(--rotate-amount) * -1)) translateX(-1px);
        }

        75% {
            transform: rotate(var(--rotate-amount)) translateX(1px);
        }

        0%, 100% {
            transform: rotate(0) translateX(0);
        }
    }
</style>