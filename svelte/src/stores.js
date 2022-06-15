import {writable} from "svelte/store";

export const noteStore = writable([]);
export const filteredNoteStore = writable([]);
export const categoryStore = writable([]);
export const selectedCategory = writable(null);
