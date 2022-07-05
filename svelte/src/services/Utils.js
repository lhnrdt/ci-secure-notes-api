/**
 * Utils functions
 * @returns {{getUser: (function(): any)}}
 */

const createUtils = () => {
    const getUser = () => JSON.parse(localStorage.getItem('user'));

    return {
        getUser
    }
};

export const Utils = createUtils();
