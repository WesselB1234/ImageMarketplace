<script setup>
    import { ref } from 'vue'
    import axios from 'axios'
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import Alert from '@/components/atoms/errorHandling/Alert.vue'
    
    const username = ref('')
    const password = ref('')
    const successAlert = ref(null)
    const errorAlert = ref(null)

    async function handleLoginClick(e) {
        try {
            e.preventDefault()

            const response = await axios.post('/login', {
                username: username.value,
                password: password.value
            })

            successAlert.value.displayAlertMessage(response.data.message)
        }
        catch (ex){
            errorAlert.value.displayAlertMessage(ex.response.data.message)
        }
    }
</script>

<template>
    <form>
        <Alert ref="errorAlert" classType="danger" />
        <Alert ref="successAlert" classType="success" />
        <BaseFormField labelName="Username" type="text" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" v-model="password"/>
        <AuthsubmitBtn @click="handleLoginClick" buttonText="Login" />
        <router-link to="/auth/register">Register a new account</router-link>
  </form>
</template>