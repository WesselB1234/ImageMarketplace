<script setup>
    import { ref } from 'vue'
    import axios from "@/utils/axios.js"
    import { useAuthStore } from "@/stores/authStore.js"
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router'

    import RegisterForm from '@/components/organisms/forms/authentication/RegisterForm.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'

    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    const vModel = ref({})
    const errorAlertRef = ref(null)

    async function handleRegister(e){
        try {
            e.preventDefault()

            if (vModel.value.repeatPassword !== vModel.value.password) {
                throw new Error('Repeated password is not equal to password');
            }

            const response = await axios.post('/auth/register', vModel.value)

            authStore.setAuthToken(response.data.jwt)
            errorHandlingStore.successMessage = 'Successfully created a new account.'
            router.push('/')
        }
        catch (ex){
            if (ex.response) {
                errorAlertRef.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                errorAlertRef.value.displayErrorMessage(ex.message)
            }
        }
    }
</script>

<template>
    <h3 class="card-title text-center mb-4">Register</h3> 
    <ErrorAlert ref="errorAlertRef"/>
    <SuccessAlert />
    <RegisterForm @submit="handleRegister" :vModel="vModel" />
    <RouterLink to="/auth/login">Login into an existing account</RouterLink>
</template>
