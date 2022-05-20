<script>
    import {onDestroy, createEventDispatcher} from "svelte";

    export let threshold = 0;
    export let hasMore = true;

    const dispatch = createEventDispatcher();
    let isLoadMore = false;

    $: {
        document.addEventListener("scroll", onScroll);
        document.addEventListener("resize", onScroll);
    }

    const onScroll = e => {
        const element = e.target.documentElement;
        const offset = element.scrollHeight - element.clientHeight - element.scrollTop;
        if (offset <= threshold) {
            if (!isLoadMore && hasMore) {
                dispatch("loadMore");
            }
            isLoadMore = true;
        } else {
            isLoadMore = false;
        }
    };

    onDestroy(() => {

        document.removeEventListener("scroll", onScroll);
        document.removeEventListener("resize", onScroll);

    });
</script>

<div style="width:0px"/>