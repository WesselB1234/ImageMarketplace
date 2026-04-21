<script setup lang="ts">
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue';
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue';
</script>

<template>
    <form action="/auth/register" method="POST" id="registerForm">
        <BaseFormField />
        <BaseFormField />
        <BaseFormField />
        <AuthsubmitBtn buttonText="Register" />
        <router-link to="/auth/login">Login into an existing account</router-link>
    </form>
</template>