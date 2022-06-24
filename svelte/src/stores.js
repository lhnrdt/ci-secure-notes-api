import {writable} from "svelte/store";
import {DataService} from "./services/DataService";

export const searchQuery = writable("");
export const categoryStore = writable([]);
export const selectedCategory = writable(null);
export const NOTES_PER_REQUEST = 20;
export const timeout = writable(null);

export const createNoteStore = () => {
    const {subscribe, update, set} = writable([])
    const limit = NOTES_PER_REQUEST;
    let offset;

    const init = async (searchQuery = "", categoryID = null) => {
        offset = 0;
        set([]);
        const res = await getMore(searchQuery, categoryID)
        if (!!res.note.length) add(res.note);
        return res;
    }

    const getMore = async (searchQuery = "", categoryID = null) => {

        let url = `/api/notes?offset=${offset}&limit=${limit}`;
        if (searchQuery.length) url += `&q=${searchQuery}`;
        if (categoryID) url += `&category=${categoryID}`;
        const responseJSON = await DataService.getResource(url);
        offset += responseJSON.note.length;
        return responseJSON;
    }

    const add = (noteBatch) => {
        update(notes => [...notes, noteBatch]);
    }

    return {
        subscribe,
        init,
        getMore,
        add
    }
}

export const noteStore = createNoteStore()