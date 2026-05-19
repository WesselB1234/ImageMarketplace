<script setup>
    import { ref } from 'vue'
    import axios from "@/utils/axios.js"
    import { useAuthStore } from "@/stores/authStore.js"
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import router from '@/router'

    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    const username = ref('')
    const password = ref('')
    const repeatPassword = ref('')
    const currentErrorAlert = ref(null)

    async function handleRegister(e){
        try {
            e.preventDefault()

            if (repeatPassword.value !== password.value) {
                throw new Error('Repeated password is not equal to password');
            }

            const form = new FormData()
            form.append('username', username.value)
            form.append('password', password.value)

            const response = await axios.post('/users/register', form)

            authStore.setAuthToken(response.data.jwt)
            errorHandlingStore.setSuccessMessage('Successfully created a new account.')
            router.push('/')
        }
        catch (ex){
            if (ex.response){
                currentErrorAlert.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                currentErrorAlert.value.displayErrorMessage(ex.message)
            }
        }
    }
</script>

<template>
    <form @submit="handleRegister">
        <ErrorAlert ref="currentErrorAlert" />
        <BaseFormField labelName="Username" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <BaseFormField labelName="Repeat password" type="password" id="repeat_password" placeholder="Repeat your password" v-model="repeatPassword"/>
        <AuthsubmitBtn text="Register" />
    </form>
</template>