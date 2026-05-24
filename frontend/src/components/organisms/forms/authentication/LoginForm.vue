<script setup>
    import { ref } from 'vue'
    import axios from '@/utils/axios.js'
    import { useAuthStore } from '@/stores/authStore.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router'

    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    
    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    const username = ref('')
    const password = ref('')

    async function handleLogin(e) {
        try {
            e.preventDefault()

            const response = await axios.post('/users/login', {
                'username': username.value,
                'password': password.value
            })

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
    <form @submit="handleLogin">
        <ErrorAlert />
        <BaseFormField labelName="Username" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <AuthsubmitBtn text="Login" />
  </form>
</template>