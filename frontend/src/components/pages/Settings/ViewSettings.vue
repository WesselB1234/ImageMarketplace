<script setup>
    import router from '@/router/index.js'
    import axios from '@/utils/axios.js'
    import { ref } from 'vue'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'

    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import DangerBtn from '@/components/atoms/buttons/DangerBtn.vue'

    const errorHandlingStore = useErrorHandlingStore()

    const errorAlertRef = ref(null)

    async function handleDeleteAccount() {
        try {
            await axios.delete('/users/settings')

            errorHandlingStore.successMessage = 'Successfully deleted your account'
            router.push('/auth/login')
        }
        catch (ex){
            if (ex.response){
                errorAlertRef.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                errorAlertRef.value.displayErrorMessage(ex.message)
            }
        }
    }
</script>

<template>
    <h1 class="mb-4">Settings</h1>

    <ErrorAlert ref="errorAlertRef" />

    <h4>Account actions</h4>
    <ul class="list-unstyled">
        <li>
            <DangerBtn @click ="handleDeleteAccount" text="Delete account" />
        </li>
    </ul>
</template>