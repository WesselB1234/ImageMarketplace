<script setup lang="ts">
    import SubmitBtn from '@/components/atoms/buttons/forms/submitBtn.vue';
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue';
</script>

<template>
    <form action="/auth/login" method="POST">
        <BaseFormField />
        <BaseFormField />
        <SubmitBtn />
        <router-link to="/auth/register">Register a new account</router-link>
    </form>
</template>