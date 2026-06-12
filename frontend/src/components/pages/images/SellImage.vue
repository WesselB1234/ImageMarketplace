<script setup>
    import { onMounted, ref } from 'vue'
    import { getImageById } from '@/utils/imageLoader'
    import router from '@/router/index.js'
    import axios from '@/utils/axios.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'

    import SellImageForm from '@/components/organisms/forms/images/SellImageForm.vue'
    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    
    const errorHandlingStore = useErrorHandlingStore()
    
    const routeImageId = router.currentRoute.value.params.id
    const image = ref(null)
    const vModel = ref({})
    const errorAlertRef = ref(null)

    async function handleSell(e) {  
        try {
            e.preventDefault()

            await axios.patch('/images/' + image.value.imageId + '/sell', vModel.value)

            errorHandlingStore.successMessage = 'Successfully put image on sale.'
            router.push('/images/' + image.value.imageId) 
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
        image.value = await getImageById(routeImageId)
    })
</script>

<template>

    <h1>Sell image: {{ image?.name }} (Image ID: {{ routeImageId }})</h1>
    
    <ErrorAlert ref="errorAlertRef" />
    <ReturnBtn :to="'/images/' + routeImageId" text="Return back to image details page" />
    <SellImageForm :imageId="routeImageId" @submit="handleSell" :vModel="vModel" />
</template>