<script setup>
    import axios from '@/utils/axios.js'
    import { onMounted, ref } from 'vue'

    import UserRow from '@/components/molecules/tables/UserRow.vue'

    const props = defineProps({
        errorAlertRef: {
            type: Object,
            required: true
        },
        successAlertRef: {
            type: Object,
            required: true
        },
    });

    const users = ref(null)

    onMounted(async () => {
        try {
            const response = await axios.get('/users')
            users.value = response.data
        }
        catch (ex){
            if (ex.response){
                props.errorAlertRef.displayErrorMessage(ex.response.data.message)
            }
            else {
                props.errorAlertRef.displayErrorMessage(ex.message)
            }
        }
    })
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
