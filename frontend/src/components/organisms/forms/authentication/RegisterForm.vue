<script setup lang="ts">
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue';
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue';
</script>

<template>
    <form action="/auth/register" method="POST" id="registerForm">
        <BaseFormField labelName="Username" type="text" id="username" name="username" placeholder="Enter your username"/>
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password"/>
        <BaseFormField labelName="Repeat password" type="password" id="repeat_password" placeholder="Repeat your password"/>
        <AuthsubmitBtn buttonText="Register" />
        <router-link to="/auth/login">Login into an existing account</router-link>
    </form>
</template>