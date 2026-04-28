<script setup>
    import { watch } from 'vue'
    import { useAuthStore } from './stores/authStore.js'
    import router from './router/index.js'

    const authStore = useAuthStore()

    watch(() => authStore.role, (newRole, oldRole) => {
        if (oldRole !== null){
            router.go(0)
        }
    })
</script>

<template>
    <router-view></router-view>
</template>
