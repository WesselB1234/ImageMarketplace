<script setup>
    import UsersTable from '@/components/organisms/tables/UsersTable.vue'
    import CreateBtn from '@/components/atoms/buttons/CreateBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue';
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue';
</script>

<template>
    <h1>Users</h1>

    <ErrorAlert />
    <SuccessAlert />

    <CreateBtn to="users/create" text="Create new user" />
    <UsersTable />
</template>
