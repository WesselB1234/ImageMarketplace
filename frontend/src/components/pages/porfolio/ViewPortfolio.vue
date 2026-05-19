<script setup>
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue';
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue';
    import ImagesDisplay from '@/components/organisms/images/ImagesDisplay.vue';
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

    <a class="btn btn-success" href="images/upload">Upload an image</a>

    <ImagesDisplay :images="images" />
</template>
