import {toasts} from "svelte-toasts";
import {navigate} from "svelte-navigator";

const createAuthService = () => {

    // converts the response to json and evaluates response code
    const processAuthResponse = async response => {
        if (response.status === 201 || response.status === 200) {
            let resJSON = await response.json();
            // save received access_token to local storage;
            localStorage.setItem('access_token', resJSON["access_token"]);
            localStorage.setItem('user', JSON.stringify(resJSON["user"]));
            return resJSON['user'];
        }
        if (response.status === 400) {
            throw new Error('Invalid Login Credentials');
        }
        if (!response.ok) {
            throw new Error('Server Error');
        }
    };

    // sends login request
    const login = async formData => {
        const res = await fetch('/auth/login', {
            method: 'POST',
            body: formData
        });

        const user = await processAuthResponse(res);
        if (user) toasts.success("Login successful.");
        return user;
    };

    // sends register request
    const register = async formData => {
        const res = await fetch('/auth/register', {
            method: 'POST',
            body: formData
        });

        const user = await processAuthResponse(res);
        toasts.success("Registration successful.");
        return user;
    };


    // clears saved userdata and signals server to delete refresh_token cookie
    const logout = async () => {
        await fetch('auth/logout');
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
        toasts.success('Ausgeloggt.')
        navigate('/login');
    };

    // renews access token if expired with refresh token
    const renewAccessToken = async () => {
        return fetch('auth/refreshtoken')
            .then(async res => {
                if (res.status === 200) {
                    const json = await res.json();
                    localStorage.setItem('access_token', json['access_token'])
                } else {
                    throw new Error("Refresh token expired.")
                }
            });
    };

    return {
        login,
        logout,
        register,
        renewAccessToken
    }
};

export const AuthService = createAuthService();