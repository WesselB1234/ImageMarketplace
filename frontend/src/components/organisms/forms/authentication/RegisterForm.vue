<script setup>
    import { ref } from 'vue'
    import axios from "@/utils/axios.js"
    import { useAuthStore } from "@/stores/auth.js"
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import Alert from '@/components/atoms/errorHandling/Alert.vue'

    const username = ref('')
    const password = ref('')
    const successAlert = ref(null)
    const errorAlert = ref(null)
    const authStore = useAuthStore();

    async function handleRegister(e){
        try {
            e.preventDefault()

            const response = await axios.post('/auth/register', {
                username: username.value,
                password: password.value
            })

            authStore.setAuthToken(response.data.jwt)
            successAlert.value.displayAlertMessage("Successfully registered a new account.")
        }
        catch (ex){
            errorAlert.value.displayAlertMessage(ex.response.data.message)
        }
    }
</script>

<template>
    <form @submit="handleRegister">
        <Alert ref="errorAlert" classType="danger" />
        <Alert ref="successAlert" classType="success" />
        <BaseFormField labelName="Username" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <BaseFormField labelName="Repeat password" type="password" id="repeat_password" placeholder="Repeat your password"/>
        <AuthsubmitBtn buttonText="Register" />
        <router-link to="/auth/login">Login into an existing account</router-link>
    </form>
</template>