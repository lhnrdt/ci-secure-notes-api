/**
 * Creates authorization header with access_token from localStorage
 *
 * @returns {{Authorization: string}|{}}
 */
export default () => {
    const accessToken = localStorage.getItem('access_token');
    if (accessToken) {
        return {Authorization: `Bearer ${accessToken}`};
    } else {
        return {};
    }
}