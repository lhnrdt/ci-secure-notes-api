<script>

    import {scale} from 'svelte/transition';
    import {expoInOut} from 'svelte/easing';
    import {createEventDispatcher} from "svelte";
    import {searchQuery} from "../stores";

    const dispatch = createEventDispatcher();

    export let note = {};
    let color, markedContent, markedTitle, markedCategory, regEx;

    $: color = note.color ?? '#fffff';

    // replaces matched words with mark elements when searching
    $: regEx = new RegExp($searchQuery, "ig");
    $: markedContent = note.content.replaceAll(regEx, `<mark class="p-0">$&</mark>`);
    $: markedTitle = note.title?.replaceAll(regEx, `<mark class="p-0">$&</mark>`);
    $: markedCategory = note['category_name']?.replaceAll(regEx, `<mark class="p-0">$&</mark>`);

</script>

<div class="col" in:scale={{duration: 200, easing: expoInOut}}>
    <div class="card note h-100"
         on:click={() => dispatch('noteClicked', {note: note})}
         style:background-color={note.color}
    >
        <div class="card-body">
            {#if (note.title)}
                <h5 class="card-title">
                    {#if $searchQuery}
                        {@html markedTitle}
                    {:else}
                        {note.title}
                    {/if}
                </h5>
            {/if}
            <p>
                {#if $searchQuery}
                    {@html markedContent}
                {:else}
                    {note.content}
                {/if}
            </p>
        </div>
        {#if (note['category_name'])}
            <div class="card-footer text-muted">
                {#if $searchQuery}
                    {@html markedCategory}
                {:else}
                    {note['category_name']}
                {/if}
            </div>
        {/if}
    </div>
</div>
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