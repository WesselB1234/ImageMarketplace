import { defineStore } from 'pinia'
import { jwtDecode } from 'jwt-decode'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {

    let authToken = ref(localStorage.getItem('auth_token') || null)
    let decodedAuthToken = ref(getDecodedAuthToken())
    
    function setAuthToken(token) {

        authToken.value = token;
        decodedAuthToken.value = getDecodedAuthToken()

        if (token) {
            localStorage.setItem('auth_token', token)
        } 
        else {
            localStorage.removeItem('auth_token')
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