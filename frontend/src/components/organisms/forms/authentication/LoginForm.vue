<script setup lang="ts">
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue';
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue';
</script>

<template>
    <form action="/auth/login" method="POST">
        <BaseFormField labelName="Username" type="text" id="username" name="username" placeholder="Enter your username" />
        <BaseFormField labelName="Password" type="password" id="password" name="password" placeholder="Enter your password" />
        <AuthsubmitBtn buttonText="Login" />
        <router-link to="/auth/register">Register a new account</router-link>
    </form>
</template>