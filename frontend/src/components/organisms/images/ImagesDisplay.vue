<script setup>
    import ImageCard from '@/components/molecules/images/ImageCard.vue';

    const props = defineProps({
        images: {
            type: Array,
            required: true
        }
    });
</script>


<template>
    <div class="mt-4 row row-cols-sm-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4">
        <span v-if="images.length === 0" class="font-weight-bold">No images found</span>

        <div v-for="img in images" :key="img.imageId" class="col mb-4">
            <ImageCard :image="img" />
        </div>
    </div>
</template>