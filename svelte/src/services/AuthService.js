import {toasts} from "svelte-toasts";
import {navigate} from "svelte-navigator";

function createAuthService() {
    function login(formData) {
        return fetch('/auth/login', {
            method: 'POST',
            body: formData
        }).then(async res => {
            if (res.status === 200) {
                toasts.success("Login successful.");
                let resJSON = await res.json();
                localStorage.setItem('access_token', resJSON["access_token"]);
                navigate('/');
            }
            if (res.status === 400) {
                toasts.error("Invalid Login credentials");
            }
        })
    }

    function logout() {
        localStorage.removeItem('access_token');
    }

    async function register(formData) {

        return fetch('/auth/register', {
            method: 'POST',
            body: formData
        }).then(res => {
            if (res.status === 201) {
                toasts.success("Registered successfully.");
                let resJSON = res.json();
                localStorage.setItem('access_token', resJSON["access_token"]);
                navigate('/');
            }
            if (res.status === 400) {
                toasts.error("Something went wrong");
            }
            return res
        })
            .catch((error) => {
                console.error(error);
            });
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