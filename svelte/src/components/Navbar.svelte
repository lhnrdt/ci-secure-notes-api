<script>

    import {AuthService} from '../services/AuthService';
    import {navigate, link} from "svelte-navigator";
    import {Utils} from "../services/Utils";

    export let loggedIn = false;
    const user = Utils.getUser();

    async function handleLogout() {
        await AuthService.logout();
        await navigate('/login');
    }
</script>
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" aria-label="Fourth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Notizen</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04"
                    aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    {#if (!loggedIn)}
                        <li class="nav-item">
                            <a class="nav-link" use:link href="/login"> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" use:link href="/register"> Register</a>
                        </li>
                    {/if}
                </ul>
                {#if (loggedIn)}
                    <div class="position-relative">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://picsum.photos/200" alt="" width="32" height="32" class="rounded-circle me-2">
                            <strong>{user.username}</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow position-absolute" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" on:click|preventDefault={handleLogout} href="/">Sign out</a></li>
                        </ul>
                    </div>
                {/if}
            </div>
        </div>
    </nav>
</header>