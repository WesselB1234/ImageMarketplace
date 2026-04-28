import axios from 'axios';
import { useAuthStore } from "@/stores/authStore.js"

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
        // if (error.response?.status === 401) {
        //     setAuthToken(null);
        //     window.location.href = "/login";
        // }
        return Promise.reject(error);
    }
);

export default apiClient;