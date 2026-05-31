<script setup>
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue';
    import SuccessAlert from '@/components/atoms/errorHandling/ErrorAlert.vue';
</script>

<template>
    <h1 class="mb-4">Settings</h1>

    <ErrorAlert />
    <SuccessAlert />

    <h4>Account actions</h4>
    <ul class="list-unstyled">
        <li>
            <a class="btn btn-danger" href="/settings/delete-account">Delete account</a>
        </li>
    </ul>
</template>