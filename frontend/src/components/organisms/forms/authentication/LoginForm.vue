<script setup>
    import { ref } from 'vue'
    import axios from 'axios';
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'

    const username = ref('')
    const password = ref('')

    async function handleLoginClick(e) {
        e.preventDefault()
        console.log('Form data:', {
            username: username.value,
            password: password.value
        })

        const response = await axios.post("http://localhost:80/login", null)
        console.log(response.data)
    }
</script>

<template>
    <form action="/auth/login" method="POST">
        <BaseFormField labelName="Username" type="text" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <AuthsubmitBtn @click="handleLoginClick" buttonText="Login" />
        <router-link to="/auth/register">Register a new account</router-link>
  </form>
</template>