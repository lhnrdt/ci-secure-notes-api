import authHeader from "./AuthHeader";
import {AuthService} from "./AuthService";
import {navigate} from "svelte-navigator";
import {toasts} from "svelte-toasts";

function createDataService() {
    async function getResource(url) {
        const response = await fetch(url, {headers: authHeader()});

        // access token is valid
        if (response.status === 200) {
            return await response.json();
        }

        // access token is expired
        if (response.status === 401) {
            return await refreshToken(() => getResource(url))
        }
    }

    async function postResource(url, formData) {
        const response = await fetch(url, {
            method: 'post',
            headers: authHeader(),
            body: formData
        })

        if (response.status === 401) {
            await refreshToken(() => postResource(url, formData));
        }
    }

    async function refreshToken(callback) {
        try {
            await AuthService.renewAccessToken();
            return await callback();
        } catch (e) {
            // refresh token is expired
            console.trace(e.message)
            toasts.error("Login expired, please login again.");
            navigate('/login');
        }
    }


    return {
        getResource: getResource,
        postResource: postResource
    }
}

export const DataService = createDataService();