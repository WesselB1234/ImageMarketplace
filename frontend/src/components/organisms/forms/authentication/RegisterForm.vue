<script setup lang="ts">
    import SubmitBtn from '@/components/atoms/buttons/forms/submitBtn.vue';
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue';
</script>

<template>
    <form action="/auth/register" method="POST" id="registerForm">
        <BaseFormField />
        <BaseFormField />
        <BaseFormField />
        <SubmitBtn />
        <router-link to="/auth/login">Login into an existing account</router-link>
    </form>
</template>