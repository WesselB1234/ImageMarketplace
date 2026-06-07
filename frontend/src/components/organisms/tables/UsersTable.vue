<script setup>
    import axios from '@/utils/axios.js'
    import { onMounted, ref } from 'vue'

    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import UserRow from '@/components/molecules/tables/UserRow.vue'

    const users = ref(null)

    const errorAlertRef = ref(null)

    onMounted(async () => {
        try {
            const response = await axios.get('/users')
            users.value = response.data
        }
        catch (ex){
            if (ex.response){
                errorAlertRef.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                errorAlertRef.value.displayErrorMessage(ex.message)
            }
        }
    })
</script>

<template>
    <SuccessAlert /> 
    <ErrorAlert ref="errorAlertRef" />

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
            <UserRow v-for="user in users" :user="user" />
        </tbody>
    </table>
</template>
