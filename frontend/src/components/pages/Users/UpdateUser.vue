<script setup>
    import router from '@/router/index.js'
    import { onMounted, ref } from 'vue'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import axios from '@/utils/axios.js'

    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import ConfigureUserForm from '@/components/organisms/forms/users/ConfigureUserForm.vue'

    const errorHandlingStore = useErrorHandlingStore()

    const routeUserId = router.currentRoute.value.params.id
    const vModel = ref({
        'role': ''
    })
    const errorAlertRef = ref(null)

    async function handleUpdateUser(e) {
        try {
            e.preventDefault()

            await axios.put('/users/' + routeUserId, vModel.value)

            errorHandlingStore.successMessage = 'Successfully updated user with id: #' + routeUserId
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

    onMounted(async () => {
        try {
            const response = await axios.get('/users/get-by-id/' + routeUserId)
            vModel.value.username = response.data.username
            vModel.value.imageTokens = response.data.imageTokens
            vModel.value.role = response.data.role
        }
        catch (ex){
            if (ex.response){
                errorHandlingStore.errorMessage = ex.response.data.message
            }
            else {
                errorHandlingStore.errorMessage = ex.message
            }

            router.push('/users')
        }
    })
</script>

<template>

    <h1>Update User: #{{ routeUserId }}</h1>

    <ErrorAlert ref="errorAlertRef" />
    <ReturnBtn to="/users" text="Return back to users" />
    <ConfigureUserForm :vModel="vModel" @submit="handleUpdateUser" />
</template>
