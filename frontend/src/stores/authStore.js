import { defineStore } from 'pinia'
import { jwtDecode } from 'jwt-decode'
import { ref } from 'vue'

const authTokenLocalStorageKey = 'image_marketplace_auth_token'

export const useAuthStore = defineStore('auth', () => {

    let authToken = ref(getAuthTokenFromLocalStorage())
    let decodedAuthToken = ref(getDecodedAuthToken())

    function getAuthTokenFromLocalStorage(){

        const localStorageAuthToken = localStorage.getItem(authTokenLocalStorageKey)

        if (localStorageAuthToken){
            return localStorageAuthToken
        }

        return null
    }
    
    function setAuthToken(token) {

        authToken.value = token;
        decodedAuthToken.value = getDecodedAuthToken()

        if (token) {
            localStorage.setItem(authTokenLocalStorageKey, token)
        } 
        else {
            localStorage.removeItem(authTokenLocalStorageKey)
        }
    }

    function getDecodedAuthToken() {

        if (authToken.value !== null) {
            return jwtDecode(authToken.value)
        }
        
        return null
    }

    return {authToken, decodedAuthToken, setAuthToken}
})