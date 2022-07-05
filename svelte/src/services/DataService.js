import authHeader from "./AuthHeader";
import {AuthService} from "./AuthService";
import {navigate} from "svelte-navigator";
import {toasts} from "svelte-toasts";

const createDataService = () => {

    // Tries to request new access token before executing callback function passed as argument
    const refreshToken = async callback => {
        try {
            await AuthService.renewAccessToken();
            return await callback();
        } catch (e) {
            // refresh token is expired
            toasts.error("Login expired, please login again.");
            navigate('/login');
        }
    };

    // gets a specific resource from the api, refreshes token if necessary
    const getResource = async url => {
        const response = await fetch(url, {headers: authHeader()});

        // access token is valid
        if (response.status === 200 || response.status === 404) {
            return await response.json();
        }

        // access token is expired
        if (response.status === 401) {
            return await refreshToken(() => getResource(url))
        }
    };

    // save a specific resource to the api, refreshes token if necessary
    const postResource = async (url, formData) => {
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
    };

    // delete a specific resource to the api, refreshes token if necessary
    const deleteResource = async url => {
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
    };


    // wrapper functions for specific resources
    const createNote = async formData => await postResource(`/api/notes/`, formData);
    const updateNote = async (id, formData) => await postResource(`/api/notes/${id}`, formData);
    const deleteNote = async id => await deleteResource(`/api/notes/${id}`);

    return {
        getResource,
        postResource,
        deleteResource,
        createNote,
        deleteNote,
        updateNote,
    }
};

export const DataService = createDataService();