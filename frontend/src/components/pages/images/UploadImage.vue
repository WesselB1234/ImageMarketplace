<script setup>
    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue';
    import UploadImageForm from '@/components/organisms/forms/images/UploadImageForm.vue'

    import { ref } from 'vue'
    import axios from '@/utils/axios.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router'

    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'

    const errorHandlingStore = useErrorHandlingStore()

    const vModel = ref({})

    const errorAlertRef = ref(null)

    async function handleUpload(e) {
        try {
            e.preventDefault()

            const form = new FormData()
            form.append('name', vModel.value.name)
            form.append('image', vModel.value.image)
            form.append('description', vModel.value.description)
            form.append('altText', vModel.value.altText)

            await axios.post('/images', form, {
                headers: {
                    'Content-Type': undefined
                }
            })
            
            errorHandlingStore.successMessage = 'Successfully uploaded image.'
            router.push('/')
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
    <h1>Upload image</h1>

    <ReturnBtn to="/portfolio" text="Return back to portfolio" />
    <ErrorAlert ref="errorAlertRef" />
    <SuccessAlert />
    <UploadImageForm @submit="handleUpload" :vModel="vModel" />
</template>
