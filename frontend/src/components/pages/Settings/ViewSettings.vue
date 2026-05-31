<script setup>
    import router from '@/router/index.js'

    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import DangerBtn from '@/components/atoms/buttons/DangerBtn.vue'

    function handleDeleteAccount() {
        console.log('delete')
    }
</script>

<template>
    <h1 class="mb-4">Settings</h1>

    <ErrorAlert />
    <SuccessAlert />

    <h4>Account actions</h4>
    <ul class="list-unstyled">
        <li>
            <DangerBtn @click ="handleDeleteAccount" text="Delete account" />
        </li>
    </ul>
</template>