<script setup>
    import UserRow from '@/components/molecules/tables/UserRow.vue'

    const props = defineProps({
        errorAlertRef: {
            type: Object,
            required: false
        },
        successAlertRef: {
            type: Object,
            required: false
        },
        users: {
            type: Array,
            required: true
        }
    });
</script>

<template>
    <table class="table mt-4">
        <thead class="table-dark">
            <tr>
                <th scope="col">User ID</th>
                <th scope="col">Username</th>
                <th scope="col">Image tokens</th>
                <th scope="col">Role</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <UserRow v-for="user in users" :user="user" :successAlertRef="successAlertRef" :errorAlertRef="errorAlertRef" />
        </tbody>
    </table>
</template>
