<script>
    import {AuthService} from "../services/AuthService";
    import {link, navigate} from "svelte-navigator";
    import Navbar from "../components/Navbar.svelte";

    const handleRegister = async (e) => {
        let formData = new FormData(e.target);
        await AuthService.register(formData);
        navigate('/');
    }

</script>

<Navbar/>
<main>
    <form on:submit|preventDefault={handleRegister} class="form-signin text-center">
        <h1 class="h3 mb-3 font-weight-normal">Registrieren</h1>
        <label for="inputUsername" class="sr-only">Email Adresse</label>
        <input type="text" id="inputUsername" name="username" class="form-control"
               placeholder="Username" required>

        <label for="inputEmail" class="sr-only">Passwort</label>
        <input type="email" id="inputEmail" name="email" class="form-control"
               placeholder="Email Adresse" required>

        <label for="inputPassword" class="sr-only">Passwort</label>
        <input type="password" id="inputPassword" name="password" class="form-control"
               placeholder="Passwort" required>

        <label for="inputPasswordRepeat" class="sr-only">Passwort wiederholen</label>
        <input type="password" id="inputPasswordRepeat" class="form-control"
               placeholder="Passwort wiederholen" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Registrieren</button>
        <p class="mt-1">
            <a href="/login" use:link>Login</a>
        </p>
        <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
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