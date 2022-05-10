import authHeader from "./AuthHeader";
import {AuthService} from "./AuthService";
import {navigate} from "svelte-navigator";
import {toasts} from "svelte-toasts";

function createDataService() {
    function getUserNotes() {
        return fetch('/api/notes', {headers: authHeader()})
            .then(async res => {

                // if access token is valid
                if (res.status === 200) {
                    return await res.json();
                }

                // if access token is expired
                if (res.status === 401) {
                    try {
                        await AuthService.renewAccessToken();
                        return await getUserNotes();
                    } catch (e) {
                        // if refresh token is expired
                        toasts.error("Login expired, please login again.");
                        navigate('/login');
                    }
                }

            });
    }


    return {
        getUserNotes
    }
}

export const DataService = createDataService();