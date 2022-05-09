import {writable, derived} from "svelte/store";

function createNotificationStore() {
    const toasts = writable([]);

    function send(header, message, timeout) {
        toasts.update(state => {
            return [...state, {id: randomID(), header, message, timeout}];
        })
    }

    const {subscribe} = derived(toasts, ($_toasts, set) => {
        set($_toasts)
        if ($_toasts.length > 0) {
            const timer = setTimeout(() => {
                toasts.update(state => {
                    state.shift();
                    return state;
                })
            }, $_toasts[0].timeout);
            return () => {
                clearTimeout(timer);
            }
        }
    });

    return {
        subscribe,
        send,
    }
}

function randomID() {
    return '_' + Math.random().toString(36).substring(2, 9);
}

export const toasts = createNotificationStore();