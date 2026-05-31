<script setup>
    import { onMounted, ref } from 'vue'
    import { getImageById } from '@/utils/imageLoader'
    import router from '@/router/index.js'

    import SellImageForm from '@/components/organisms/forms/images/SellImageForm.vue'
    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'

    const routeImageId = router.currentRoute.value.params.id
    const image = ref(null)
    const errorAlertRef = ref(null)

    onMounted(async () => {
        image.value = await getImageById(routeImageId)
    })
</script>

<template>

    <h1>Sell image: {{ image?.name }} (Image ID: {{ routeImageId }})</h1>
    
    <ErrorAlert ref="errorAlertRef" />
    <ReturnBtn :to="'/images/' + routeImageId" text="Return back to image details page" />
    <SellImageForm :imageId="routeImageId" :errorAlertRef="errorAlertRef"/>
</template>