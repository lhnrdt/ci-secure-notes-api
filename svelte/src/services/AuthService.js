import {toasts} from "../stores/notficationStores";
import {navigate} from "svelte-navigator";

function createAuthService() {
    function login(formData) {
        return fetch('/auth/login', {
            method: 'POST',
            body: formData
        })
            .then(async res => {
                if (res.status === 200) {
                    toasts.send("Success", "Login successfully.", 3000);
                    let resJSON = await res.json();
                    delete resJSON.message;

                    localStorage.setItem('user', JSON.stringify(resJSON));
                    navigate('/')
                }
                if (res.status === 400) {
                    toasts.send("Error", "Invalid Login credentials", 3000);
                }
            })
    }

    function logout() {
        localStorage.removeItem('user');
    }

    async function register(formData) {

        return fetch('/auth/register', {
            method: 'POST',
            body: formData
        })
            .then(res => {
                if (res.status === 201) {
                    toasts.send("Success", "Registered successfully.", 3000);
                    let resJSON = res.json();
                    // delete resJSON.message;
                    localStorage.setItem('user', JSON.stringify(resJSON));
                    navigate('/')
                }
                if (res.status === 400) {
                    toasts.send("Error", "Something went wrong", 3000);
                }
                return res
            })
            .catch((error) => {
                console.error(error);
            });
    }

    function getUser() {
        return JSON.parse(localStorage.getItem('user'));
        ;
    }

    return {
        login,
        logout,
        register,
        getUser
    }
}

export const AuthService = createAuthService();