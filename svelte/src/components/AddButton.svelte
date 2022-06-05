<script>
    export let text = '+';
</script>

<div class="position-fixed button-container" on:click>
    <button type="button" class="btn btn-primary" data-toggle="button" aria-pressed="false">
        {text}
    </button>
</div>

<style>
    .button-container {
        bottom: 2em;
        right: 2em;
    }
</style>