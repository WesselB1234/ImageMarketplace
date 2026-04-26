<script setup>
    const props = defineProps({
        message: {
            type: String
        },
    })
</script>

<template>
    <div class="alert alert-danger" role="alert">{{ message }}</div>
</template>
