import {readable, writable} from "svelte/store";

export const noteStore = writable([]);
export const categoryStore = writable([]);
export const selectedCategory = writable(null);