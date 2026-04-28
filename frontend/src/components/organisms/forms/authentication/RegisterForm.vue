<script setup>
    import { ref } from 'vue'
    import axios from "@/utils/axios.js"
    import { useAuthStore } from "@/stores/authStore.js"
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'

    const authStore = useAuthStore();

    const username = ref('')
    const password = ref('')
    const repeatPassword = ref('')
    const currentSuccessAlert = ref(null)
    const currentErrorAlert = ref(null)

    async function handleRegister(e){
        try {
            e.preventDefault()

            if (repeatPassword.value !== password.value) {
                throw new Error("Repeated password is not equal to password");
            }

            const response = await axios.post('/auth/register', {
                username: username.value,
                password: password.value
            })

            authStore.setAuthToken(response.data.jwt)
            currentSuccessAlert.value.displaySuccessMessage("Successfully registered a new account.")
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
        <SuccessAlert ref="currentSuccessAlert" />
        <BaseFormField labelName="Username" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <BaseFormField labelName="Repeat password" type="password" id="repeat_password" placeholder="Repeat your password" v-model="repeatPassword"/>
        <AuthsubmitBtn buttonText="Register" />
        <router-link to="/auth/login">Login into an existing account</router-link>
    </form>
</template>