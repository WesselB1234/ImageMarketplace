<script setup>
    import axios from '@/utils/axios.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import { useRoute } from 'vue-router'
    import router from '@/router'
    import { onMounted, ref } from 'vue'

    import SellImageForm from '@/components/organisms/forms/images/SellImageForm.vue'
    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    
    const errorHandlingStore = useErrorHandlingStore()
    const route = useRoute()

    const routeImageId = route.params.id
    const image = ref(null)
    const errorAlertRef = ref(null)

    async function setImageValue() {
        try {
            const response = await axios.get('/images/' + routeImageId)
            image.value = response.data

            console.log(image.value)
        }
        catch (ex){
            if (ex.response){
                if (ex.response.status === 404) {
                    errorAlertRef.value.shutdown()
                    errorHandlingStore.errorMessage = ex.response.data.message
                    router.push('/portfolio')
                }
                else {
                    errorHandlingStore.errorMessage = ex.response.data.message
                }
            }
            else {
                useErrorHandlingStore.errorMessage = ex.message
            }
        }
    }

    onMounted(async () => setImageValue())
</script>

<template>

    <h1>Sell image: {{ image?.name }} (Image ID: {{ routeImageId }})</h1>
    
    <ErrorAlert ref="errorAlertRef" />
    <ReturnBtn :to="'/images/' + routeImageId" text="Return back to image details page" />
    <SellImageForm :imageId="routeImageId"/>
</template>