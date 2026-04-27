import { defineStore } from "pinia"
import { jwtDecode } from "jwt-decode"

export const useAuthStore = defineStore('auth', () => {

    let authToken = localStorage.getItem('auth_token') || null
    let decodedAuthToken = getDecodedAuthToken()
    
    function setAuthToken(token) {

        authToken = token;
        decodedAuthToken = getDecodedAuthToken()

        if (token) {
            localStorage.setItem('auth_token', token)
        } 
        else {
            localStorage.removeItem('auth_token')
        }
    }
    function getDecodedAuthToken() {

        if (authToken !== null) {
            return jwtDecode(authToken)
        }
        
        return null
    }

    return {authToken, decodedAuthToken, setAuthToken}
})