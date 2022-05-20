<script>

    import {createEventDispatcher} from "svelte";

    export let open = false;
    export let showBackdrop = true;

    let dispatch = createEventDispatcher();

    export const modalClose = () => {
        open = false;
        dispatch('closed');
    }

    export let title = '';

</script>
{#if open}
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <slot/>
            </div>
        </div>
    </div>
    {#if showBackdrop}
        <div class="modal-backdrop show"></div>
    {/if}
{ /if}

<style>
    .modal {
        display: block;
    }
</style>