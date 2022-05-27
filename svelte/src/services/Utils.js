function createUtils() {
    function getUser() {
        return JSON.parse(localStorage.getItem('user'));
    }

    return {
        getUser
    }
}

export const Utils = createUtils();
