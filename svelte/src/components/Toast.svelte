<script>

    import {Toast, ToastBody, ToastHeader} from "sveltestrap";
    import {toasts} from "../stores/notficationStores";


    let Header;
</script>

<div class="toasts">
    {#each $toasts as toast (toast.id)}
        <Toast class="me-1">
            <ToastHeader>{toast.header}</ToastHeader>
            <ToastBody>
                {toast.message}
            </ToastBody>
        </Toast>
    {/each}
</div>

<style>
    .toasts {
        position: fixed;
        top: 0;
        right: 0;
        margin: 1em;
    }
</style>