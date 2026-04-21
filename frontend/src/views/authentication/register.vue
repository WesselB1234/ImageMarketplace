<script setup>
    import RegisterForm from '@/components/organisms/forms/authentication/RegisterForm.vue';
</script>

<template>
    <section class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm authentication-box">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Register</h3>  
                <RegisterForm />
            </div>
        </div>
    </section>
</template>
