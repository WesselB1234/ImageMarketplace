<script setup>
    import axios from '@/utils/axios.js'
    import { onMounted, ref } from 'vue'
    import router from '@/router/index.js'

    import UsersTable from '@/components/organisms/tables/UsersTable.vue'
    import CreateBtn from '@/components/atoms/buttons/CreateBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue';
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue';

    const successAlertRef = ref(null)
    const errorAlertRef = ref(null)
    const users = ref([])

    onMounted(async () => {
        try {
            const response = await axios.get('/users', {
                params: router.currentRoute.value.query
            })

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
    <h1>Users</h1>
    
    <SuccessAlert ref="successAlertRef" />
    <ErrorAlert ref="errorAlertRef" />

    <CreateBtn to="users/create" text="Create new user" />
    <UsersTable :successAlertRef="successAlertRef" :errorAlertRef="errorAlertRef" :users="users" />
</template>
