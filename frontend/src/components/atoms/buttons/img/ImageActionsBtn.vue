<script setup>
    const props = defineProps({
        imageId: {
            type: Number,
        }
    })
</script>

<template>
    <RouterLink :to="`/images/${imageId}`" class="btn btn-primary w-100 mt-auto">View details and actions</RouterLink>
</template>
