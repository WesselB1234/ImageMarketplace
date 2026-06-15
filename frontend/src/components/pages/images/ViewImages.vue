<script setup>
    import axios from '@/utils/axios.js'
    import { onMounted, ref } from 'vue';
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router/index.js'
    
    import ImagesDisplay from '@/components/organisms/images/ImagesDisplay.vue'

    const errorHandlingStore = useErrorHandlingStore()
    const images = ref([])

    onMounted(async () => {
        try {
            const response = await axios.get('/images/all-on-sale', {
                params: router.currentRoute.value.query
            })

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

    <h1>Images on sale</h1>

    <ImagesDisplay :images="images" />
</template>
