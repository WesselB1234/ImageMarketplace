<script setup>
    import { ref } from 'vue'
    import axios from "@/utils/axios.js"
    import { useAuthStore } from "@/stores/authStore.js"
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router'

    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'

    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    const username = ref('')
    const password = ref('')
    const repeatPassword = ref('')

    const errorAlertRef = ref(null)

    async function handleRegister(e){
        try {
            e.preventDefault()

            if (repeatPassword.value !== password.value) {
                throw new Error('Repeated password is not equal to password');
            }

            const response = await axios.post('/users/register', {
                'username': username.value,
                'password': password.value
            })

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
    <form @submit="handleRegister">
        <ErrorAlert ref="errorAlertRef" />
        <BaseFormField labelName="Username" id="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" placeholder="Enter your password" v-model="password"/>
        <BaseFormField labelName="Repeat password" type="password" id="repeat_password" placeholder="Repeat your password" v-model="repeatPassword"/>
        <AuthsubmitBtn text="Register" />
    </form>
</template>