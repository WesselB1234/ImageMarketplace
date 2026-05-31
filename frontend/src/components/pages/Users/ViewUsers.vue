<script setup>
    import UsersTable from '@/components/organisms/tables/UsersTable.vue'
    import CreateBtn from '@/components/atoms/buttons/CreateBtn.vue'
</script>

<template>
    <h1>Users</h1>

    <CreateBtn to="users/create" text="Create new user" />
    <UsersTable />
</template>
