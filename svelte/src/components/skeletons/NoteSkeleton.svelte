<script>

    import {fade} from 'svelte/transition';

    const getRandomInt = (min, max) => {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min)) + min;
    }
</script>

<div class="col" in:fade={{duration: 200}}>
    <div class="card skeleton-note h-100">
        <div class="card-body">
            <h5 class="skeleton"></h5>
            <p>
                {#each Array(getRandomInt(10, 20)) as _, i}
                    <span class="skeleton" ></span>
                {/each}
            </p>
        </div>
        <div class="card-footer text-muted">
            <span class="skeleton"></span>
        </div>
    </div>
</div>
<style>
    .skeleton-note {
        user-select: none;
    }

    h5.skeleton {
        width: 16ch;
    }

    .skeleton {
        display: inline-block;
        width: 20ch;
        height: 1rem;
        background: #e7e7e7;
    }
</style>