<script>

    import {createEventDispatcher} from "svelte";
    const dispatch = createEventDispatcher();

    export let note;

</script>

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
    <div class="card-footer text-muted">
        {note['category_name']}
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