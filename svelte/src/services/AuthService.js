import {toasts} from "svelte-toasts";
import {navigate} from "svelte-navigator";

function createAuthService() {
    async function login(formData) {
        const res = await fetch('/auth/login', {
            method: 'POST',
            body: formData
        });

        try {
            await processAuthResponse(res);
            toasts.success("Login successful.");
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
            await processAuthResponse(res);
            toasts.success("Login successful.");
        } catch (e) {
            toasts.error(e.message);
        }
    }

    async function processAuthResponse(response) {
        if (response.status === 201 || response.status === 200) {
            let resJSON = await response.json();
            localStorage.setItem('access_token', resJSON["access_token"]);
            localStorage.setItem('user', JSON.stringify(resJSON["user"]));
            navigate('/');
        }
        if (response.status === 400) {
            throw new Error('Invalid Login Credentials');
        }
    }

    function logout() {
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
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