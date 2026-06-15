<script setup>
    import router from '@/router'
    import { useAuthStore } from "@/stores/authStore.js"
    import { useErrorHandlingStore } from "@/stores/errorHandlingStore"

    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    function handleLogout() {
        authStore.setAuthToken(null)
        errorHandlingStore.successMessage = 'Successfully logged out of your account.'

        router.push('/auth/login')
    }
</script>

<template>
    <button @click="handleLogout" class="btn btn-danger text-light">Logout</button>
</template>