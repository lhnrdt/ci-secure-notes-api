<script>
    import {AuthService} from "../services/AuthService";
    import {link, navigate} from "svelte-navigator";
    import {toasts} from "svelte-toasts";
    import Navbar from "../components/Navbar.svelte";

    /**
     * Sends authentication request and redirects to home
     * */
    const handleLogin = async (e) => {
        let formData = new FormData(e.target);

        try {
            await AuthService.login(formData);
            navigate('/');
        } catch (e) {
            toasts.error(e.message);
        }
    }

</script>

<Navbar/>
<main>
    <form on:submit|preventDefault={handleLogin} class="form-signin text-center">
        <h1 class="h3 mb-3 font-weight-normal">Login</h1>

        <label for="inputEmail" class="sr-only">Passwort</label>
        <input type="email" id="inputEmail" name="email" class="form-control"
               placeholder="Email Adresse" required>

        <label for="inputPassword" class="sr-only">Passwort</label>
        <input type="password" id="inputPassword" name="password" class="form-control"
               placeholder="Passwort" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <p class="mt-1">
            <a href="/register" use:link>Register</a>
        </p>
        <p class="mt-5 mb-3 text-muted">Version 1.0.0 - developed by J Lehnerdt</p>
    </form>
</main>

<style>
    form {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }

    form .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }

    form .form-control:focus {
        z-index: 2;
    }

    form input:first-of-type {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    form input:not(:first-of-type):not(:last-of-type) {
        border-radius: 0;
        margin-bottom: -1px;
    }

    form input:last-of-type {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>