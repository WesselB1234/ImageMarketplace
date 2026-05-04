import { defineStore } from 'pinia'
import { jwtDecode } from 'jwt-decode'
import { ref } from 'vue'
import axios from '@/utils/axios.js'

export const useAuthStore = defineStore('auth', () => {

    let authToken = ref(getAuthTokenFromLocalStorage())
    let decodedAuthToken = ref(getDecodedAuthToken())
    let loggedInUser = ref(null)

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

    async function getLoggedInUser() {
        try {
            if (loggedInUser.value === null){
                const response = await axios.get('/auth/get-logged-in-user')
                loggedInUser.value = response.data                
            }
            
            return loggedInUser.value;
        }
        catch (ex){
            if (ex.response){
                console.log('An error has occurred during fetching logged in user: ' + ex.response.data.message)
            }
        }
    }

    return {authToken, decodedAuthToken, setAuthToken, getLoggedInUser}
})