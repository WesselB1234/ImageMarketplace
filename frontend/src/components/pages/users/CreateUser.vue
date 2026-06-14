<script setup>
    import { ref } from 'vue'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router/index.js'
    import axios from '@/utils/axios.js'

    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import ConfigureUserForm from '@/components/organisms/forms/users/ConfigureUserForm.vue'

    const errorHandlingStore = useErrorHandlingStore()

    const vModel = ref({
        'role': ''
    })
    const errorAlertRef = ref(null)

    async function handleCreateUser(e) {
        try {
            e.preventDefault()

            await axios.post('/users', vModel.value)

            errorHandlingStore.successMessage = 'Successfully created a new user.'
            router.push('/users')
        }
        catch (ex){
            if (ex.response){
                errorAlertRef.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                errorAlertRef.value.displayErrorMessage(ex.message)
            }
        }
    }
</script>

<template>

    <h1>Create User</h1>

    <ErrorAlert ref="errorAlertRef" />
    <ReturnBtn to="/users" text="Return back to users" />
    <ConfigureUserForm :vModel="vModel" @submit="handleCreateUser" :isPasswordRequired="true" SubmitBtnText="Create" />
</template>
