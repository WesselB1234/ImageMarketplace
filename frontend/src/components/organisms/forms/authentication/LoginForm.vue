<script setup lang="ts">
import { ref } from 'vue';
import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue';
import BaseFormField from '@/components/molecules/forms/BaseFormField.vue';

const username = ref('');
const password = ref('');

function handleLoginClick(event: Event) {
    event.preventDefault();
    console.log('Form data:', {
        username: username.value,
        password: password.value
    });
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
