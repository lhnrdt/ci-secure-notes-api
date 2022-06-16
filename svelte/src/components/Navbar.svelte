<script>

    import {AuthService} from '../services/AuthService';
    import {Utils} from "../services/Utils";
    import {noteStore, categoryStore, selectedCategory, searchQuery} from "../stores";
    import {navigate, link, useLocation} from "svelte-navigator";
    import {PersonFill, Search} from 'svelte-bootstrap-icons';

    const location = useLocation();
    const user = Utils.getUser();

    let showLoginNav;
    $: showLoginNav = ['/login', '/register'].includes($location.pathname);


    async function handleLogout() {
        $noteStore = [];
        $categoryStore = [];
        $selectedCategory = null;
        await AuthService.logout();
        await navigate('/login');
    }

</script>
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark shadow" aria-label="Fourth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Notizen</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04"
                    aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample04">
                {#if (showLoginNav)}
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link" class:active={$location.pathname === '/login'} use:link href="/login">
                                Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" class:active={$location.pathname === '/register'} use:link
                               href="/register"> Register</a>
                        </li>
                    </ul>
                {:else}
                    <div class="input-group me-3">
                        <button
                                class="input-group-text text-white border-0 bg-transparent"
                        >
                            <Search/>
                        </button>
                        <input
                                class="form-control border-0 border-bottom bg-dark text-white"
                                autocomplete="off"
                                type="search"
                                placeholder="Suche"
                                bind:value={$searchQuery}
                        />
                    </div>
                    <div class="position-relative ms-auto">
                        <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-inline-block">
                                <PersonFill/>
                            </div>
                            <div class="ms-2">
                                <strong>{user?.username ?? 'not logged in'}</strong>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow position-absolute "
                            aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" on:click|preventDefault={handleLogout} href="/">Sign out</a>
                            </li>
                        </ul>
                    </div>
                {/if}
            </div>
        </div>
    </nav>
</header>
