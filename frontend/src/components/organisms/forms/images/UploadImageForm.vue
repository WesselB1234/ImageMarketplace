<script setup>
    import { ref } from 'vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'
    import FileFormField from '@/components/molecules/forms/FileFormField.vue'
    import TextAreaField from '@/components/molecules/forms/TextAreaField.vue'
    import axios from '@/utils/axios.js'

    const name = ref('')
    const image = ref(null)
    const description = ref('')
    const altText = ref('')
    const currentErrorAlert = ref(null)
    const currentSuccessAlert = ref(null)

    async function handleUpload(e) {
        try {
            e.preventDefault()

            const form = new FormData()
            form.append('name', name.value)
            form.append('image', image.value)
            form.append('description', description.value)
            form.append('altText', altText.value)

            const response = await axios.post('/images/upload', form, {
                headers: {
                    'Content-Type': undefined
                }
            })

            console.log(response)

            currentSuccessAlert.value.displaySuccessMessage('Successfully uploaded image.')
        }
        catch (ex){
            if (ex.response){
                currentErrorAlert.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                currentErrorAlert.value.displayErrorMessage(ex.message)
            }
        }
    }
</script>

<template>
    <form @submit="handleUpload">
        <ErrorAlert ref="currentErrorAlert" />
        <SuccessAlert ref="currentSuccessAlert" />

        <BaseFormField labelName="Image name" id="name" name="name" placeholder="Enter image name" v-model="name"/>
        <FileFormField labelName="Image file (extension must be .png)" id="image" name="image" accept="image/*" v-model="image"/>
        <TextAreaField labelName="Description" id="description" name="description" placeholder="Enter description" v-model="description"/>
        <TextAreaField labelName="Alt text (shows if image is not able to load on the screen)" id="alt_text" name="alt_text" placeholder="Enter alt text" v-model="altText"/>

        <SubmitBtn buttonText="Upload" />
  </form>
</template>