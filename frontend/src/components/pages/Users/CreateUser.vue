<script setup>
    import { ref } from 'vue'

    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue';
    import ConfigureUserForm from '@/components/organisms/forms/users/ConfigureUserForm.vue'

    const vModel = ref({})
</script>

<template>

    <h1>Create User</h1>

    <ErrorAlert />

    <ReturnBtn to="/users" text="Return back to users" />
    
    <ConfigureUserForm :vModel="vModel" />
</template>
