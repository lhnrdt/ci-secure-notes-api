import authHeader from "./AuthHeader";

function createDataService() {
    function getUserNotes() {
        return fetch('/api/notes', {headers: authHeader()})
            .then(res => res.json());
    }

    return {
        getUserNotes
    }
}

export const DataService = createDataService();