import { defineStore } from 'pinia'
import { jwtDecode } from 'jwt-decode'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {

    let authToken = ref(getAuthTokenFromLocalStorage())
    let decodedAuthToken = ref(getDecodedAuthToken())
    let role = ref(getCurrentRole())

    function getAuthTokenFromLocalStorage(){

        const localStorageAuthToken = localStorage.getItem('auth_token')

        if (localStorageAuthToken){
            return localStorageAuthToken
        }

        return null
    }
    
    function setAuthToken(token) {

        authToken.value = token;
        decodedAuthToken.value = getDecodedAuthToken()
        role.value = getCurrentRole()

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

    function getCurrentRole() {

        if (decodedAuthToken.value !== null){
            return decodedAuthToken.value.data.role
        }

        return null
    }

    return {authToken, decodedAuthToken, role, setAuthToken}
})