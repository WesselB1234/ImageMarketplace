<script setup>
    import { getImageUrl, getPriceFormatted } from '@/utils/stringFormatter'
    import ImageActionsBtn from '@/components/atoms/buttons/img/ImageActionsBtn.vue'

    const props = defineProps({
        image: {
            type: Object,
            required: true
        }
    });
</script>

<template>
    <div class="card h-100 image-card">
        <img class="card-img-top card-image-top" :src="getImageUrl(image.imageId)":alt="image.altText"/>

        <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ image.name }}</h5>

            <p>
                <span v-if="image.isModerated" class="text-danger">This image has been moderated</span>

                <template v-else-if="image.isOnSale && image.price !== null">
                    <span class="font-weight-bold">Price:</span> {{ getPriceFormatted(image.price) }} image tokens
                </template>

                <span v-else class="text-danger">This image is not for sale</span>
            </p>

            <ImageActionsBtn :imageId="image.imageId" />
        </div>
    </div>
</template>
