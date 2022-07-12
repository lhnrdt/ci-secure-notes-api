<script>

    import {scale} from 'svelte/transition';
    import {expoInOut} from 'svelte/easing';

    const getRandomInt = (min, max) => {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min)) + min;
    }
</script>

<div class="col" in:scale={{duration: 200, easing: expoInOut}}>
    <div class="card skeleton-note h-100">
        <div class="card-body">
            <h5 class="skeleton shine"></h5>
            <p>
                {#each Array(getRandomInt(10, 20)) as _, i}
                    <span class="skeleton shine"></span>
                {/each}
            </p>
        </div>
        <div class="card-footer text-muted">
            <span class="skeleton shine"></span>
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
        display: block;
        width: 20ch;
        height: 1rem;
        background: #e7e7e7;
    }

    .shine {
        background-image: linear-gradient(90deg, #e7e7e7, #ffffff, #e7e7e7);
        animation: shine 1500ms ease-in-out infinite;
        background-size: 100% 100%;
        overflow: hidden;
    }

    @keyframes shine {
        0% {
            background-position-x: 0;
        }
        50% {
            background-position-x: 100px;
        }
        100% {
            background-position-x: 0;
        }
    }
</style>