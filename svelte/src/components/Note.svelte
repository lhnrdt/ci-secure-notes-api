<script>

    import {createEventDispatcher} from "svelte";
    import {searchQuery} from "../stores";

    const dispatch = createEventDispatcher();

    export let note;
    let color;
    let lowercaseSearchQuery;
    let matches;

    $: color = note.color ?? '#fffff';
    $: {
        lowercaseSearchQuery = $searchQuery.toLowerCase();
        if (note.title) matches ||= note.title.toLowerCase().includes(lowercaseSearchQuery);
        if (note.category_name) matches ||= note.category_name.toLowerCase().includes(lowercaseSearchQuery);
        matches ||= note.content.toLowerCase().includes(lowercaseSearchQuery);
        console.log(lowercaseSearchQuery, matches)
    }

</script>

{#if matches}
    <div class="card note h-100"
         on:click={() => dispatch('noteClicked', {note: note})}
         style:background-color={note.color}
    >
        <div class="card-body">
            {#if (note.title)}
                <h5 class="card-title">
                    {note.title}
                </h5>
            {/if}
            <p>{note.content}</p>
        </div>
        {#if (note['category_name'])}
            <div class="card-footer text-muted">
                {note['category_name']}
            </div>
        {/if}
    </div>
{/if}

<style>
    .note {
        transition: 300ms;
        user-select: none;
    }

    .note:hover {
        transform: scale(1.02);
        box-shadow: 1px 2px 0 rgb(60 64 67 / 30%), 0 1px 3px 1px rgb(60 64 67 / 15%);
        cursor: pointer;
    }

</style>