<script setup>
    import { ref } from 'vue'
    import axios from '@/utils/axios.js'
    import { useAuthStore } from '@/stores/authStore.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import router from '@/router'
    
    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    const username = ref('')
    const password = ref('')
    const currentErrorAlert = ref(null)

    async function handleLogin(e) {
        try {
            e.preventDefault()

            const form = new FormData()
            form.append('username', username.value)
            form.append('password', password.value)

            const response = await axios.post('/users/login', form)

            authStore.setAuthToken(response.data.jwt)
            errorHandlingStore.setSuccessMessage('Successfully logged in.')
            router.push('/')
        }
        catch (ex){
            if (ex.response){
                currentErrorAlert.value.displayErrorMessage(ex.response.data.message)
            }
        }
    }
</script>

<template>
    <form @submit="handleLogin">
        <ErrorAlert ref="currentErrorAlert" />
        <SuccessAlert />
        <BaseFormField labelName="Username" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <AuthsubmitBtn text="Login" />
  </form>
</template>