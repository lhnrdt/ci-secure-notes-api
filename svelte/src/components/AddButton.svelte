<script>
    import {Plus} from "svelte-bootstrap-icons";
</script>

<div class="position-fixed button-container" on:click>
    <button
            type="button"
            class="btn btn-primary shadow rounded-circle d-flex align-items-center justify-content-center m-0 p-2"
            data-toggle="button"
            aria-pressed="false"
    >
        <Plus width={30} height={30}/>
    </button>
</div>

<style>
    .button-container {
        bottom: 2em;
        right: 2em;
    }
</style>