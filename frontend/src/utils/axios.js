import axios from 'axios';
import { useAuthStore } from '@/stores/authStore.js'
import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
import router from '@/router'

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:80',
    headers: {
        'Content-Type': 'application/json',
    },
});

apiClient.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore()
        let authToken = authStore.authToken

        if (authToken) {
            config.headers.Authorization = `Bearer ${authToken}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

apiClient.interceptors.response.use(
    response => response,
    error => {
        const authStore = useAuthStore()
        const errorHandlingStore = useErrorHandlingStore()

        if (error.response) {
            if (error.response.status === 401 && error.response.headers['x-auth-error']) {
                authStore.setAuthToken(null)
                errorHandlingStore.setErrorMessage("Your token has been deleted due it being invalid. Please log in again.")
                router.push('/auth/login')
            }
        }

        return Promise.reject(error);
    }
);

export default apiClient;