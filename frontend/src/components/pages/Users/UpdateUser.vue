<script setup>
    import router from '@/router/index.js'
    import { onMounted, ref } from 'vue'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'

    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import ConfigureUserForm from '@/components/organisms/forms/users/ConfigureUserForm.vue'

    const errorHandlingStore = useErrorHandlingStore()

    const routeUserId = router.currentRoute.value.params.id
    const vModel = ref({
        'role': ''
    })
    const errorAlertRef = ref(null)

    function handleUpdateUser(e) {
        try {
            e.preventDefault()

            //const response = await axios.post('/auth/login', vModel.value)

            console.log(vModel.value)

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

    onMounted(() => {
        console.log('getUser')
    })
</script>

<template>

    <h1>Update User: #{{ routeUserId }}</h1>

    <ErrorAlert ref="errorAlertRef" />

    <ReturnBtn to="/users" text="Return back to users" />
    
    <ConfigureUserForm :vModel="vModel" @submit="handleUpdateUser" />
</template>
