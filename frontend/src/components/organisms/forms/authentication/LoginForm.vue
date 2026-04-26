<script setup>
    import { ref } from 'vue'
    import axios from 'axios'
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'

    const username = ref('')
    const password = ref('')
    const successMessage = ref('')
    const errorMessage = ref('')

    async function handleLoginClick(e) {
        try {
            e.preventDefault()
            console.log('Form data:', {
                username: username.value,
                password: password.value
            })

            const response = await axios.post("/login", null)
            successMessage.value = response.data.message
        }
        catch (ex){
            errorMessage.value = ex.response.data.message
        }
    }
</script>

<template>
    <form action="/auth/login" method="POST">
        <ErrorAlert :message="errorMessage" />
        <SuccessAlert :message="successMessage" />
        <BaseFormField labelName="Username" type="text" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <AuthsubmitBtn @click="handleLoginClick" buttonText="Login" />
        <router-link to="/auth/register">Register a new account</router-link>
  </form>
</template>