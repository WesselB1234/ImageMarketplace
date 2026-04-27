import { defineStore } from "pinia"
import { jwtDecode } from "jwt-decode"

export const useAuthStore = defineStore('auth', () => {

    let authToken = localStorage.getItem('auth_token') || null
    let decodedAuthToken = setDecodedAuthToken()
    
    function setAuthToken(token) {

        authToken = token;
        decodedAuthToken = setDecodedAuthToken()

        if (token) {
            localStorage.setItem('auth_token', token)
        } 
        else {
            localStorage.removeItem('auth_token')
        }
    }
    function setDecodedAuthToken() {

        if (authToken !== null) {
            decodedAuthToken = jwtDecode(authToken)
        }
        else{
            decodedAuthToken = null
        }
    }

    return {authToken, decodedAuthToken, setAuthToken}
})