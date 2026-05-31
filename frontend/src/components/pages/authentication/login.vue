<script setup>
    import LoginForm from '@/components/organisms/forms/authentication/LoginForm.vue';

    import { ref } from 'vue'
    import axios from '@/utils/axios.js'
    import { useAuthStore } from '@/stores/authStore.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router'

    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    
    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    const vModel = ref({})

    const errorAlertRef = ref(null)

    async function handleLogin(e) {
        try {
            e.preventDefault()

            const response = await axios.post('/users/login', vModel.value)

            authStore.setAuthToken(response.data.jwt)
            errorHandlingStore.successMessage = 'Successfully logged in.'
            router.push('/')
        }
        catch (ex){
            if (ex.response){
                errorAlertRef.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                errorAlertRef.value.displayErrorMessage(ex.message)
            }
        }
    }
</script>

<template>
    <section class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm authentication-box">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Login</h3>
                <ErrorAlert ref="errorAlertRef"/>
                <SuccessAlert />
                <LoginForm :vModel="vModel" @submit="handleLogin" />
                <RouterLink to="/auth/register">Register a new account</RouterLink>
            </div>
        </div>
    </section>
</template>
