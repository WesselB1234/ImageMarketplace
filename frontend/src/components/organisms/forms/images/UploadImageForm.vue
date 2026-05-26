<script setup>
    import { ref } from 'vue'
    import axios from '@/utils/axios.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router'

    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'
    import FileFormField from '@/components/molecules/forms/FileFormField.vue'
    import TextAreaField from '@/components/molecules/forms/TextAreaField.vue'

    const errorHandlingStore = useErrorHandlingStore()

    const name = ref('')
    const image = ref(null)
    const description = ref('')
    const altText = ref('')

    const errorAlertRef = ref(null)

    async function handleUpload(e) {
        try {
            e.preventDefault()

            const form = new FormData()
            form.append('name', name.value)
            form.append('image', image.value)
            form.append('description', description.value)
            form.append('altText', altText.value)

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
    <form @submit="handleUpload">
        <ErrorAlert ref="errorAlertRef" />
        <SuccessAlert />

        <BaseFormField labelName="Image name" id="name" placeholder="Enter image name" v-model="name"/>
        <FileFormField labelName="Image file (extension must be .png)" id="image" accept="image/*" v-model="image"/>
        <TextAreaField labelName="Description" id="description" placeholder="Enter description" v-model="description"/>
        <TextAreaField labelName="Alt text (shows if image is not able to load on the screen)" id="alt_text" placeholder="Enter alt text" v-model="altText"/>

        <SubmitBtn text="Upload" />
  </form>
</template>