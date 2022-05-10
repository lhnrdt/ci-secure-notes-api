<script>

    import {onMount} from "svelte";
    import {DataService} from "../services/DataService";
    import Note from "../components/Note.svelte";

    let notes = [];

    onMount(async () => {
        const notesRes = await DataService.getUserNotes();
        notes = await notesRes['note'];
    })

</script>

<main class="container-fluid">

    {#each notes as note}
        <Note note={note}/>
    {:else}
        <p>No notes found.</p>
    {/each}

</main>