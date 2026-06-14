<script setup>
    import { ref } from 'vue';

    import UsersTable from '@/components/organisms/tables/UsersTable.vue'
    import CreateBtn from '@/components/atoms/buttons/CreateBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue';
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue';

    const successAlertRef = ref(null)
    const errorAlertRef = ref(null)
</script>

<template>
    <h1>Users</h1>
    
    <SuccessAlert ref="successAlertRef" />
    <ErrorAlert ref="errorAlertRef" />

    <CreateBtn to="users/create" text="Create new user" />
    <UsersTable :successAlertRef="successAlertRef" :errorAlertRef="errorAlertRef" />
</template>
