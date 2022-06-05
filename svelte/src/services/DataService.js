import authHeader from "./AuthHeader";
import {AuthService} from "./AuthService";
import {navigate} from "svelte-navigator";
import {toasts} from "svelte-toasts";

function createDataService() {
    async function getResource(url) {
        const response = await fetch(url, {headers: authHeader()});

        // access token is valid
        if (response.status === 200 || response.status === 404) {
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

        if (response.status === 400) {
            let responseJSON = await response.json();
            toasts.error(responseJSON.message);
        }

        if (response.status === 401) {
            await refreshToken(() => postResource(url, formData));
        }

        if (response.status === 200) {
            let responseJSON = await response.json();
            toasts.success(responseJSON.message);
            return responseJSON;
        }
    }

    async function deleteResource(url) {
        const response = await fetch(url, {
            method: 'delete',
            headers: authHeader()
        })

        if (response.status === 400) {
            await refreshToken(() => postResource(url));
        }

        if (response.status === 200) {
            let responseJSON = await response.json();
            toasts.success(responseJSON.message);
        }
    }

    async function refreshToken(callback) {
        try {
            await AuthService.renewAccessToken();
            return await callback();
        } catch (e) {
            // refresh token is expired
            toasts.error("Login expired, please login again.");
            navigate('/login');
        }
    }

    async function createNote(formData) {
        return await postResource(`/api/notes/`, formData);
    }

    async function updateNote(id, formData) {
        return await postResource(`/api/notes/${id}`, formData)
    }

    async function deleteNote(id) {
        await deleteResource(`/api/notes/${id}`)
    }

    return {
        getResource,
        postResource,
        deleteResource,
        createNote,
        deleteNote,
        updateNote
    }
}

export const DataService = createDataService();