<script>

    import {AuthService} from '../services/AuthService';
    import {navigate, link, useLocation} from "svelte-navigator";
    import {noteStore, categoryStore, selectedCategory} from "../stores";
    import {PersonFill} from 'svelte-bootstrap-icons';
    import {Utils} from "../services/Utils";

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
                    <div class="position-relative ms-auto">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <PersonFill/>
                            <strong>{user.username}</strong>
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