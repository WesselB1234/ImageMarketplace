<script setup>
    import router from '@/router'
    import { useAuthStore } from "@/stores/auth.js"
    import { useErrorHandlingStore } from "@/stores/errorHandlingStore"

    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    authStore.setAuthToken(null)
    errorHandlingStore.setSuccessMessage("Successfully logged out of your account.")

    router.push('/auth/login')
</script>

