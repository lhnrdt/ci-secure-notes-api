<script>
    export let note;

</script>

<div class="card note">
    <div class="card-body">
        {#if (note.title)}
            <h5 class="card-title">
                {note.title}
            </h5>
        {/if}
        {note.content}
    </div>
</div>

<style>
    .note {
        width: 240px;
        transition: 300ms;
        user-select: none;
    }

    .note:hover {
        box-shadow: 1px 2px 0 rgb(60 64 67 / 30%), 0 1px 3px 1px rgb(60 64 67 / 15%);;
    }
</style>