<script setup>
    import axios from '@/utils/axios.js'
    import { onMounted, ref } from 'vue';
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'

    import CreateBtn from '@/components/atoms/buttons/CreateBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ImagesDisplay from '@/components/organisms/images/ImagesDisplay.vue'

    const errorHandlingStore = useErrorHandlingStore()
    const images = ref([])

    onMounted(async () => {
        try {
            const response = await axios.get('/users/me/portfolio')
            images.value = response.data
        }
        catch (ex){
            if (ex.response){
                errorHandlingStore.errorMessage = ex.response.data.message
            }
        }
    })
</script>

<template>
    <h1>Portfolio</h1>

    <ErrorAlert />
    <SuccessAlert />

    <CreateBtn to="images/upload" text="Upload an image" />

    <ImagesDisplay :images="images" />
</template>
