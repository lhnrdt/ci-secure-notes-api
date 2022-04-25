<script lang="ts">
    import {navigate} from "svelte-navigator";

    let username = '', email = '', password = '', passwordRepeat = '';
    let rememberMe = false;
    const submit = async (e) => {
        const formData = new FormData(e.target);

        await fetch('/auth/register', {
            method: 'POST',
            body: formData
        }).then(res => res.json())
            .then(json => console.log(json));

        await navigate('/');
    }

</script>

<form on:submit|preventDefault={submit} class="form-signin text-center">
    <img class="mb-4 rounded-circle" src="https://picsum.photos/150" alt width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal">Registrieren</h1>
    <label for="inputUsername" class="sr-only">Email Adresse</label>
    <input bind:value={username} type="text" id="inputUsername" name="username" class="form-control"
           placeholder="Username" required autofocus>

    <label for="inputEmail" class="sr-only">Passwort</label>
    <input bind:value={email} type="email" id="inputEmail" name="email" class="form-control"
           placeholder="Email Adresse" required autofocus>

    <label for="inputPassword" class="sr-only">Passwort</label>
    <input bind:value={password} type="password" id="inputPassword" name="password" class="form-control"
           placeholder="Passwort" required>

    <label for="inputPasswordRepeat" class="sr-only">Passwort wiederholen</label>
    <input bind:value={passwordRepeat} type="password" id="inputPasswordRepeat" class="form-control"
           placeholder="Passwort wiederholen" required>
    <div class="checkbox mb-3">
        <label>
            <input bind:value={rememberMe} type="checkbox">
            Remember me
        </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Registrieren</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2022</p>
</form>

<style>
    form {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }

    form .checkbox {
        font-weight: 400;
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