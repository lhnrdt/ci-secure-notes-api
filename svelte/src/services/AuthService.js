import {toasts} from "svelte-toasts";
import {navigate} from "svelte-navigator";

function createAuthService() {
    async function login(formData) {
        const res = await fetch('/auth/login', {
            method: 'POST',
            body: formData
        });

        try {
            const user = await processAuthResponse(res);
            toasts.success("Login successful.");
            return user;
        } catch (e) {
            toasts.error(e.message);
        }
    }

    async function register(formData) {
        const res = await fetch('/auth/register', {
            method: 'POST',
            body: formData
        });

        try {
            const user = await processAuthResponse(res);
            toasts.success("Registration successful.");
            return user;
        } catch (e) {
            toasts.error(e.message);
        }
    }

    async function processAuthResponse(response) {
        if (response.status === 201 || response.status === 200) {
            let resJSON = await response.json();
            localStorage.setItem('access_token', resJSON["access_token"]);
            localStorage.setItem('user', JSON.stringify(resJSON["user"]));
            return resJSON['user'];
        }
        if (response.status === 400) {
            throw new Error('Invalid Login Credentials');
        }
    }

    async function logout() {
        await fetch('auth/logout');
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
        toasts.success('Ausgeloggt.')
        navigate('/login');
    }

    async function renewAccessToken() {
        return fetch('auth/refreshtoken')
            .then(async res => {
                if (res.status === 200) {
                    const json = await res.json();
                    localStorage.setItem('access_token', json['access_token'])
                } else {
                    throw new Error("Refresh token expired.")
                }
            })
    }

    return {
        login,
        logout,
        register,
        renewAccessToken
    }
}

export const AuthService = createAuthService();