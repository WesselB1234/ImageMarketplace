<script setup>
    import RegisterForm from '@/components/organisms/forms/authentication/RegisterForm.vue';
</script>

<template>
    <h3 class="card-title text-center mb-4">Register</h3>  
    <RegisterForm />
    <RouterLink to="/auth/login">Login into an existing account</RouterLink>
</template>
