<script setup>
    import CreateBtn from '@/components/atoms/buttons/CreateBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ImagesDisplay from '@/components/organisms/images/ImagesDisplay.vue'
    import axios from '@/utils/axios.js'
    import { onMounted, ref } from 'vue';

    const images = ref([])
    const currentErrorAlert = ref(null)

    onMounted(async () => {
        try {
            images.value = (await axios.get('/users/portfolio')).data
        }
        catch (ex){
            if (ex.response){
                currentErrorAlert.value.displayErrorMessage(ex.response.data.message)
            }
        }
    })
</script>

<template>

    <h1>Portfolio</h1>

    <ErrorAlert />
    <SuccessAlert ref="currentSuccessAlert" />

    <CreateBtn to="images/upload" text="Upload an image" />

    <ImagesDisplay :images="images" />
</template>
